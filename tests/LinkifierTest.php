<?php

class LinkifierTest extends \PHPUnit_Framework_TestCase
{
    const FIXTURES = [
        [
            '1.Test with no links',
            '1.Test with no links',
        ],
        [
            '3.The URL is www.google.com',
            '3.The URL is <a href="http://www.google.com">www.google.com</a>',
        ],
        [
            '4.The URL is http://google.com',
            '4.The URL is <a href="http://google.com">http://google.com</a>',
        ],
        [
            '6.Test with no links.It is sloppy avoiding spaces after the dot',
            '6.Test with no links.It is sloppy avoiding spaces after the dot',
        ],
        [
            'I\'m using vk.com!',
            'I\'m using <a href="http://vk.com">vk.com</a>!',
        ],
        [
            'funniest website 9gag.com)))))',
            'funniest website <a href="http://9gag.com">9gag.com</a>)))))',
        ],
        [
            'best search engine is google.com.',
            'best search engine is <a href="http://google.com">google.com</a>.',
        ],
        [
            'test suite given from https://github.com/SoapBox/linkifyjs/blob/master/test/spec/linkify-string-test.js#L85-L104',
            'test suite given from <a href="https://github.com/SoapBox/linkifyjs/blob/master/test/spec/linkify-string-test.js#L85-L104">https://github.com/SoapBox/linkifyjs/blob/master/test/spec/linkify-string-test.js#L85-L104</a>',
        ],
        [
            'Super long maps URL https://www.google.ca/maps/@43.472082,-80.5426668,18z?hl=en',
            'Super long maps URL <a href="https://www.google.ca/maps/@43.472082,-80.5426668,18z?hl=en">https://www.google.ca/maps/@43.472082,-80.5426668,18z?hl=en</a>'
        ],
        [
            'there is unknown.tld',
            'there is unknown.tld',
        ],
        [
            'google.com-zip',
            'google.com-zip',
        ],
        [
            'google.com.foo.bar',
            'google.com.foo.bar',
        ],
        [
            'crazy russian punycode россия.рф',
            'crazy russian punycode <a href="http://россия.рф">россия.рф</a>',
        ],
        [
            'crazy russian punycode россия.рф/foo/bar',
            'crazy russian punycode <a href="http://россия.рф/foo/bar">россия.рф/foo/bar</a>',
        ],
        [
            'crazy russian punycode http://россия.рф/',
            'crazy russian punycode <a href="http://россия.рф/">http://россия.рф/</a>',
        ],
        [
            'yandex.ru?q=linkify',
            '<a href="http://yandex.ru?q=linkify">yandex.ru?q=linkify</a>',
        ],
    ];

    /** @var \Sc\Linkifier\Linkifier */
    protected $linkifier;

    public function setUp()
    {
        $this->linkifier = new Sc\Linkifier\Linkifier();
    }

    public function testLinkify()
    {
        foreach (self::FIXTURES as $suite) {
            $expected = $suite[1];
            $actual = $this->linkifier->linkifyText($suite[0]);
            self::assertEquals($expected, $actual);
        }
    }
}
