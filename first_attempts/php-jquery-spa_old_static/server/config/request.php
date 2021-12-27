<?php
// Working with the received data
class Request {
	// Array with values of route variables
	private static $route = array();

	// Returns all data
	public static function all() {
		if($_SERVER["CONTENT_TYPE"] ==  'application/json') {
			$data = file_get_contents('php://input');
			$array = json_decode($data, true);
		} else $array = array_merge($_REQUEST, $_FILES);
		return $array;
	}
	// Returns data by key
	public static function input($key) {
		if($_SERVER["CONTENT_TYPE"] ==  'application/json') {
			$data = file_get_contents('php://input');
			$array = json_decode($data, true);
		} else $array = array_merge($_REQUEST, $_FILES);
		return $array[$key];
	}

	// Returns data by key
	public static function route($key) {
		return self::$route[$key];
	}

	// Add route variable value
	public static function add_route($key, $value) {
		self::$route[$key] = $value;
	}
}
?>