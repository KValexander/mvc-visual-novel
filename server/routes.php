<?php
	// type = get || post
	// Route::get(...);
	// Route::post(...);

	// All routes must go through /api/

	// Route::type("/api/route", function(){});
	// Route::type("/api/route", "Controller/method");

	// Route::type("/api/route", function(){})->middleware(function(){});
	// Route::type("/api/route", function(){})->middleware(Middleware);
	// Route::type("/api/route", "Controller/method")->middleware(function(){});
	// Route::type("/api/route", "Controller/method")->middleware("Middleware");

	/*
	Route::middleware("middleware", function() {
		Route::type("/api/route", function(){});
		Route::type("/api/route", "Controller/method");
	});
	*/

	// Register
	Route::post("/api/register", "AuthController/register");
	// Login
	Route::post("/api/login", "AuthController/login");

	// Routes for authorized users only
	Route::middleware("AuthMiddleware", function() {
		// User
		Route::get("/api/user", "UserController/get_user");
		// Update avatar
		Route::post("/api/user/update/avatar", "UserController/update_avatar");
		// Logout
		Route::get("/api/logout", "AuthController/logout");
	});

	// Routes for administration only
	Route::middleware("ModerationMiddleware", function() {
		// Get all user
		Route::get("/api/get/users", "ModerationController/get_users");
		// Get directories
		Route::get("/api/get/directories", "ModerationController/get_directories");
		// Add genre
		Route::get("/api/add/genre", "ModerationController/add_genre");
	});

	// Role
	Route::get("/api/role", "UserController/get_role");
	// Auth check
	Route::post("/api/auth/check", "AuthController/auth_check");
?>