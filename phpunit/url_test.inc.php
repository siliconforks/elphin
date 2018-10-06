<?php

class url_test extends PHPUnit_Framework_TestCase {
  public function test_get_current_url() {
    unset($_SERVER['HTTPS']);
    $_SERVER['HTTP_HOST'] = 'example.com';
    $_SERVER['REQUEST_URI'] = '/';
    $this->assertSame('http://example.com/', elphin_get_current_url());
  }

  public function test_get_current_url_absolute_request_uri() {
    $_SERVER['REQUEST_URI'] = 'http://example.com/';
    $this->assertSame('http://example.com/', elphin_get_current_url());
  }

  public function test_get_current_url_https() {
    $_SERVER['HTTPS'] = 'on';
    $_SERVER['HTTP_HOST'] = 'example.com';
    $_SERVER['REQUEST_URI'] = '/';
    $this->assertSame('https://example.com/', elphin_get_current_url());
  }

  public function test_get_current_url_no_request_uri() {
    unset($_SERVER['REQUEST_URI']);
    $this->setExpectedException('elphin_exception');
    elphin_get_current_url();
  }

  public function test_get_current_url_no_http_host() {
    unset($_SERVER['HTTP_HOST']);
    $_SERVER['REQUEST_URI'] = '/';
    $this->setExpectedException('elphin_exception');
    elphin_get_current_url();
  }

  public function test_slug() {
    $this->assertEquals('', elphin_get_url_slug(''));
    $this->assertEquals('example', elphin_get_url_slug('example'));
    $this->assertEquals('example', elphin_get_url_slug('Example'));
    $this->assertEquals('foo-bar', elphin_get_url_slug('foo bar'));
    $this->assertEquals('foo-bar', elphin_get_url_slug('foo  bar'));
    $this->assertEquals('foo-bar', elphin_get_url_slug(' foo bar '));
    $this->assertEquals('hello-world', elphin_get_url_slug('Hello, World!'));
    $this->assertEquals('were-1', elphin_get_url_slug('We\'re #1'));
    $this->assertEquals('50-percent-off', elphin_get_url_slug('50% off'));
    $this->assertEquals('salt-and-pepper', elphin_get_url_slug('salt & pepper'));
    $this->assertEquals('stars', elphin_get_url_slug('***stars***'));
    $this->assertEquals('text-in-parentheses', elphin_get_url_slug('text (in parentheses)'));
    $this->assertEquals('1-plus-1-equals-2', elphin_get_url_slug('1 + 1 = 2'));
    $this->assertEquals('3-2-equals-1', elphin_get_url_slug('3 - 2 = 1'));
    $this->assertEquals('dramatic-pause', elphin_get_url_slug('dramatic - pause'));
    $this->assertEquals('punctuation-the-colon', elphin_get_url_slug('punctuation: the colon'));
    $this->assertEquals('punctuation-the-semicolon', elphin_get_url_slug('punctuation; the semicolon'));
    $this->assertEquals('i-said-quotation-marks', elphin_get_url_slug('I said, "quotation marks"'));
    $this->assertEquals('i-said-quotation-marks', elphin_get_url_slug('I said, \'quotation marks\''));
    $this->assertEquals('washington-dc', elphin_get_url_slug('Washington, D.C.'));
    $this->assertEquals('washington-d-c', elphin_get_url_slug('Washington, D. C.'));
    $this->assertEquals('and-or', elphin_get_url_slug('and/or'));

    // these are suboptimal
    $this->assertEquals('that-will-be-123', elphin_get_url_slug('That will be $1.23'));
    $this->assertEquals('send-email-to-example-at-examplecom', elphin_get_url_slug('send email to example@example.com'));
  }

  public function test_unparse_url() {
    $url = 'http://username:password@hostname:9090/path?arg=value#anchor';
    $parsed_url = parse_url($url);
    $this->assertEquals($url, elphin_unparse_url($parsed_url));

    $url = '//www.example.com/path?googleguy=googley';
    $parsed_url = parse_url($url);
    $this->assertEquals($url, elphin_unparse_url($parsed_url));

    $url = '/absolute/path';
    $parsed_url = parse_url($url);
    $this->assertEquals($url, elphin_unparse_url($parsed_url));

    $url = './relative/path';
    $parsed_url = parse_url($url);
    $this->assertEquals($url, elphin_unparse_url($parsed_url));

    $url = 'relative/path';
    $parsed_url = parse_url($url);
    $this->assertEquals($url, elphin_unparse_url($parsed_url));
  }
}
