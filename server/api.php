<?php
	// Including files
	include "config/database.php";
	include "config/rand.php";
	include "routes.php";

	// Session start
	session_start();

	// Database connection
	DB::connect();

	// Headers 
	header("Access-Control-Allow-Origin: *");
	header("Content-Type:application/json;charset=UTF-8");

	// Checking for Route Availability
	if (Route::search($_SERVER["REQUEST_METHOD"], $_SERVER["REDIRECT_URL"])) {
		$params = explode("/", Route::give($_SERVER["REQUEST_METHOD"], $_SERVER["REDIRECT_URL"]));

		// Getting and checking a class and controller
		include "controllers/". $params[0] .".php";
		if(!class_exists($params[0], false))
			exit("Class $params[0] not found");

		$controller = new $params[0];

		// Getting and checking a method
		if(!method_exists($controller, $params[1]))
			exit("Method $params[1] not found");

		$method = (string)$params[1];

		return $controller->$method($_REQUEST);
	} else exit("Route not found");

?>