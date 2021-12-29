<?php
	session_start();
	
	// Headers
	header("Access-Control-Allow-Origin: *");

	// Include constants
	include "config.php";

	// Include include (ha-ha, classic)
	include "core/helpers/include.php";

	// Core and helpers include
	include_files("core/");

	// Models include
	include_files("models/");

	// Include class Controller
	include "controllers/Controller.php";

	// Include class Middleware
	include "middlewares/Middleware.php";

	// Include routes
	include "routes.php";

	// Server
	$server = new Server();

	// Check and processing route in case of availability
	if(!$server->search_route($_SERVER["REQUEST_URI"]))
		return view("index");
		// echo "<h1>This path doesn't exist</h1>";
		// or return view("404"); in view

?>