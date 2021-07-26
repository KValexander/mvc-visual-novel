<?php
class Database {
	private $dbhost = "localhost";
	private $dbuser = "root";
	private $dbpass = "root";
	private $dbname = "novel_re";
	private $connect;
	
	public function connect() {
		$this->connect = null;
		$this->connect = new mysqli($this->dbhost, $this->dbuser, $this->dbpass, $this->dbname);
		$this->connect->set_charset("utf8");

		if($this->connect->connect_errno)
			die("Connection error: ". $this->connect->connect_errno);

		return $this->connect;
	}
}
?>