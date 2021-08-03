<?php
// Simple router
class Route {
	// Variables of current values and routes
	private static $current_type = NULL;
	private static $current_route = NULL;
	private static $current_middleware = NULL;
	private static $routes = array();

	// Adding middleware
	public static function middleware($filename, $callback=NULL) {
		// Attaching middleware to a single route
		if ($callback == NULL) self::$routes[self::$current_type][self::$current_route]["middleware"] = $filename;
		else { // Attaching middleware to a route group
			self::$current_middleware = $filename;
			$callback();
			self::$current_middleware = NULL;
		}
		return new self;
	}

	public static function name($name) {
		// Attaching a name to a route
		self::$routes[self::$current_type][self::$current_route]["name"] = $name;
		return new self;
	}

	// Adding a route with a data type GET
	public static function get($route, $param) {
		// Set current values
		self::$current_type = "GET";
		self::$current_route = $route;
		// Adding a new route
		self::$routes["GET"][$route] = [
			"param" => $param,
			"middleware" => self::$current_middleware,
			"name" => NULL,
		];
		return new self;
	}

	// Adding a route with a data type POST
	public static function post($route, $param) {
		// Set current values
		self::$current_type = "POST";
		self::$current_route = $route;
		// Adding a new route
		self::$routes["POST"][$route] = [
			"param" => $param,
			"middleware" => self::$current_middleware,
			"name" => NULL,
		];;
		return new self;
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