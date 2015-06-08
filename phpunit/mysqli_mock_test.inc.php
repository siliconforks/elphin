<?php

class mysqli_mock_test extends PHPUnit_Framework_TestCase {
  public function test_insert() {
    $mysqli = $this->get_mock_mysqli();
    $mysqli->expects($this->once())
           ->method('query')
           ->with($this->equalTo('INSERT INTO `t` (`c1`, `c2`, `c3`, `c4`, `c5`, `c6`) VALUES (\'a\', \'1\', 1, 0, NULL, NOW())'));
    $elphin_mysqli = new elphin_mysqli($mysqli);
    $elphin_mysqli->insert('t', array('c1' => 'a', 'c2' => 1, 'c3' => TRUE, 'c4' => FALSE, 'c5' => NULL, 'c6' => new elphin_raw_sql('NOW()')));
  }

  public function test_update() {
    $mysqli = $this->get_mock_mysqli();
    $mysqli->expects($this->once())
           ->method('query')
           ->with($this->equalTo('UPDATE `t` SET `c1` = \'a\', `c2` = \'b\' WHERE `id` = 123'));
    $elphin_mysqli = new elphin_mysqli($mysqli);
    $elphin_mysqli->update('t', array('c1' => 'a', 'c2' => 'b'), '`id` = 123');
  }

  public function test_delete() {
    $mysqli = $this->get_mock_mysqli();
    $mysqli->expects($this->once())
           ->method('query')
           ->with($this->equalTo('DELETE FROM `t` WHERE `id` = 123'));
    $elphin_mysqli = new elphin_mysqli($mysqli);
    $elphin_mysqli->delete('t', '`id` = 123');
  }

  public function test_insert_error() {
    $mysqli = $this->get_mock_error_mysqli();
    $mysqli->expects($this->once())
           ->method('query')
           ->with($this->equalTo('INSERT INTO `t` (`c1`, `c2`) VALUES (\'a\', \'b\')'));
    $elphin_mysqli = new elphin_mysqli($mysqli);
    $this->setExpectedException('elphin_mysqli_exception');
    $elphin_mysqli->insert('t', array('c1' => 'a', 'c2' => 'b'));
  }

  public function test_update_error() {
    $mysqli = $this->get_mock_error_mysqli();
    $mysqli->expects($this->once())
           ->method('query')
           ->with($this->equalTo('UPDATE `t` SET `c1` = \'a\', `c2` = \'b\' WHERE `id` = 123'));
    $elphin_mysqli = new elphin_mysqli($mysqli);
    $this->setExpectedException('elphin_mysqli_exception');
    $elphin_mysqli->update('t', array('c1' => 'a', 'c2' => 'b'), '`id` = 123');
  }

  public function test_delete_error() {
    $mysqli = $this->get_mock_error_mysqli();
    $mysqli->expects($this->once())
           ->method('query')
           ->with($this->equalTo('DELETE FROM `t` WHERE `id` = 123'));
    $elphin_mysqli = new elphin_mysqli($mysqli);
    $this->setExpectedException('elphin_mysqli_exception');
    $elphin_mysqli->delete('t', '`id` = 123');
  }

  public function test_select_all_rows() {
    $mysqli = $this->get_mock_select_2_rows_mysqli();
    $sql = 'SELECT * FROM `t`';
    $mysqli->expects($this->once())
           ->method('query')
           ->with($this->equalTo($sql));
    $elphin_mysqli = new elphin_mysqli($mysqli);
    $this->assertSame(array(array('a' => '1', 'b' => '2'), array('a' => '3', 'b' => '4')), $elphin_mysqli->select_all_rows($sql));
  }

  public function test_select_all_rows_error() {
    $mysqli = $this->get_mock_error_mysqli();
    $sql = 'SELECT * FROM `t`';
    $mysqli->expects($this->once())
           ->method('query')
           ->with($this->equalTo($sql));
    $elphin_mysqli = new elphin_mysqli($mysqli);
    $this->setExpectedException('elphin_mysqli_exception');
    $elphin_mysqli->select_all_rows($sql);
  }

  public function test_select_single_row() {
    $mysqli = $this->get_mock_select_1_row_mysqli();
    $sql = 'SELECT * FROM `t`';
    $mysqli->expects($this->once())
           ->method('query')
           ->with($this->equalTo($sql));
    $elphin_mysqli = new elphin_mysqli($mysqli);
    $this->assertSame(array('a' => '1', 'b' => '2'), $elphin_mysqli->select_single_row($sql));
  }

  public function test_select_single_row_error() {
    $mysqli = $this->get_mock_error_mysqli();
    $sql = 'SELECT * FROM `t`';
    $mysqli->expects($this->once())
           ->method('query')
           ->with($this->equalTo($sql));
    $elphin_mysqli = new elphin_mysqli($mysqli);
    $this->setExpectedException('elphin_mysqli_exception');
    $elphin_mysqli->select_single_row($sql);
  }

  public function test_select_single_row_0_rows() {
    $mysqli = $this->get_mock_select_0_rows_mysqli();
    $sql = 'SELECT * FROM `t`';
    $mysqli->expects($this->once())
           ->method('query')
           ->with($this->equalTo($sql));
    $elphin_mysqli = new elphin_mysqli($mysqli);
    $this->setExpectedException('elphin_mysqli_exception');
    $elphin_mysqli->select_single_row($sql);
  }

