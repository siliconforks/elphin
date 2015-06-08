<?php

/*
Copyright (c) 2015 siliconforks.com

Permission is hereby granted, free of charge, to any person obtaining a copy
of this software and associated documentation files (the "Software"), to deal
in the Software without restriction, including without limitation the rights
to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
copies of the Software, and to permit persons to whom the Software is
furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in
all copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
THE SOFTWARE.
*/

/**
 * @package DOM
 */

require_once dirname(__FILE__) . '/common.inc.php';

/**
 * Loads HTML into a DOMDocument with a specified encoding.
 */
function elphin_load_html(DOMDocument $document, $source, $encoding = NULL) {
  if ($encoding !== NULL) {
    // http://stackoverflow.com/questions/8218230/php-domdocument-loadhtml-not-encoding-utf-8-correctly
    $source = mb_convert_encoding($source, 'HTML-ENTITIES', $encoding);
  }

  $error_reporting = error_reporting();
  error_reporting($error_reporting & ~E_WARNING);
  $result = $document->loadHTML($source);
  error_reporting($error_reporting);
  if (! $result) {
    throw new elphin_exception('Failed to parse HTML');
  }
}
