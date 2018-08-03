<?php

class mysqli_real_test extends PHPUnit_Extensions_Database_TestCase {
  private static $pdo = NULL;

  private $connection = NULL;

  public final function getConnection() {
    global $ELPHIN_MYSQL_HOST, $ELPHIN_MYSQL_USER, $ELPHIN_MYSQL_PASSWORD, $ELPHIN_MYSQL_DATABASE;

    if ($this->connection === null) {
      if (self::$pdo == null) {
        self::$pdo = new PDO('mysql:host=' . $ELPHIN_MYSQL_HOST . ';dbname=' . $ELPHIN_MYSQL_DATABASE, $ELPHIN_MYSQL_USER, $ELPHIN_MYSQL_PASSWORD);
      }
      $this->connection = $this->createDefaultDBConnection(self::$pdo, $ELPHIN_MYSQL_DATABASE);
    }
    return $this->connection;
  }

  public function getDataSet() {
    return $this->createXMLDataSet(dirname(__FILE__) . '/database.xml');
  }

  /**
   * @group database
   */
  public function test_insert() {
    $mysqli = $this->get_mysqli();
    $elphin_mysqli = new elphin_mysqli($mysqli);
    $elphin_mysqli->insert('t', array('s' => 'c', 'b' => FALSE));
    $elphin_mysqli->insert('t', array('s' => 'd', 'b' => TRUE));
    $elphin_mysqli->insert('t', array('s' => NULL, 'b' => new elphin_raw_sql('IF(1 < 2, 1, 0)')));
    $this->assertEquals(5, $this->connection->getRowCount('t'));
    $expected = $this->createXmlDataSet(dirname(__FILE__) . '/insert.xml')->getTable('t');
    $this->assertTablesEqual($expected, $this->getConnection()->createQueryTable('t', 'SELECT * FROM `t` ORDER BY `t`'));
  }

  /**
   * @group database
   */
  public function test_insert_error() {
    $mysqli = $this->get_mysqli();
    $elphin_mysqli = new elphin_mysqli($mysqli);
    $this->setExpectedException('elphin_mysqli_exception');
    $elphin_mysqli->insert('t2', array('s' => 'c', 'b' => FALSE));
  }

  /**
   * @group database
   */
  public function test_update() {
    $mysqli = $this->get_mysqli();
    $elphin_mysqli = new elphin_mysqli($mysqli);
    $elphin_mysqli->update('t', array('s' => 'c', 'b' => FALSE), '`t` = 1');
    $this->assertEquals(2, $this->connection->getRowCount('t'));
    $expected = $this->createXmlDataSet(dirname(__FILE__) . '/update.xml')->getTable('t');
    $this->assertTablesEqual($expected, $this->getConnection()->createQueryTable('t', 'SELECT * FROM `t` ORDER BY `t`'));
  }

  /**
   * @group database
   */
  public function test_update_error() {
    $mysqli = $this->get_mysqli();
    $elphin_mysqli = new elphin_mysqli($mysqli);
    $this->setExpectedException('elphin_mysqli_exception');
    $elphin_mysqli->update('t2', array('s' => 'c', 'b' => FALSE), '`t` = 1');
  }

  /**
   * @group database
   */
  public function test_delete() {
    $mysqli = $this->get_mysqli();
    $elphin_mysqli = new elphin_mysqli($mysqli);
    $elphin_mysqli->delete('t', '`t` = 1');
    $this->assertEquals(1, $this->connection->getRowCount('t'));
    $expected = $this->createXmlDataSet(dirname(__FILE__) . '/delete.xml')->getTable('t');
    $this->assertTablesEqual($expected, $this->getConnection()->createQueryTable('t', 'SELECT * FROM `t` ORDER BY `t`'));
  }

  /**
   * @group database
   */
  public function test_delete_error() {
    $mysqli = $this->get_mysqli();
    $elphin_mysqli = new elphin_mysqli($mysqli);
    $this->setExpectedException('elphin_mysqli_exception');
    $elphin_mysqli->delete('t2', '`t` = 1');
  }

