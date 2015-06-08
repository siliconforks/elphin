<?php

class random_test extends PHPUnit_Framework_TestCase {
  public function test_random() {
    $random1 = elphin_random_bytes(16);
    $this->assertInternalType('string', $random1);
    $this->assertSame(16, strlen($random1));
    $random2 = elphin_random_bytes(16);
    $this->assertInternalType('string', $random2);
    $this->assertSame(16, strlen($random2));
    $this->assertNotEquals($random1, $random2);
  }

  public function test_zero_length() {
    $this->assertSame('', elphin_random_bytes(0));
  }
}
