<?php
// Calling a view
function view($view, $args=[]) {
	global $view_views_args;

	// Checking for file existence
	if(!file_exists("public/views/".$view.".php"))
		return print("File ". $view .".php doesn't exists");

	// Converting an array to variables
	view_share($args);
	foreach($view_views_args as $key => $val)
		${$key} = $val;

	// Connecting a view
	include "public/views/".$view.".php";

	// Clearing an array of view arguments
	$view_views_args = array();
}

// Passing data to a view
function view_share($args) {
	global $view_views_args;
	foreach($args as $key => $val)
		$view_views_args[$key] = $val;
}