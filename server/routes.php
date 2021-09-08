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

	$route = new Route();

	

	// Register
	Route::post("/api/auth/register", "AuthController/register");

	// Login
	Route::post("/api/auth/login", "AuthController/login");

	// Role
	Route::get("/api/user/role", "UserController/get_role");

	// Get approved novels
	Route::get("/api/novels", "NovelController/get_novels");

	// Get novel
	Route::get("/api/novel/{id}", "NovelController/get_novel");

	// Get directories
	Route::get("/api/directory/get", "DirectoryController/get_directories");

	// Get comments
	Route::get("/api/novel/{id}/comments", "CommentController/get_comments");

	// Routes for authorized users only
	Route::middleware("AuthMiddleware", function() {

		// Add novel
		Route::post("/api/add/novel", "NovelController/add_novel");

		// User
		Route::get("/api/user", "UserController/get_user");

		// Update avatar
		Route::post("/api/user/update/avatar", "UserController/update_avatar");

		// Get moderated novels
		Route::get("/api/user/moderated_novels", "UserController/get_moderated_novels");

		// Get approved novels
		Route::get("/api/user/approved_novels", "UserController/get_approved_novels");

		// Add comment
		Route::post("/api/novel/{id}/comment/add", "CommentController/add_comment");

		// Delete comment
		Route::get("/api/novel/{id}/comment/delete", "CommentController/delete_comment");

		// Logout
		Route::get("/api/auth/logout", "AuthController/logout");

	});

	// Routes for administration only
	Route::middleware("ModerationMiddleware", function() {

		// Get all users
		Route::get("/api/moderation/users/get", "ModerationController/get_users");

		// Get all novels for moderation
		Route::get("/api/moderation/novels/get", "ModerationController/get_moderation_novels");

		// Approve novel
		Route::get("/api/moderation/novel/{id}/approve", "ModerationController/novel_approve");

		// Add genre
		Route::get("/api/directory/genre/add", "DirectoryController/add_genre");

		// Delete genre
		Route::get("/api/directory/genre/delete", "DirectoryController/delete_genre");

	});

	// Auth check
	Route::post("/api/auth/check", "AuthController/auth_check");
?>