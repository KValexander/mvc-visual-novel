<?php
// Including files
include "config/rand.php";

class DB {
	private static $dbhost = "localhost";
	private static $dbuser = "root";
	private static $dbpass = "root";
	private static $dbname = "novel_re";
	public static $connect;
	
	public static function connect() {
		self::$connect = null;
		self::$connect = new mysqli(self::$dbhost, self::$dbuser, self::$dbpass, self::$dbname);
		self::$connect->set_charset("utf8");
		if(self::$connect->connect_errno)
			die("Connection error: ". self::$connect->connect_errno);
	}

	public static function query($sql) {
		$result = self::$connect->query($sql);
		return $result;
	}
}
?>