<?php
	// Including files
	include "config/database.php";
	include "config/routes.php";

	// Database connection
	$db = new Database;
	$db = $db->connect();

	// Checking for Route Availability
	if(array_key_exists($_SERVER["REQUEST_URI"], $routes)) {
		$params = explode("/", $routes[$_SERVER["REQUEST_URI"]]);

		// Getting and checking a class and controller
		include "controllers/". $params[0] .".php";
		if(!class_exists($params[0], false))
			exit("Class $params[0] not found");

		$controller = new $params[0]($db);

		// Getting and checking a method
		if(!method_exists($controller, $params[1]))
			exit("Method $params[1] not found");

		$method = (string)$params[1];

		// Method return
		return $controller->$method($_REQUEST);
	} else exit("Route not found");

?>