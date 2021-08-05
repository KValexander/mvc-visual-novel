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
			"name" => NULL
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
		];
		return new self;
	}
	// Checking for the existence of a route
	public static function search($type, $route) {
		// Processing for a route with a variable
		foreach(self::$routes[$type] as $init => $not_needed) {
			if (preg_match("/\{.*?\}/", $init)) {
				// Checking
				$ex = preg_split("/\{.*?\}/", $init);
				if(strpos($route, $ex[0]) === false) continue;
				// Retrieving Keys
				preg_match_all("/\{.*?\}/", $init, $key, PREG_PATTERN_ORDER);
				// Retrieving values
				$value = $route; $array = array();
				for($i = 0; $i < count($key[0]); $i++) {
					$value = self::str_replace_once($ex[$i], "", $value);
					$intermediate = (preg_match("/\//", $value)) ? explode("/", $value)[0] :  $value;
					$value = self::str_replace_once($intermediate, "", $value);
					array_push($array, $intermediate);
				}
				$rte = $init;
				// Working with the received data
				for($i = 0; $i < count($key[0]); $i++) {
					$key_k = preg_replace("/(\{)|(\})/", "", $key[0][$i]);
					Request::add_route($key_k, $array[$i]);
					$rte = str_replace($key[0][$i], $array[$i], $rte);
				}
				unset(self::$routes[$type][$init]);
				self::$routes[$type][$rte] = $not_needed;
			}
		}

		if (array_key_exists($route, self::$routes[$type])) return true;
		else return false;
	}
	// Sending data for the desired route
	public static function give($type, $route) {
		return self::$routes[$type][$route];
	}

	// Method to replace only the first match, the only copy-paste in the whole code
	// Source: https://web.izjum.com/php-str_replace_once
	private static function str_replace_once($search, $replace, $text) { 
		$pos = @strpos($text, $search); 
		return ($pos!==false) ? substr_replace($text, $replace, $pos, strlen($search)) : $text; 
	}

}
?>