  /**
   * @group database
   */
  public function test_select_all_rows() {
    $mysqli = $this->get_mysqli();
    $elphin_mysqli = new elphin_mysqli($mysqli);
    $sql = 'SELECT * FROM `t` ORDER BY `t`';
    $this->assertSame(array(array('t' => '1', 's' => 'a', 'b' => '1'), array('t' => '2', 's' => 'b', 'b' => '0')), $elphin_mysqli->select_all_rows($sql));
  }

  /**
   * @group database
   */
  public function test_select_all_rows_error() {
    $mysqli = $this->get_mysqli();
    $elphin_mysqli = new elphin_mysqli($mysqli);
    $this->setExpectedException('elphin_mysqli_exception');
    $sql = 'SELECT * FROM `t2` ORDER BY `t`';
    $elphin_mysqli->select_all_rows($sql);
  }

  /**
   * @group database
   */
  public function test_select_single_column() {
    $mysqli = $this->get_mysqli();
    $elphin_mysqli = new elphin_mysqli($mysqli);
    $sql = 'SELECT `t` FROM `t` ORDER BY `t`';
    $this->assertSame(array('1', '2'), $elphin_mysqli->select_single_column($sql));
  }

  /**
   * @group database
   */
  public function test_select_single_column_error() {
    $mysqli = $this->get_mysqli();
    $elphin_mysqli = new elphin_mysqli($mysqli);
    $this->setExpectedException('elphin_mysqli_exception');
    $sql = 'SELECT `t` FROM `t2` ORDER BY `t`';
    $elphin_mysqli->select_single_column($sql);
  }

  /**
   * @group database
   */
  public function test_select_single_row() {
    $mysqli = $this->get_mysqli();
    $elphin_mysqli = new elphin_mysqli($mysqli);
    $sql = 'SELECT * FROM `t` WHERE `t` = 1 ORDER BY `t`';
    $this->assertSame(array('t' => '1', 's' => 'a', 'b' => '1'), $elphin_mysqli->select_single_row($sql));
  }

  /**
   * @group database
   */
  public function test_select_single_row_error() {
    $mysqli = $this->get_mysqli();
    $elphin_mysqli = new elphin_mysqli($mysqli);
    $this->setExpectedException('elphin_mysqli_exception');
    $sql = 'SELECT * FROM `t2` WHERE `t` = 1 ORDER BY `t`';
    $elphin_mysqli->select_single_row($sql);
  }

  /**
   * @group database
   */
  public function test_select_single_row_0_rows() {
    $mysqli = $this->get_mysqli();
    $elphin_mysqli = new elphin_mysqli($mysqli);
    $this->setExpectedException('elphin_mysqli_exception');
    $sql = 'SELECT * FROM `t` WHERE `t` = 0 ORDER BY `t`';
    $elphin_mysqli->select_single_row($sql);
  }

  /**
   * @group database
   */
  public function test_select_single_row_2_rows() {
    $mysqli = $this->get_mysqli();
    $elphin_mysqli = new elphin_mysqli($mysqli);
    $this->setExpectedException('elphin_mysqli_exception');
    $sql = 'SELECT * FROM `t` ORDER BY `t`';
    $elphin_mysqli->select_single_row($sql);
  }

  /**
   * @group database
   */
  public function test_select_single_value() {
    $mysqli = $this->get_mysqli();
    $elphin_mysqli = new elphin_mysqli($mysqli);
    $sql = 'SELECT `s` FROM `t` WHERE `t` = 1';
    $this->assertSame('a', $elphin_mysqli->select_single_value($sql));
  }

  /**
   * @group database
   */
  public function test_select_single_value_error() {
    $mysqli = $this->get_mysqli();
    $elphin_mysqli = new elphin_mysqli($mysqli);
    $this->setExpectedException('elphin_mysqli_exception');
    $sql = 'SELECT `s` FROM `t2` WHERE `t` = 1';
    $elphin_mysqli->select_single_value($sql);
  }

  /**
   * @group database
   */
  public function test_select_single_value_0_rows() {
    $mysqli = $this->get_mysqli();
    $elphin_mysqli = new elphin_mysqli($mysqli);
    $this->setExpectedException('elphin_mysqli_exception');
    $sql = 'SELECT `s` FROM `t` WHERE `t` = 0';
    $elphin_mysqli->select_single_value($sql);
  }

