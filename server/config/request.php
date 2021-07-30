<?php
// Working with the received data
class Request {
	// Returns all data
	public static function all() {
		$array = array_merge($_REQUEST, $_FILES);
		return $array;
	}
	// Returns data by key
	public static function input($key) {
		$array = array_merge($_REQUEST, $_FILES);
		return $array[$key];
	}
}
?>