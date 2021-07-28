<?php
	include "config/route.php";

	// Register
	Route::post("/api/register", "AuthController/register");
	// Login
	Route::post("/api/login", "AuthController/login");
	// User
	Route::get("/api/user", "UserController/get_user");
	// Role
	Route::get("/api/role", "UserController/get_role");
	// Logout
	Route::get("/api/logout", "AuthController/logout");
?>