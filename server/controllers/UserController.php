<?php
// Including files
include "config/response.php";
include "config/auth.php";

class UserController {

	// Getting a user's role
	public function get_role($request) {
		if(Auth::check()) {
			$user = Auth::user();
			return response(200, $user["role"]);
		} else return response(200, NULL);
	}

	// Retrieving Authorized User Data
	public function get_user($request) {
		return response(200, Auth::user());
	}

}
?>