<?php
// Working with the received data
class Request {
	// Array with values of route variables
	private $route = array();

	// Returns all data
	public function all() {
		if($_SERVER["CONTENT_TYPE"] ==  'application/json') {
			$data = file_get_contents('php://input');
			$array = json_decode($data, true);
		} else $array = array_merge($_REQUEST, $_FILES);
		return $array;
	}
	// Returns data by key
	public function input($key) {
		if($_SERVER["CONTENT_TYPE"] ==  'application/json') {
			$data = file_get_contents('php://input');
			$array = json_decode($data, true);
		} else $array = array_merge($_REQUEST, $_FILES);
		return $array[$key];
	}

	// Returns data by key
	public function route($key) {
		return $this->route[$key];
	}

	// Add route variable value
	public function add_route($key, $value) {
		$this->route[$key] = $value;
	}
}
?>