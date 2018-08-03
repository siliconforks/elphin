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
 * @package URL
 */

require_once dirname(__FILE__) . '/common.inc.php';

/**
* Gets the current URL.
* @return  string  the URL used to execute the current script
* @throws  elphin_exception  if the URL cannot be determined
*/
function elphin_get_current_url() {
  if (! isset($_SERVER['REQUEST_URI'])) {
    throw new elphin_exception('REQUEST_URI not defined');
  }

  $request_uri = $_SERVER['REQUEST_URI'];

  if (preg_match('@^https?://@i', $request_uri)) {
    return $request_uri;
  }

  if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== '' && strtolower($_SERVER['HTTPS']) !== 'off') {
    $protocol = 'https://';
  }
  else {
    $protocol = 'http://';
  }

  /*
  Note that the Host HTTP header should include the port, if any, according to
  RFC 2616 section 14.23.  All major web browsers do this.
  */
  if (! isset($_SERVER['HTTP_HOST'])) {
    throw new elphin_exception('HTTP_HOST not defined');
  }

  $url = $protocol . $_SERVER['HTTP_HOST'] . $request_uri;
  return $url;
}

/**
 * Gets a "slug" (URL component) for an ASCII string.
 * @param  string  $s  an ASCII string
 * @return  string  the slug
 */
function elphin_get_url_slug($s) {
  $s = strtolower($s);
  $s = str_replace('@', ' at ', $s);
  $s = str_replace('%', ' percent ', $s);
  $s = str_replace('&', ' and ', $s);
  $s = str_replace('+', ' plus ', $s);
  $s = str_replace('=', ' equals ', $s);
  $s = str_replace('\'', '', $s);
  $s = str_replace('.', '', $s);
  $s = preg_replace('/[^A-Za-z0-9-]/', '-', $s);
  $s = preg_replace('/-+/', '-', $s);
  $s = trim($s, '-');
  return $s;
}
