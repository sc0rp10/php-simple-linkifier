<?php

/**
 * This file is part of sc/php-simple-linkify.
 *
 * © Konstantin Zamyakin <dev@weblab.pro>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sc\Linkifier;

class Linkifier implements LinkifierInterface
{
    protected const DEFAULT_TLDS = [
        'com',
        'ru',
        'net',
        'org',
        'de',
        'jp',
        'uk',
        'br',
        'pl',
        'in',
        'it',
        'fr',
        'au',
        'nl',
        'info',
        'рф',
    ];

    protected const PUNCTUATION = '/[`!\-()\[\]{};:\'".,<>?«»“”‘’]+$/u';

    protected const REGEXES = [
        '/^https?:\/\//u',
        '/(.+)\.(#tlds#)(\/|\?)?$/u',
        '/(.+)\.(#tlds#)(\/|\?)[\w\/-_\.!&]*$/u',
    ];

    private static $base_uri_re = '/(https?:\/\/)?[\w_-]+\.(#tlds#)/u';

    private static $compiled_regexes = [];

    public function __construct(array $tlds = self::DEFAULT_TLDS)
    {
        $this->buildRegexes($tlds);
    }

    /**
     * @param string $text
     * @param array $attrs
     * @return string
     */
    public function linkifyText(string $text, array $attrs = []): string
    {
        $text = trim($text);

        if (!$text) {
            return '';
        }

        $text = str_replace("\r", '', $text);
        $lines = explode("\n", $text);

        foreach ($lines as &$line) {
            $words = explode(' ', $line);

            foreach ($words as &$word) {
                $memo = '';

                if (preg_match(self::PUNCTUATION, $word) && preg_match(static::$base_uri_re, $word)) {
                    $without_mark = preg_replace(static::PUNCTUATION, '', $word);
                    $link = mb_substr($word, 0, strlen($without_mark), 'UTF-8');
                    $memo = str_replace($link, '', $word);

                    if ($memo === ')' && $this->isParenthesisBalanced($word)) {
                        $memo = '';
                    } else {
                        $word = $link;
                    }
                }

                foreach (self::$compiled_regexes as $regex) {
                    $res = preg_match($regex, $word);

                    if ($res) {
                        $word = $this->linkifyWord($word, $attrs).$memo;
                        continue 2;
                    }
                }
            }

            $line = implode(' ', $words);
        }

        return implode("\n", $lines);
    }

    /**
     * @param string $word
     * @param array $attrs
     * @return string
     */
    protected function linkifyWord(string $word, array $attrs = []): string
    {
        $link = $word;

        if (substr($word, 0, strlen('http://')) !== 'http://' && substr($word, 0, strlen('https://')) !== 'https://') {
            $link = 'http://'.$word;
        }

        $attr_string = '';

        foreach ($attrs as $key => $value) {
            $attr_string .= sprintf(' %s="%s"', $key, $value);
        }

        return sprintf('<a href="%s"%s>%s</a>', $link, $attr_string, $word);
    }

    /**
     * @param array $tlds
     */
    private function buildRegexes(array $tlds): void
    {
        if (!static::$compiled_regexes) {
            $str = implode('|', $tlds);

            static::$base_uri_re = str_replace('#tlds#', $str, static::$base_uri_re);

            static::$compiled_regexes = array_map(function ($re) use ($str) {
                return str_replace('#tlds#', $str, $re);
            }, static::REGEXES);
        }
    }

    /**
     * @param string $str
     * @return bool
     */
    private function isParenthesisBalanced(string $str): bool
    {
        $count = 0;
        $length = mb_strlen($str, 'UTF-8');

        for ($i = 0; $i < $length; ++$i) {
            if ($str[$i] == '(') {
                $count += 1;
            } elseif ($str[$i] == ')') {
                $count -= 1;
            }

            if ($count == -1) {
                return false;
            }
        }

        return $count == 0;
    }
}
