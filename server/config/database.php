<?php
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
	private static $orderby = "";
	private static $orderby_state = false;

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
	public static function where($field, $condition, $value) {
		self::$where_state = true;
		self::$where = sprintf("WHERE `%s`%s'%s'", $field, $condition, $value);
		return new self;
	}

	// Additional condition
	public static function andWhere($field, $condition, $value) {
		if (self::$where_state)
			self::$where .= sprintf(" AND `%s`%s'%s'", $field, $condition, $value);
		return new self;
	}

	// Additional condition
	public static function orWhere($filed, $condition, $value) {
		if (self::$where_state)
			self::$where .= sprintf(" OR `%s`%s'%s'", $field, $condition, $value);
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

	// Order by
	public static function orderBy($value, $type) {
		self::$orderby_state = true;
		self::$orderby = sprintf("ORDER BY `%s` %s", $value, $type);
		return new self;
	}

	// Get data
	public function get() {
		$table = (self::$table_state) ? self::$table : "";
		$where = (self::$where_state) ? self::$where : "";
		$select = (self::$select_state) ? self::$select : "*";
		$orderby = (self::$orderby_state) ? self::$orderby : "";
		$query = sprintf("SELECT %s FROM %s %s %s", $select, $table, $where, $orderby);
		$result = self::query($query);
		$array = [];
		while($row = $result->fetch_assoc())
			array_push($array, $row);
		return $array;
	}

	// Get first data
	public static function first() {
		$table = (self::$table_state) ? self::$table : "";
		$where = (self::$where_state) ? self::$where : "";
		$select = (self::$select_state) ? self::$select : "*";
		$orderby = (self::$orderby_state) ? self::$orderby : "";
		$query = sprintf("SELECT %s FROM %s %s %s", $select, $table, $where, $orderby);
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

	// Delete data
	public static function delete() {
		$query = sprintf("DELETE FROM `%s` %s", self::$table, self::$where);
		if(!self::query($query)) return false;
		else return true;
	}
}
?>