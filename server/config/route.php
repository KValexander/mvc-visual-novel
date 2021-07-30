<?php
// Simple router
class Route {
	// Array of routes and groups routes
	private static $routes = array();
	private static $groups = array();

	// Adding middleware
	public static function middleware($filename, $callback) {
		self::$groups[$filename] = array();
		$routes = $callback();
		// var_dump(self::$routes);
		// var_dump(is_callable(self::$routes["GET"]["/api/test/"]));
	}

	// Adding a route with a data type GET
	public static function get($route, $param) {
		self::$routes["GET"][$route] = $param;
	}
	// Adding a route with a data type POST
	public static function post($route, $param) {
		self::$routes["POST"][$route] = $param;
	}
	// Checking for the existence of a route
	public static function search($type, $route) {
		if (array_key_exists($route, self::$routes[$type])) return true;
		else return false;
	}
	// Sending data for the desired route
	public static function give($type, $route) {
		return self::$routes[$type][$route];
	}

}
?>