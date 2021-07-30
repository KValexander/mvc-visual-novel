<?php
	include "config/route.php";
	// Middleware
	Route::middleware("AuthMiddleware", function() {
		Route::get("/api/test", function() {
			echo "test";
		});
	});

	// Register
	Route::post("/api/register", "AuthController/register");
	// Login
	Route::post("/api/login", "AuthController/login");
	// User
	Route::get("/api/user", "UserController/get_user");
	// Update avatar
	Route::post("/api/user/update/avatar", "UserController/update_avatar");
	// Role
	Route::get("/api/role", "UserController/get_role");
	// Logout
	Route::get("/api/logout", "AuthController/logout");

	// Auth check
	Route::post("/api/auth/check", "AuthController/auth_check");
?>