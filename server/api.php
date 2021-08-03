<?php
	// Session start
	session_start();

	// Including files
	$files = scandir("config/"); unset($files[0], $files[1]);
	foreach($files as $val) include "config/". $val;
	include "routes.php";
	/* 	Classes: DB, Auth, Rand, Request, Route
		Functions: response(), validator() */

	// Database connection
	DB::connect();

	// Headers
	header("Access-Control-Allow-Origin: *");
	header("Content-Type:application/json;charset=UTF-8");

	// Checking for Route Availability
	if (Route::search($_SERVER["REQUEST_METHOD"], $_SERVER["REDIRECT_URL"])) {
		// Getting route value
		$route = Route::give($_SERVER["REQUEST_METHOD"], $_SERVER["REDIRECT_URL"]);
		$next = true;

		// Connecting middleware in case of presence on the route
		if ($route["middleware"] != NULL) {
			$next = false;
			// If passed function
			if (is_callable($route["middleware"])) $next = $route["middleware"]();
			else {
				// Middleware checking and connection
				if (!file_exists("middleware/". $route["middleware"] .".php"))
					exit('File '.$route["middleware"].'.php does not exist');
				include "middleware/". $route["middleware"] .".php";
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
		include "controllers/". $params[0] .".php";
		if(!class_exists($params[0], false))
			exit("Class $params[0] not found");

		$controller = new $params[0];

		// Getting and checking a method
		if(!method_exists($controller, $params[1]))
			exit("Method $params[1] not found");

		$method = (string)$params[1];

		return $controller->$method();
	} else exit("Route not found");

?>