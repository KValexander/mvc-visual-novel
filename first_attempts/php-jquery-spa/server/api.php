<?php
	// Session start
	session_start();

	// Data to connect on database
	define("DBHOST", 		"localhost");
	define("DBUSERNAME", 	"root");
	define("DBPASSWORD", 	"root");
	define("DBNAME", 		"novel-re");

	// Data to authenticate
	define("ATABLE", "users");
	define("APKEY", "user_id");
	define("AFPASSWORD", "password");
	define("AFTOKEN", "remember_token");
	define("AHEADER", "HTTP_AUTH_TOKEN");

	// Array of directories
	$directories = [
		"config/" => scandir("config/"),
		"helpers/" => scandir("helpers/"),
		"controllers/" => scandir("controllers/"),
		"middleware/" => scandir("middleware/")
	];
	// Including files from directories
	foreach($directories as $key => $files) {
		unset($files[0], $files[1]);
		foreach($files as $val) include $key . $val;
	}
	// Including the routes file
	include "routes.php";

	// Headers
	// header("Access-Control-Allow-Origin: *"); // Full access
	header("Access-Control-Allow-Origin: http://specific"); // Access only for a specific domain
	header("Content-Type:application/json;charset=UTF-8");

	// Checking for Route Availability
	if (Route::check($_SERVER["REQUEST_METHOD"], $_SERVER["REDIRECT_URL"])) Route::call();
	else return response(404, "Route not found");

?>