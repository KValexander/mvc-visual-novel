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
	private static $table;
	private static $where;
	private static $query;

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
		return new self;
	}

	// Selecting a table by attribute
	public static function where($field, $value) {
		self::$where = sprintf("WHERE `%s`='%s'", $field, $value);
		return new self;
	}

	// Updating data
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
		self::query($query);
		
		if(!self::query($query)) return false;
		else return true;
	}
}
?>