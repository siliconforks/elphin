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
 * @package Random
 */

require_once dirname(__FILE__) . '/common.inc.php';

/**
 * Generates a cryptographically secure pseudo-random string of bytes.
 *
 * @param  int  $num_bytes  the number of bytes to generate
 * @return  string  a string of length $num_bytes
 */
function elphin_random_bytes($num_bytes) {
  if ($num_bytes == 0) {
    return '';
  }

  if (is_readable('/dev/urandom')) {
    $f = fopen('/dev/urandom', 'rb');
    if ($f) {
      $result = fread($f, $num_bytes);
      fclose($f);
      if (is_string($result) && strlen($result) === $num_bytes) {
        return $result;
      }
    }
  }

  if (function_exists('openssl_random_pseudo_bytes')) {
    $result = openssl_random_pseudo_bytes($num_bytes, $crypto_strong);
    if (is_string($result) && strlen($result) === $num_bytes && $crypto_strong) {
      return $result;
    }
  }

  throw new elphin_exception('Failed to generate random data');
}
