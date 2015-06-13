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
 * @package Mysqli
 */

require_once dirname(__FILE__) . '/common.inc.php';

/**
 * @package Mysqli
 */
class elphin_mysqli_exception extends elphin_exception {
}

class elphin_record_not_found_exception extends elphin_mysqli_exception {
}

class elphin_multiple_records_found_exception extends elphin_mysqli_exception {
}

class elphin_raw_sql {
  public $sql;

  public function __construct($sql) {
    $this->sql = $sql;
  }
}

class elphin_mysqli {
  public $mysqli;

  /**
   * Constructs an elphin_mysqli object from a mysqli object.
   * @param  mysqli  $mysqli  the mysqli object
   */
  public function __construct($mysqli) {
    $this->mysqli = $mysqli;
  }

  /**
   * Executes an SQL INSERT statement.
   * The array passed to this function represents a single row to insert; it is
   * an associative array with the keys representing the columns of the table
   * and the values representing the values of those columns.  The values can
   * be strings, integers, booleans, NULL, or elphin_raw_sql objects.  All values
   * will be quoted and escaped automatically except elphin_raw_sql objects.
   * @param  string  $table  the database table
   * @param  array  $data  the data to insert
   */
  public function insert($table, array $data) {
    $mysqli = $this->mysqli;

    $columns = array();
    $values = array();
    foreach ($data as $key => $value) {
      $columns[] = '`' . $key . '`';
      $values[] = $this->quote($value);
    }

    $sql = 'INSERT INTO `' . $table . '` (' . implode(', ', $columns) . ') VALUES (' . implode(', ', $values) . ')';
    if (! $mysqli->query($sql)) {
      throw new elphin_mysqli_exception('Insert failed: ' . $mysqli->error, $mysqli->errno);
    }
  }

  /**
   * Executes an SQL UPDATE statement.
   * The array passed to this function represents the values to update; the
   * values permitted are the same as for the insert method.
   * @param  string  $table  the database table
   * @param  array  $data  the data to update
   * @param  string  $where  SQL code for the WHERE clause of the UPDATE statement
   */
  public function update($table, array $data, $where) {
    $mysqli = $this->mysqli;

    $sql = array();
    foreach ($data as $key => $value) {
      $sql[] = '`' . $key . '` = ' . $this->quote($value);
    }

    $sql = 'UPDATE `' . $table . '` SET ' . implode(', ', $sql) . ' WHERE ' . $where;
    if (! $mysqli->query($sql)) {
      throw new elphin_mysqli_exception('Update failed: ' . $mysqli->error, $mysqli->errno);
    }
  }

  /**
   * Executes an SQL DELETE statement.
   * @param  string  $table  the database table
   * @param  string  $where  SQL code for the WHERE clause of the DELETE statement
   */
  public function delete($table, $where) {
    $mysqli = $this->mysqli;

    $sql = 'DELETE FROM `' . $table . '` WHERE ' . $where;
    if (! $mysqli->query($sql)) {
      throw new elphin_mysqli_exception('Delete failed: ' . $mysqli->error, $mysqli->errno);
    }
  }

  /**
   * Returns all rows returned by an SQL query.
   * The result is an array of associative arrays, each representing one row.
   * @param  string  $sql  the SQL query
   * @return  array  an array of associative arrays
   */
  public function select_all_rows($sql) {
    $mysqli = $this->mysqli;

    $result = $mysqli->query($sql);
    if (! $result) {
      throw new elphin_mysqli_exception('Database query failed: ' . $mysqli->error, $mysqli->errno);
    }
    $rows = array();
    while ($row = $result->fetch_assoc()) {
      $rows[] = $row;
    }
    $result->close();
    return $rows;
  }

  /**
   * Returns a single row returned by an SQL query.
   * The result is an associative array representing the single row returned by
   * the query.  Note that this method will throw an exception if more than 1
   * row is returned, or if 0 rows are returned.
   * @param  string  $sql  the SQL query
   * @return  array  an associative array
   * @throws  elphin_mysqli_exception  if the number of rows is not exactly 1
   */
  public function select_single_row($sql) {
    $mysqli = $this->mysqli;

    $result = $mysqli->query($sql);
    if (! $result) {
      throw new elphin_mysqli_exception('Database query failed: ' . $mysqli->error, $mysqli->errno);
    }
    $num_rows = 0;
    $row = NULL;
    while ($r = $result->fetch_assoc()) {
      $row = $r;
      ++$num_rows;
    }
    $result->close();
    if ($num_rows === 0) {
      throw new elphin_record_not_found_exception('Record not found in database');
    }
    if ($num_rows > 1) {
      throw new elphin_multiple_records_found_exception('Multiple records found in database');
    }
    return $row;
  }

