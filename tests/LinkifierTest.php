<?php

class LinkifierTest extends \PHPUnit_Framework_TestCase
{
    const FIXTURES = [
        [
            '1. Test with no links',
            '1. Test with no links',
            '1. Test with no links',
        ],
        [
            '2. The URL is www.google.com',
            '2. The URL is <a href="http://www.google.com">www.google.com</a>',
            '2. The URL is <a href="http://www.google.com" target="_blank" class="link">www.google.com</a>',
        ],
        [
            '3. The URL is http://google.com',
            '3. The URL is <a href="http://google.com">http://google.com</a>',
            '3. The URL is <a href="http://google.com" target="_blank" class="link">http://google.com</a>',
        ],
        [
            '4. Test with no links.It is sloppy avoiding spaces after the dot',
            '4. Test with no links.It is sloppy avoiding spaces after the dot',
            '4. Test with no links.It is sloppy avoiding spaces after the dot',
        ],
        [
            '5. I\'m using vk.com!',
            '5. I\'m using <a href="http://vk.com">vk.com</a>!',
            '5. I\'m using <a href="http://vk.com" target="_blank" class="link">vk.com</a>!',
        ],
        [
            '6. funniest website 9gag.com)))))',
            '6. funniest website <a href="http://9gag.com">9gag.com</a>)))))',
            '6. funniest website <a href="http://9gag.com" target="_blank" class="link">9gag.com</a>)))))',
        ],
        [
            '7. best search engine is google.com.',
            '7. best search engine is <a href="http://google.com">google.com</a>.',
            '7. best search engine is <a href="http://google.com" target="_blank" class="link">google.com</a>.',
        ],
        [
            '8. test suite taken from https://github.com/SoapBox/linkifyjs/blob/master/test/spec/linkify-string-test.js#L85-L104',
            '8. test suite taken from <a href="https://github.com/SoapBox/linkifyjs/blob/master/test/spec/linkify-string-test.js#L85-L104">https://github.com/SoapBox/linkifyjs/blob/master/test/spec/linkify-string-test.js#L85-L104</a>',
            '8. test suite taken from <a href="https://github.com/SoapBox/linkifyjs/blob/master/test/spec/linkify-string-test.js#L85-L104" target="_blank" class="link">https://github.com/SoapBox/linkifyjs/blob/master/test/spec/linkify-string-test.js#L85-L104</a>',
        ],
        [
            '9. Super long maps URL https://www.google.ca/maps/@43.472082,-80.5426668,18z?hl=en',
            '9. Super long maps URL <a href="https://www.google.ca/maps/@43.472082,-80.5426668,18z?hl=en">https://www.google.ca/maps/@43.472082,-80.5426668,18z?hl=en</a>',
            '9. Super long maps URL <a href="https://www.google.ca/maps/@43.472082,-80.5426668,18z?hl=en" target="_blank" class="link">https://www.google.ca/maps/@43.472082,-80.5426668,18z?hl=en</a>',
        ],
        [
            '10. there is unknown.tld',
            '10. there is unknown.tld',
            '10. there is unknown.tld',
        ],
        [
            '11. google.com-zip',
            '11. google.com-zip',
            '11. google.com-zip',
        ],
        [
            '12. google.com.foo.bar',
            '12. google.com.foo.bar',
            '12. google.com.foo.bar',
        ],
        [
            '13. crazy russian punycode россия.рф',
            '13. crazy russian punycode <a href="http://россия.рф">россия.рф</a>',
            '13. crazy russian punycode <a href="http://россия.рф" target="_blank" class="link">россия.рф</a>',
        ],
        [
            '14. crazy russian punycode россия.рф/foo/bar',
            '14. crazy russian punycode <a href="http://россия.рф/foo/bar">россия.рф/foo/bar</a>',
            '14. crazy russian punycode <a href="http://россия.рф/foo/bar" target="_blank" class="link">россия.рф/foo/bar</a>',
        ],
        [
            '15. crazy russian punycode http://россия.рф/',
            '15. crazy russian punycode <a href="http://россия.рф/">http://россия.рф/</a>',
            '15. crazy russian punycode <a href="http://россия.рф/" target="_blank" class="link">http://россия.рф/</a>',
        ],
        [
            '16. yandex.ru?q=linkify',
            '16. <a href="http://yandex.ru?q=linkify">yandex.ru?q=linkify</a>',
            '16. <a href="http://yandex.ru?q=linkify" target="_blank" class="link">yandex.ru?q=linkify</a>',
        ],
        [
            "17. lorem ipsum google.com\r\nfoobar",
            "17. lorem ipsum <a href=\"http://google.com\">google.com</a>\nfoobar",
            "17. lorem ipsum <a href=\"http://google.com\" target=\"_blank\" class=\"link\">google.com</a>\nfoobar",
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

    public function testLinkifyWithAttrs()
    {
        foreach (self::FIXTURES as $suite) {
            $expected = $suite[2];
            $actual = $this->linkifier->linkifyText($suite[0], [
                'target' => '_blank',
                'class' => 'link',
            ]);
            self::assertEquals($expected, $actual);
        }
    }
}
