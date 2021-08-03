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
	// Role
	Route::get("/api/role", "UserController/get_role");

	// Routes for authorized users only
	Route::middleware("AuthMiddleware", function() {
		// User
		Route::get("/api/user", "UserController/get_user");
		// Update avatar
		Route::post("/api/user/update/avatar", "UserController/update_avatar");
		// Get directories
		Route::get("/api/get/directories", "ModerationController/get_directories");
		// Logout
		Route::get("/api/logout", "AuthController/logout");

		// Add novel
		Route::post("/api/add/novel", "NovelController/add_novel");
	});

	// Routes for administration only
	Route::middleware("ModerationMiddleware", function() {
		// Get all user
		Route::get("/api/get/users", "ModerationController/get_users");
		// Add genre
		Route::get("/api/add/genre", "ModerationController/add_genre");
		// Delete genre
		Route::get("/api/delete/genre", "ModerationController/delete_genre");
	});
	// Auth check
	Route::post("/api/auth/check", "AuthController/auth_check");
?>