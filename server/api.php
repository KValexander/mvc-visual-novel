<?php
	// Session start
	session_start();

	// Including files
	include "config/database.php";
	include "config/response.php";
	include "config/request.php";
	include "routes.php";

	// Database connection
	DB::connect();

	// Headers
	header("Access-Control-Allow-Origin: *");
	header("Content-Type:application/json;charset=UTF-8");

	// Checking for Route Availability
	if (Route::search($_SERVER["REQUEST_METHOD"], $_SERVER["REDIRECT_URL"])) {
		// Getting route value
		$route = Route::give($_SERVER["REQUEST_METHOD"], $_SERVER["REDIRECT_URL"]);
		// If passed function
		if (is_callable($route)) return $route();

		// Retrieving parameters
		$params = explode("/", $route);

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