<?php
// Including files
include "config/rand.php";

class DB {
	// Data for connecting to the base
	private static $dbhost = "localhost";
	private static $dbuser = "root";
	private static $dbpass = "root";
	private static $dbname = "novel_re";
	public static $connect;

	// Query data
	private static $table = "";
	private static $table_state = false;
	private static $where = "";
	private static $where_state = false;
	private static $select = "*";
	private static $select_state = false;

	// Connection to base
	public static function connect() {
		self::$connect = null;
		self::$connect = new mysqli(self::$dbhost, self::$dbuser, self::$dbpass, self::$dbname);
		self::$connect->set_charset("utf8");
		if(self::$connect->connect_errno)
			die("Connection error: ". self::$connect->connect_errno);
	}

	// Executing sql query
	public static function query($sql) {
		$result = self::$connect->query($sql);
		return $result;
	}

	// Fluid interface
	// Table
	public static function table($table) {
		self::$table = $table;
		self::$table_state = true;
		self::$where_state = false;
		self::$select_state = false;
		return new self;
	}

	// Selecting a table by attribute
	public static function where($field, $value) {
		self::$where_state = true;
		self::$where = sprintf("WHERE `%s`='%s'", $field, $value);
		return new self;
	}

	// Selecting the fields you want
	public static function select($fields) {
		self::$select_state = true;
		$fields = explode(",", $fields);
		$string = "";
		$counter = 0;
		foreach($fields as $val) {
			if($counter == count($fields) - 1)
				$string .= sprintf("`%s`", trim($val));
			else $string .= sprintf("`%s`, ", trim($val));
			$counter++;
		}
		self::$select = $string;
		return new self;
	}

	// Get data
	public function get() {
		if(self::$table_state) $table = self::$table;
		else $table = "";
		if(self::$where_state) $where = self::$where;
		else $where = "";
		if(self::$select_state) $select = self::$select;
		else $select = "*";
		$array = [];
		$query = sprintf("SELECT %s FROM %s %s", $select, $table, $where);
		$result = self::query($query);
		while($row = $result->fetch_assoc())
			array_push($array, $row);
		return $array;
	}

	// Get first data
	public static function first() {
		if(self::$table_state) $table = self::$table;
		else $table = "";
		if(self::$where_state) $where = self::$where;
		else $where = "";
		if(self::$select_state) $select = self::$select;
		else $select = "*";
		$query = sprintf("SELECT %s FROM %s %s", $select, $table, $where);
		return self::query($query)->fetch_assoc();
	}

	// Insert data
	public static function insert($array) {
		$keys = "";
		$values = "";
		$counter = 0;
		foreach($array as $key => $val) {
			if($counter == count($array) - 1) {
				$keys .= sprintf("`%s`", $key);
				$values .= sprintf("'%s'", $val);
			} else {
				$keys .= sprintf("`%s`, ", $key);
				$values .= sprintf("'%s', ", $val);
			}
			$counter++;
		}
		$query = sprintf("INSERT INTO `%s`(%s) VALUES (%s)", self::$table, $keys, $values);
		if(!self::query($query)) return false;
		else return true;
	}

	// Adding data with return ID
	public static function insert_id($array) {
		if (self::insert($array)) return DB::$connect->insert_id;
		else return false;
	}

	// Update data
	public static function update($array) {
		$string = "";
		$counter = 0;
		foreach($array as $key => $val) {
			if($counter == count($array) - 1)
				$string .= sprintf("`%s`='%s'", $key, $val);
			else $string .= sprintf("`%s`='%s', ", $key, $val);
			$counter++;
		}
		$query = sprintf("UPDATE `%s` SET %s %s", self::$table, $string, self::$where);
		if(!self::query($query)) return false;
		else return true;
	}
}
?>