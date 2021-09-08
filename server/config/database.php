<?php
// Database
class Database {
	// Connect
	public $connect;

	// Query data
	private $table = "";
	private $table_state = false;
	private $join = "";
	private $join_state = false;
	private $where = "";
	private $where_state = false;
	private $select = "*";
	private $select_state = false;
	private $orderby = "";
	private $orderby_state = false;

	// Connection to base
	function __construct($dbhost, $dbuser, $dbpass, $dbname) {
		$this->connect = null;
		$this->connect = new mysqli($dbhost, $dbuser, $dbpass, $dbname);
		$this->connect->set_charset("utf8");
		if($this->connect->connect_errno)
			die("Connection error: ". $this->connect->connect_errno);
	}

	// Desctructor
	function __destruct() {
		return;
	}

	// Executing sql query
	public function query($sql) {
		$result = $this->connect->query($sql);
		return $result;
	}

	// Fluid interface
	// Table
	public function table($table) {
		$this->table = $table;
		$this->table_state = true;
		$this->where_state = false;
		$this->select_state = false;
		return $this;
	}

	// Join
	public function join($table, $field="", $condition="", $value="") {
		$this->join_state = true; $on = ($field == "") ? "" : "ON";
		$this->join = sprintf("JOIN `%s` %s %s %s %s", $table, $on, $field, $condition, $value);
		return $this;
	}

	// Selecting a table by attribute
	public function where($field, $condition, $value) {
		$this->where_state = true;
		$this->where = sprintf("WHERE `%s` %s '%s'", $field, $condition, $value);
		return $this;
	}

	// Additional condition
	public function andWhere($field, $condition, $value) {
		if ($this->where_state)
			$this->where .= sprintf(" AND `%s` %s '%s'", $field, $condition, $value);
		return $this;
	}

	// Additional condition
	public function orWhere($field, $condition, $value) {
		if ($this->where_state)
			$this->where .= sprintf(" OR `%s` %s '%s'", $field, $condition, $value);
		return $this;
	}

	// Selecting the fields you want
	public function select($fields) {
		$this->select_state = true;
		$fields = explode(",", $fields);
		$string = "";
		$counter = 0;
		foreach($fields as $val) {
			if($counter == count($fields) - 1)
				$string .= sprintf("`%s`", trim($val));
			else $string .= sprintf("`%s`, ", trim($val));
			$counter++;
		}
		$this->select = $string;
		return $this;
	}

	// Order by
	public function orderBy($value, $type) {
		$this->orderby_state = true;
		$this->orderby = sprintf("ORDER BY `%s` %s", $value, $type);
		return $this;
	}

	// Get data
	public function get() {
		$table = ($this->table_state) ? $this->table : ""; $this->table_state = false;
		$join = ($this->join_state) ? $this->join : ""; $this->join_state = false;
		$where = ($this->where_state) ? $this->where : ""; $this->where_state = false;
		$select = ($this->select_state) ? $this->select : "*"; $this->select_state = false;
		$orderby = ($this->orderby_state) ? $this->orderby : ""; $this->orderby_state = false;
		$query = sprintf("SELECT %s FROM `%s` %s %s %s", $select, $table, $join, $where, $orderby);
		$result = $this->query($query);
		$array = [];
		while($row = $result->fetch_assoc())
			array_push($array, $row);
		return $array;
	}

	// Get first data
	public function first() {
		$table = ($this->table_state) ? $this->table : ""; $this->table_state = false;
		$join = ($this->join_state) ? $this->join : ""; $this->join_state = false;
		$where = ($this->where_state) ? $this->where : ""; $this->where_state = false;
		$select = ($this->select_state) ? $this->select : "*"; $this->select_state = false;
		$orderby = ($this->orderby_state) ? $this->orderby : ""; $this->orderby_state = false;
		$query = sprintf("SELECT %s FROM `%s` %s %s %s", $select, $table, $join, $where, $orderby);
		return $this->query($query)->fetch_assoc();
	}

	// Insert data
	public function insert($array) {
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
		$query = sprintf("INSERT INTO `%s`(%s) VALUES (%s)", $this->table, $keys, $values);
		if(!$this->query($query)) return false;
		else return true;
	}

	// Adding data with return ID
	public function insert_id($array) {
		if ($this->insert($array)) return DB::$connect->insert_id;
		else return false;
	}

	// Update data
	public function update($array) {
		$string = "";
		$counter = 0;
		foreach($array as $key => $val) {
			if($counter == count($array) - 1)
				$string .= sprintf("`%s`='%s'", $key, $val);
			else $string .= sprintf("`%s`='%s', ", $key, $val);
			$counter++;
		}
		$query = sprintf("UPDATE `%s` SET %s %s", $this->table, $string, $this->where);
		if(!$this->query($query)) return false;
		else return true;
	}

	// Delete data
	public function delete() {
		$query = sprintf("DELETE FROM `%s` %s", $this->table, $this->where);
		if(!$this->query($query)) return false;
		else return true;
	}
}
?>