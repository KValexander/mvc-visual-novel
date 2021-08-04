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

	// Route::type("/api/route/{id}", function() {
	// 	echo Request::route("id");
	// });

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

		// Add novel
		Route::post("/api/add/novel", "NovelController/add_novel");

		// Get directories
		Route::get("/api/get/directories", "ModerationController/get_directories");

		// User
		Route::get("/api/user", "UserController/get_user");

		// Update avatar
		Route::post("/api/user/update/avatar", "UserController/update_avatar");

		// Get novels for moderation
		Route::get("/api/user/moderation_novels", "UserController/get_moderation_novels");

		// Logout
		Route::get("/api/logout", "AuthController/logout");

	});

	// Routes for administration only
	Route::middleware("ModerationMiddleware", function() {

		// Get all users
		Route::get("/api/get/users", "ModerationController/get_users");

		// Get all novels for moderation
		Route::get("/api/get/moderation_novels", "ModerationController/get_moderation_novels");

		// Add genre
		Route::get("/api/add/genre", "ModerationController/add_genre");

		// Delete genre
		Route::get("/api/delete/genre", "ModerationController/delete_genre");

	});

	// Auth check
	Route::post("/api/auth/check", "AuthController/auth_check");
?>