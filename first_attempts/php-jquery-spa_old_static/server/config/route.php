<?php
// Simple router
class Route {
	// Variables
	private static $pathToControllers = "controllers/";
	private static $pathToMiddleware = "middleware/";
	private static $current_type = NULL;
	private static $current_route = NULL;
	private static $current_middleware = NULL;
	private static $type = NULL;
	private static $route = NULL;
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
	public static function check($type, $route) {
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

		// Writing data
		self::$type = $type;
		self::$route = $route;

		// Check
		if (array_key_exists($route, self::$routes[$type])) return true;
		else return false;
	}

	// Call method
	public static function call() {
		// Get route params
		$route = self::$routes[self::$type][self::$route];
		$next = true;

		// Connecting middleware in case of presence on the route
		if ($route["middleware"] != NULL) {
			$next = false;
			// If passed function
			if (is_callable($route["middleware"])) $next = $route["middleware"]();
			else {
				// Middleware checking and connection
				if (!file_exists(self::$pathToMiddleware . $route["middleware"] .".php"))
					exit('File '.$route["middleware"].'.php does not exist');
				include self::$pathToMiddleware . $route["middleware"] .".php";
				if(!class_exists($route["middleware"], false))
					exit('Class '.$route["middleware"].' not found');

				// Creating an instance
				$middleware = new $route["middleware"];
				$handle = "handle";

				// Calling middleware
				$next = $middleware->$handle();
			}
		}

		// Continuation check
		if(!$next) return;

		// If passed function
		if (is_callable($route["param"])) return $route["param"]();

		// Retrieving parameters
		$params = explode("/", $route["param"]);

		// Getting and checking a class and controller
		include self::$pathToControllers . $params[0] .".php";
		if(!class_exists($params[0], false))
			exit("Class $params[0] not found");

		$controller = new $params[0];

		// Getting and checking a method
		if(!method_exists($controller, $params[1]))
			exit("Method $params[1] not found");

		$method = (string)$params[1];

		return $controller->$method();
	}

	// Method to replace only the first match, the only copy-paste in the whole code
	// Source: https://web.izjum.com/php-str_replace_once
	private static function str_replace_once($search, $replace, $text) { 
		$pos = @strpos($text, $search); 
		return ($pos!==false) ? substr_replace($text, $replace, $pos, strlen($search)) : $text; 
	}

}
?>