  public function test_select_single_row_2_rows() {
    $mysqli = $this->get_mock_select_2_rows_mysqli();
    $sql = 'SELECT * FROM `t`';
    $mysqli->expects($this->once())
           ->method('query')
           ->with($this->equalTo($sql));
    $elphin_mysqli = new elphin_mysqli($mysqli);
    $this->setExpectedException('elphin_mysqli_exception');
    $elphin_mysqli->select_single_row($sql);
  }

  public function test_select_single_column() {
    $mysqli = $this->get_mock_select_2_rows_mysqli();
    $sql = 'SELECT * FROM `t`';
    $mysqli->expects($this->once())
           ->method('query')
           ->with($this->equalTo($sql));
    $elphin_mysqli = new elphin_mysqli($mysqli);
    $this->assertSame(array('1', '3'), $elphin_mysqli->select_single_column($sql));
  }

  public function test_select_single_column_error() {
    $mysqli = $this->get_mock_error_mysqli();
    $sql = 'SELECT * FROM `t`';
    $mysqli->expects($this->once())
           ->method('query')
           ->with($this->equalTo($sql));
    $elphin_mysqli = new elphin_mysqli($mysqli);
    $this->setExpectedException('elphin_mysqli_exception');
    $elphin_mysqli->select_single_column($sql);
  }

  public function test_select_single_value() {
    $mysqli = $this->get_mock_select_1_row_mysqli();
    $sql = 'SELECT * FROM `t`';
    $mysqli->expects($this->once())
           ->method('query')
           ->with($this->equalTo($sql));
    $elphin_mysqli = new elphin_mysqli($mysqli);
    $this->assertSame('1', $elphin_mysqli->select_single_value($sql));
  }

  public function test_select_single_value_error() {
    $mysqli = $this->get_mock_error_mysqli();
    $sql = 'SELECT * FROM `t`';
    $mysqli->expects($this->once())
           ->method('query')
           ->with($this->equalTo($sql));
    $elphin_mysqli = new elphin_mysqli($mysqli);
    $this->setExpectedException('elphin_mysqli_exception');
    $elphin_mysqli->select_single_value($sql);
  }

  public function test_select_single_value_0_rows() {
    $mysqli = $this->get_mock_select_0_rows_mysqli();
    $sql = 'SELECT * FROM `t`';
    $mysqli->expects($this->once())
           ->method('query')
           ->with($this->equalTo($sql));
    $elphin_mysqli = new elphin_mysqli($mysqli);
    $this->setExpectedException('elphin_mysqli_exception');
    $elphin_mysqli->select_single_value($sql);
  }

  public function test_select_single_value_2_rows() {
    $mysqli = $this->get_mock_select_2_rows_mysqli();
    $sql = 'SELECT * FROM `t`';
    $mysqli->expects($this->once())
           ->method('query')
           ->with($this->equalTo($sql));
    $elphin_mysqli = new elphin_mysqli($mysqli);
    $this->setExpectedException('elphin_mysqli_exception');
    $elphin_mysqli->select_single_value($sql);
  }

  private function get_mock_mysqli() {
    $mysqli = $this->getMockBuilder('stdClass')
                   ->setMethods(array('real_escape_string', 'query'))
                   ->getMock();
    $mysqli->method('real_escape_string')
           ->will($this->returnCallback('addslashes'));
    $mysqli->method('query')
           ->willReturn(TRUE);
    return $mysqli;
  }

  private function get_mock_error_mysqli() {
    $mysqli = $this->getMockBuilder('stdClass')
                   ->setMethods(array('real_escape_string', 'query'))
                   ->getMock();
    $mysqli->method('real_escape_string')
           ->will($this->returnCallback('addslashes'));
    $mysqli->method('query')
           ->willReturn(FALSE);
    $mysqli->error = 'error message';
    $mysqli->errno = 123;
    return $mysqli;
  }

  private function get_mock_select_1_row_mysqli() {
    $result = $this->getMockBuilder('stdClass')
                   ->setMethods(array('fetch_assoc', 'fetch_row', 'close'))
                   ->getMock();
    $result->method('fetch_assoc')
           ->will($this->onConsecutiveCalls(array('a' => '1', 'b' => '2'), NULL));
    $result->method('fetch_row')
           ->will($this->onConsecutiveCalls(array('1'), NULL));
    $mysqli = $this->getMockBuilder('stdClass')
                   ->setMethods(array('query'))
                   ->getMock();
    $mysqli->method('query')
           ->willReturn($result);
    return $mysqli;
  }

  private function get_mock_select_2_rows_mysqli() {
    $result = $this->getMockBuilder('stdClass')
                   ->setMethods(array('fetch_assoc', 'fetch_row', 'close'))
                   ->getMock();
    $result->method('fetch_assoc')
           ->will($this->onConsecutiveCalls(array('a' => '1', 'b' => '2'), array('a' => '3', 'b' => '4'), NULL));
    $result->method('fetch_row')
           ->will($this->onConsecutiveCalls(array('1'), array('3'), NULL));
    $mysqli = $this->getMockBuilder('stdClass')
                   ->setMethods(array('query'))
                   ->getMock();
    $mysqli->method('query')
           ->willReturn($result);
    return $mysqli;
  }

  private function get_mock_select_0_rows_mysqli() {
    $result = $this->getMockBuilder('stdClass')
                   ->setMethods(array('fetch_assoc', 'fetch_row', 'close'))
                   ->getMock();
    $result->method('fetch_assoc')
           ->willReturn(NULL);
    $result->method('fetch_row')
           ->willReturn(NULL);
    $mysqli = $this->getMockBuilder('stdClass')
                   ->setMethods(array('query'))
                   ->getMock();
    $mysqli->method('query')
           ->willReturn($result);
    return $mysqli;
  }
}
