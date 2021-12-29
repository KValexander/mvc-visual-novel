<?php
class Router {
	// Array of routes
	private static $routes = array();
	// Array of middleware
	private static $middlewares = array();
	// Current route
	private static $current_route = NULL;
	// Middleware groups
	private static $groups = array(); 

	// Add route
	public static function add($type, $path, $value) {
		$path = slash_check($path);
		self::$routes[strtoupper($type)][$path] = $value;
		self::$current_route = $path;
		return new self;
	}

	// Add GET route
	public static function get($path, $value) {
		$path = slash_check($path);

		// Add route
		self::$routes["GET"][$path] = $value;
		self::$current_route = $path;
		
		// Add middleware
		if(count(self::$groups) != 0)
			self::add_middleware($path);

		return new self;
	}

	// Add POST route
	public static function post($path, $values) {
		$path = slash_check($path);
		
		// Add route
		self::$routes["POST"][$path] = $values;
		self::$current_route = $path;
		
		// Add middleware
		if(count(self::$groups) != 0)
			self::add_middleware($path);

		return new self;
	}

	// Add middleware group
	public static function group($middleware, $value) {
		self::$groups[] = $middleware;
		if (is_callable($value)) {
			$value();
			array_pop(self::$groups);
		};
	}

	// Add middleware
	public static function middleware($middleware, $value=NULL) {
		if(self::$current_route != NULL)
			self::$middlewares[$middleware][] = self::$current_route;
		self::$current_route = NULL;
	}

	// Add route in middleware
	private static function add_middleware($path) {
		foreach(self::$groups as $val)
			self::$middlewares[$val][] = self::$current_route;
	}

	// Search route
	public static function search($path, $type) {
		if(count(self::$routes) == 0) return false;

		// Checking for route availability
		if($result = self::route_processing($path, $type)) {
			// Check middleware
			if(!self::check_middleware($result[0])) return false;

			// Function call
			if (is_callable($result[1])) $result[1]();
			// Instantiating the controller
			else self::controller_processing(explode("@", $result[1]));

			return true;
		} else return false;
	}

	// Route processing
	private static function route_processing($path, $type) {
		// Simple coincidence
		if (array_key_exists($path, self::$routes[$type]))
			return [$path, self::$routes[$type][$path]];

		// Checking for the existence of variables in a route
		// Part 1
		$val_path = explode("/", $path);
		foreach(self::$routes[$type] as $key => $val) {
			$count_var = preg_match_all("#{.*?}#", $key);
			$val_key = explode("/", $key);
			if ($count_var > 0 && count($val_path) == count($val_key)) {

				// Determining routes matching
				$pattern = "#". preg_replace("/\{.*?\}/", "(.*?)", $key) ."#";
				if (preg_match($pattern, $path)) {

					// Retrieving values and keys of route variables
					for($i = 0; $i < count($val_path); $i++)
						if(preg_match("/\{.*?\}/", $val_key[$i]))
							Request::add_route(preg_replace("#{|}#", "", $val_key[$i]), $val_path[$i]);

					// Returning result
					return [$key, $val];
				}
			}
		}
		return false;
	}

	// Check middleware
	private static function check_middleware($path) {
		// Checking the route for the presence of middleware
		foreach(self::$middlewares as $k => $v)
			if(in_array($path, self::$middlewares[$k]))
				if(!self::middleware_processing($k))
					return false;
		return true;
	}

	// Middleware processing
	private static function middleware_processing($middleware) {
		// Checking for file existence
		if(!file_exists("middlewares/". $middleware .".php"))
			return print("File ". $middleware .".php doesn't exists");

		// Class connection
		include_once("middlewares/". $middleware .".php");
		// Check the existence of a class
		if(!class_exists($middleware))
			return print("Class ". $middleware ." doesn't exists");

		// Instantiating a class
		$middleware = new $middleware();

		// Checking for the existence of a method inside a class
		if(!method_exists($middleware, "handle"))
			return print("Method handle in class ". $middleware ." doesn't exists");

		return $middleware->{"handle"}();
	}

	// Controller processing
	public function controller_processing($params) {
		// Checking for file existence
		if(!file_exists("controllers/". $params[0] .".php"))
			return print("File ". $params[0] .".php doesn't exists");

		// Class connection
		include_once("controllers/". $params[0] .".php");
		// Check the existence of a class
		if(!class_exists($params[0]))
			return print("Class ". $params[0] ." doesn't exists");

		// Instantiating a class
		$controller = new $params[0]();

		// Checking for the existence of a method inside a class
		if(!method_exists($controller, $params[1]))
			return print("Method ". $params[1] ." in class ". $params[0] ." doesn't exists");

		$method = $params[1];

		// Method call
		$controller->$method();
	}
}
?>