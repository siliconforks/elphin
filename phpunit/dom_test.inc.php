<?php

class dom_test extends PHPUnit_Framework_TestCase {
  public function test_iso_8859_1_header_utf_8_meta() {
    // generally, browsers will ignore the meta tag in this case

    $iso_8859_1 = "\xE8\xE9\xEA";
    $utf_8 = "\xC3\xA8\xC3\xA9\xC3\xAA";
    $this->assertSame($iso_8859_1, mb_convert_encoding('&egrave;&eacute;&ecirc;', 'ISO-8859-1', 'HTML-ENTITIES'));
    $this->assertSame($utf_8, mb_convert_encoding('&egrave;&eacute;&ecirc;', 'UTF-8', 'HTML-ENTITIES'));

    $document = new DOMDocument();
    $source = '<html><head><meta http-equiv="Content-Type" content="text/html; charset=UTF-8"></head><body><div id="content">' . $iso_8859_1 . '</div></body></html>';
    elphin_load_html($document, $source, 'ISO-8859-1');
    $content = $document->getElementById('content');
    $text_node = $content->firstChild;

    // the DOM extension uses UTF-8 encoding internally - http://php.net/manual/en/intro.dom.php
    $this->assertSame($utf_8, $text_node->data);
  }

  public function test_utf_8_header_iso_8859_1_meta() {
    // generally, browsers will ignore the meta tag in this case

    $iso_8859_1 = "\xE8\xE9\xEA";
    $utf_8 = "\xC3\xA8\xC3\xA9\xC3\xAA";
    $this->assertSame($iso_8859_1, mb_convert_encoding('&egrave;&eacute;&ecirc;', 'ISO-8859-1', 'HTML-ENTITIES'));
    $this->assertSame($utf_8, mb_convert_encoding('&egrave;&eacute;&ecirc;', 'UTF-8', 'HTML-ENTITIES'));

    $document = new DOMDocument();
    $source = '<html><head><meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1"></head><body><div id="content">' . $utf_8 . '</div></body></html>';
    elphin_load_html($document, $source, 'UTF-8');
    $content = $document->getElementById('content');
    $text_node = $content->firstChild;

    // the DOM extension uses UTF-8 encoding internally - http://php.net/manual/en/intro.dom.php
    $this->assertSame($utf_8, $text_node->data);
  }

  public function test_no_header_utf_8_meta() {
    $utf_8 = "\xC3\xA8\xC3\xA9\xC3\xAA";
    $this->assertSame($utf_8, mb_convert_encoding('&egrave;&eacute;&ecirc;', 'UTF-8', 'HTML-ENTITIES'));

    $document = new DOMDocument();
    $source = '<html><head><meta http-equiv="Content-Type" content="text/html; charset=UTF-8"></head><body><div id="content">' . $utf_8 . '</div></body></html>';
    elphin_load_html($document, $source);
    $content = $document->getElementById('content');
    $text_node = $content->firstChild;

    // the DOM extension uses UTF-8 encoding internally - http://php.net/manual/en/intro.dom.php
    $this->assertSame($utf_8, $text_node->data);
  }

  public function test_no_header_iso_8859_1_meta() {
    $iso_8859_1 = "\xE8\xE9\xEA";
    $utf_8 = "\xC3\xA8\xC3\xA9\xC3\xAA";
    $this->assertSame($iso_8859_1, mb_convert_encoding('&egrave;&eacute;&ecirc;', 'ISO-8859-1', 'HTML-ENTITIES'));
    $this->assertSame($utf_8, mb_convert_encoding('&egrave;&eacute;&ecirc;', 'UTF-8', 'HTML-ENTITIES'));

    $document = new DOMDocument();
    $source = '<html><head><meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1"></head><body><div id="content">' . $iso_8859_1 . '</div></body></html>';
    elphin_load_html($document, $source);
    $content = $document->getElementById('content');
    $text_node = $content->firstChild;

    // the DOM extension uses UTF-8 encoding internally - http://php.net/manual/en/intro.dom.php
    $this->assertSame($utf_8, $text_node->data);
  }

  public function test_no_header_no_meta() {
    $iso_8859_1 = "\xE8\xE9\xEA";
    $utf_8 = "\xC3\xA8\xC3\xA9\xC3\xAA";
    $this->assertSame($iso_8859_1, mb_convert_encoding('&egrave;&eacute;&ecirc;', 'ISO-8859-1', 'HTML-ENTITIES'));
    $this->assertSame($utf_8, mb_convert_encoding('&egrave;&eacute;&ecirc;', 'UTF-8', 'HTML-ENTITIES'));

    $document = new DOMDocument();
    $source = '<html><head></head><body><div id="content">' . $iso_8859_1 . '</div></body></html>';
    elphin_load_html($document, $source);
    $content = $document->getElementById('content');
    $text_node = $content->firstChild;

    // the DOM extension uses UTF-8 encoding internally - http://php.net/manual/en/intro.dom.php
    $this->assertSame($utf_8, $text_node->data);
  }

  /**
   * This test will fail with old versions of PHP (5.4 or earlier).
   * @requires PHP 5.5
   */
  public function test_no_header_utf_8_html5_meta() {
    $utf_8 = "\xC3\xA8\xC3\xA9\xC3\xAA";
    $this->assertSame($utf_8, mb_convert_encoding('&egrave;&eacute;&ecirc;', 'UTF-8', 'HTML-ENTITIES'));

    $document = new DOMDocument();
    $source = '<!DOCTYPE html><html><head><meta charset="UTF-8"></head><body><div id="content">' . $utf_8 . '</div></body></html>';
    elphin_load_html($document, $source);
    $content = $document->getElementById('content');
    $text_node = $content->firstChild;

    // the DOM extension uses UTF-8 encoding internally - http://php.net/manual/en/intro.dom.php
    $this->assertSame($utf_8, $text_node->data);
  }

  public function test_no_header_iso_8859_1_html5_meta() {
    $iso_8859_1 = "\xE8\xE9\xEA";
    $utf_8 = "\xC3\xA8\xC3\xA9\xC3\xAA";
    $this->assertSame($iso_8859_1, mb_convert_encoding('&egrave;&eacute;&ecirc;', 'ISO-8859-1', 'HTML-ENTITIES'));
    $this->assertSame($utf_8, mb_convert_encoding('&egrave;&eacute;&ecirc;', 'UTF-8', 'HTML-ENTITIES'));

    $document = new DOMDocument();
    $source = '<!DOCTYPE html><html><head><meta charset="ISO-8859-1"></head><body><div id="content">' . $iso_8859_1 . '</div></body></html>';
    elphin_load_html($document, $source);
    $content = $document->getElementById('content');
    $text_node = $content->firstChild;

    // the DOM extension uses UTF-8 encoding internally - http://php.net/manual/en/intro.dom.php
    $this->assertSame($utf_8, $text_node->data);
  }

  public function test_error() {
    $document = new DOMDocument();
    $this->setExpectedException('elphin_exception');
    elphin_load_html($document, NULL);
  }
}
