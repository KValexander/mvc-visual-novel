<?php
	// Api routes, all routes go through /api/
	$routes = array(
		// Register
		"/api/register" => "AuthController/register",
		// Login
		"/api/login" => "AuthController/login",
		// Getting user data
		"/api/user" => "AuthController/getUserData",
	);
?>