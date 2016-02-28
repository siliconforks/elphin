<?php

class upload_test extends PHPUnit_Framework_TestCase {
  public function test_upload_basename() {
    $this->assertSame('file.ext', elphin_upload_basename('file.ext'));
    $this->assertSame('file.ext', elphin_upload_basename('C:\\fakepath\\file.ext'));
    $this->assertSame('file.ext', elphin_upload_basename('C:/fakepath/file.ext'));
  }
}
