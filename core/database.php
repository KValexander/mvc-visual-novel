<?php
// Database
class Database {
	// Connect
	private $connect;

	// Query data
	private $access = false;
	private $result = "";
	private $result_state = false;
	private $table = "";
	private $table_state = false;
	private $where = "";
	private $where_state = false;
	private $select = "*";
	private $select_state = false;
	private $orderby = "";
	private $orderby_state = false;

	// Connection to base
	function __construct($dbhost=NULL, $dbuser=NULL, $dbpass=NULL, $dbname=NULL) {
		if($dbhost == NULL || $dbuser == NULL || $dbname == NULL) return;
		$this->connect = null;
		$this->connect = new mysqli($dbhost, $dbuser, $dbpass, $dbname);
		$this->connect->set_charset("utf8");
		if($this->connect->connect_errno)
			die("Connection error: ". $this->connect->connect_error);

		$this->access = true;
	}

	// Transaction
	public function transaction($sql_array) {
		$this->check_connect();
		try {
			$this->connect->beginTransaction();
			foreach ($sql_array as $key => $sql)
				$this->connect->query($sql);
			$this->connect->commit();
		} catch (Exception $e) {
			$this->connect->rollback();
			throw $e;
		}
	}

	// Executing sql query
	public function query($sql) {
		$this->check_connect();
		$result = $this->connect->query($sql);
		return $result;
	}

	// Fluid interface
	// Executing a request with further actions
	public function result($sql) {
		$this->check_connect();
		$this->result = $this->connect->query($sql);
		$this->result_state = true;
		return $this;
	}
	
	// Table
	public function table($table) {
		$this->check_connect();
		$this->table = "`$table`";
		$this->table_state = true;
		$this->where_state = false;
		$this->select_state = false;
		return $this;
	}

	// Selecting a table by attribute
	public function where($field, $condition, $value="") {
		$this->check_connect();
		$where = ($value == "") ? sprintf("= '%s'", $condition) : sprintf("%s '%s'", $condition, $value);
		$this->where = sprintf("WHERE `%s` %s", $field, $where);
		$this->where_state = true;
		return $this;
	}

	// Additional condition
	public function andWhere($field, $condition, $value="") {
		$this->check_connect();
		if ($this->where_state) {
			$where = ($value == "") ? sprintf("= '%s'", $condition) : sprintf("%s '%s'", $condition, $value);
			$this->where .= sprintf(" AND `%s` %s", $field, $where);
		} return $this;
	}

	// Additional condition
	public function orWhere($field, $condition, $value) {
		$this->check_connect();
		if ($this->where_state) {
			$where = ($value == "") ? sprintf("= '%s'", $condition) : sprintf("%s '%s'", $condition, $value);
			$this->where .= sprintf(" OR `%s` %s", $field, $where);
		} return $this;
	}

	// Selecting the fields you want
	public function select($fields) {
		$this->check_connect();
		$string = ""; $counter = 0;
		foreach($fields as $val) {
			if($counter == count($fields) - 1)
				$string .= sprintf("`%s`", trim($val));
			else $string .= sprintf("`%s`, ", trim($val));
			$counter++;
		}
		$this->select = $string;
		$this->select_state = true;
		return $this;
	}

	// Order by
	public function orderBy($value, $type) {
		$this->check_connect();
		$this->orderby = sprintf("ORDER BY `%s` %s", $value, $type);
		$this->orderby_state = true;
		return $this;
	}

	// Get data
	public function get() {
		$this->check_connect();
		if (!$this->result_state) {
			$table = ($this->table_state) ? $this->table : ""; $this->table_state = false;
			$where = ($this->where_state) ? $this->where : ""; $this->where_state = false;
			$select = ($this->select_state) ? $this->select : "*"; $this->select_state = false;
			$orderby = ($this->orderby_state) ? $this->orderby : ""; $this->orderby_state = false;
			$query = sprintf("SELECT %s FROM %s %s %s", $select, $table, $where, $orderby);
			$this->result = $this->query($query);
		}
		$array = [];
		while($row = $this->result->fetch_assoc())
			array_push($array, $row);
		return $array;
	}

	// Get first data
	public function first() {
		$this->check_connect();
		if (!$this->result_state) {
			$table = ($this->table_state) ? $this->table : ""; $this->table_state = false;
			$where = ($this->where_state) ? $this->where : ""; $this->where_state = false;
			$select = ($this->select_state) ? $this->select : "*"; $this->select_state = false;
			$orderby = ($this->orderby_state) ? $this->orderby : ""; $this->orderby_state = false;
			$query = sprintf("SELECT %s FROM %s %s %s", $select, $table, $where, $orderby);
			$this->result = $this->query($query);
		}
		return $this->result->fetch_assoc();
	}

	// Insert data
	public function insert($array) {
		$this->check_connect();
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
		$query = sprintf("INSERT INTO %s(%s) VALUES (%s)", $this->table, $keys, $values);
		if(!$this->query($query)) return false;
		else return true;
	}

	// Adding data with return ID
	public function insert_id($array) {
		$this->check_connect();
		if ($this->insert($array)) return $this->connect->insert_id;
		else return false;
	}

	// Update data
	public function update($array) {
		$this->check_connect();
		$string = "";
		$counter = 0;
		foreach($array as $key => $val) {
			if($counter == count($array) - 1)
				$string .= sprintf("`%s`='%s'", $key, $val);
			else $string .= sprintf("`%s`='%s', ", $key, $val);
			$counter++;
		}
		$query = sprintf("UPDATE %s SET %s %s", $this->table, $string, $this->where);
		if(!$this->query($query)) return false;
		else return true;
	}

	// Delete data
	public function delete() {
		$this->check_connect();
		$query = sprintf("DELETE FROM %s %s", $this->table, $this->where);
		if(!$this->query($query)) return false;
		else return true;
	}

	// Error output
	public function error() {
		$this->check_connect();
		return $this->connect->error;
	}

	// Check connect
	private function check_connect() {
		if(!$this->access)
			die("Connection error: There is no data to connect to the database");
	}
}
?>