  /**
   * @group database
   */
  public function test_select_single_value_2_rows() {
    $mysqli = $this->get_mysqli();
    $elphin_mysqli = new elphin_mysqli($mysqli);
    $this->setExpectedException('elphin_mysqli_exception');
    $sql = 'SELECT `s` FROM `t`';
    $elphin_mysqli->select_single_value($sql);
  }

  /**
   * @group database
   */
  public function test_select_all_rows_hash() {
    $mysqli = $this->get_mysqli();
    $elphin_mysqli = new elphin_mysqli($mysqli);
    $sql = 'SELECT * FROM `t` ORDER BY `t`';
    $this->assertSame(array(1 => array('t' => '1', 's' => 'a', 'b' => '1'), 2 => array('t' => '2', 's' => 'b', 'b' => '0')), $elphin_mysqli->select_all_rows_hash($sql, 't'));
  }

  /**
   * @group database
   */
  public function test_select_single_column_hash() {
    $mysqli = $this->get_mysqli();
    $elphin_mysqli = new elphin_mysqli($mysqli);
    $sql = 'SELECT `t`, `s` FROM `t` ORDER BY `t`';
    $this->assertSame(array(1 => 'a', 2 => 'b'), $elphin_mysqli->select_single_column_hash($sql));
  }

  /**
   * @group database
   */
  public function test_pattern_match() {
    $mysqli = $this->get_mysqli();
    $elphin_mysqli = new elphin_mysqli($mysqli);

    $sql = 'SELECT `pattern`, `s` FROM `pattern` WHERE `s` LIKE ' . $elphin_mysqli->quote('50% off') . ' ORDER BY `pattern`';
    $this->assertSame(array(1 => '50% off', 2 => '50 dollars off'), $elphin_mysqli->select_single_column_hash($sql));
    $sql = 'SELECT `pattern`, `s` FROM `pattern` WHERE `s` LIKE ' . $elphin_mysqli->quote($elphin_mysqli->escape_for_pattern_match('50% off')) . ' ORDER BY `pattern`';
    $this->assertSame(array(1 => '50% off'), $elphin_mysqli->select_single_column_hash($sql));

    $sql = 'SELECT `pattern`, `s` FROM `pattern` WHERE `s` LIKE ' . $elphin_mysqli->quote('%_italics_') . ' ORDER BY `pattern`';
    $this->assertSame(array(3 => 'Use _underscores_ for _italics_', 4 => 'Use *asterisks* for *italics*'), $elphin_mysqli->select_single_column_hash($sql));
    $sql = 'SELECT `pattern`, `s` FROM `pattern` WHERE `s` LIKE ' . $elphin_mysqli->quote('%' . $elphin_mysqli->escape_for_pattern_match('_italics_')) . ' ORDER BY `pattern`';
    $this->assertSame(array(3 => 'Use _underscores_ for _italics_'), $elphin_mysqli->select_single_column_hash($sql));

    $sql = 'SELECT `pattern`, `s` FROM `pattern` WHERE `s` LIKE ' . $elphin_mysqli->quote('C:%') . ' ORDER BY `pattern`';
    $this->assertSame(array(5 => 'C:\Program Files', 6 => 'C:/Program Files'), $elphin_mysqli->select_single_column_hash($sql));
    $sql = 'SELECT `pattern`, `s` FROM `pattern` WHERE `s` LIKE ' . $elphin_mysqli->quote('C:/%') . ' ORDER BY `pattern`';
    $this->assertSame(array(6 => 'C:/Program Files'), $elphin_mysqli->select_single_column_hash($sql));
    $sql = 'SELECT `pattern`, `s` FROM `pattern` WHERE `s` LIKE ' . $elphin_mysqli->quote($elphin_mysqli->escape_for_pattern_match('C:\\') . '%') . ' ORDER BY `pattern`';
    $this->assertSame(array(5 => 'C:\Program Files'), $elphin_mysqli->select_single_column_hash($sql));
  }

  private function get_mysqli() {
    global $ELPHIN_MYSQL_HOST, $ELPHIN_MYSQL_USER, $ELPHIN_MYSQL_PASSWORD, $ELPHIN_MYSQL_DATABASE;

    $mysqli = new mysqli($ELPHIN_MYSQL_HOST, $ELPHIN_MYSQL_USER, $ELPHIN_MYSQL_PASSWORD, $ELPHIN_MYSQL_DATABASE);
    return $mysqli;
  }
}
