<?php
// Root directories
define('APP_ROOT', $_SERVER['DOCUMENT_ROOT'] . '/');
define('APP_DOMEN', $_SERVER['REQUEST_SCHEME']. '://' .$_SERVER['HTTP_HOST'] . '/');

// Data for connecting to the base
define("DBHOST", 	 "");
define("DBUSERNAME", "");
define("DBPASSWORD", "");
define("DBNAME", 	 "");

// Data for authorization
define("AUTH_TABLE", 		"user"); 	 // Users table
define("AUTH_PRIMARY_KEY", 	"user_id");  // User table primary key
define("AUTH_PASSWORD", 	"password"); // Password field in the users table
define("AUTH_TOKEN", 		"token"); 	 // User table token field
// The header to which the token is sent from the client side
define("AUTH_HEADER", 		"AUTH-TOKEN");

// Global Variables
$view_views_args = array();
$response_args = array();