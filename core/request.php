<?php
// Working with the received data
class Request {
	private static $routes = array();

	// Data retrieval method
	private function get_data() {
		if($_SERVER["CONTENT_TYPE"] ==  'application/json') {
			$data = file_get_contents('php://input');
			$array = json_decode($data, true);
		} else $array = array_merge($_REQUEST, $_FILES);
		return $array;
	}

	// Add route
	public static function add_route($key, $value) {
		self::$routes[$key] = $value;
	}

	// Returns all data
	public function all() {
		return $this->get_data();
	}
	
	// Returns data by key
	public function input($key) {
		return $this->get_data()[$key];
	}

	// public function 
	// Returns data by key
	public function route($key) {
		return self::$routes[$key];
	}
}
?>