  /**
   * Returns all values returned by an SQL query.
   * The SQL query must select only a single value - if it selects more than one
   * value, only the first value in each row is returned.
   * The result is an array of values.
   * @param  string  $sql  the SQL query
   * @return  array  an array of values
   */
  public function select_single_column($sql) {
    $mysqli = $this->mysqli;

    $result = $mysqli->query($sql);
    if (! $result) {
      throw new elphin_mysqli_exception('Database query failed: ' . $mysqli->error, $mysqli->errno);
    }
    $rows = array();
    while ($row = $result->fetch_row()) {
      $rows[] = $row[0];
    }
    $result->close();
    return $rows;
  }

  /**
   * Returns a single value returned by an SQL query.
   * The result is a single value returned by the query. Note that this method
   * will throw an exception if more than 1  row is returned, or if 0 rows are
   * returned.
   * @param  string  $sql  the SQL query
   * @return  string|null  a value
   * @throws  elphin_mysqli_exception  if the number of rows is not exactly 1
   */
  public function select_single_value($sql) {
    $mysqli = $this->mysqli;

    $result = $mysqli->query($sql);
    if (! $result) {
      throw new elphin_mysqli_exception('Database query failed: ' . $mysqli->error, $mysqli->errno);
    }
    $num_rows = 0;
    $row = NULL;
    while ($r = $result->fetch_row()) {
      $row = $r;
      ++$num_rows;
    }
    $result->close();
    if ($num_rows === 0) {
      throw new elphin_record_not_found_exception('Record not found in database');
    }
    if ($num_rows > 1) {
      throw new elphin_multiple_records_found_exception('Multiple records found in database');
    }
    return $row[0];
  }

  /**
   * Returns all rows returned by an SQL query as an associative array.
   * The result is an associative array of associative arrays, each representing
   * one row.
   * @param  string  $sql  the SQL query
   * @param  string  $hash_key  the database column to use as the hash key
   * @return  array  an associative array
   */
  public function select_all_rows_hash($sql, $hash_key) {
    $mysqli = $this->mysqli;

    $result = $mysqli->query($sql);
    if (! $result) {
      throw new elphin_mysqli_exception('Database query failed: ' . $mysqli->error, $mysqli->errno);
    }
    $rows = array();
    while ($row = $result->fetch_assoc()) {
      $rows[$row[$hash_key]] = $row;
    }
    $result->close();
    return $rows;
  }

  /**
   * Returns all values returned by an SQL query in an associative array.
   * The SQL query must select exactly two columns - the first column is used as
   * the hash key and the second column is used as the hash value.  (If it
   * selects more than two columns, the remaining columns will be ignored.)
   * The result is an associative array.
   * @param  string  $sql  the SQL query
   * @return  array  an associative array mapping one column to another
   */
  public function select_single_column_hash($sql) {
    $mysqli = $this->mysqli;

    $result = $mysqli->query($sql);
    if (! $result) {
      throw new elphin_mysqli_exception('Database query failed: ' . $mysqli->error, $mysqli->errno);
    }
    $rows = array();
    while ($row = $result->fetch_row()) {
      $rows[$row[0]] = $row[1];
    }
    $result->close();
    return $rows;
  }

  /**
   * Quotes a value.
   * The value is escaped and surrounded with (single) quotes if necessary.
   * @param  mixed  $value  the value to quote
   * @return  string  the escaped, quoted value
   */
  public function quote($value) {
    $mysqli = $this->mysqli;

    if (is_object($value) && $value instanceof elphin_raw_sql) {
      return $value->sql;
    }
    elseif ($value === NULL) {
      return 'NULL';
    }
    elseif ($value === TRUE) {
      return '1';
    }
    elseif ($value === FALSE) {
      return '0';
    }
    else {
      $escaped = $mysqli->real_escape_string($value);
      if (! is_string($escaped)) {
        throw new elphin_mysqli_exception('String escape failed');
      }
      return '\'' . $escaped . '\'';
    }
  }
}
