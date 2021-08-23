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
	// header("Access-Control-Allow-Origin: *"); // Full access
	header("Access-Control-Allow-Origin: http://spa"); // Access only for a specific domain
	header("Content-Type:application/json;charset=UTF-8");

	// Checking for Route Availability
	if (Route::check($_SERVER["REQUEST_METHOD"], $_SERVER["REDIRECT_URL"])) Route::call();
	else return response(404, "Route not found");

?>