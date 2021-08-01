<?php
// Including files
include "config/validator.php";
include "config/auth.php";

class ModerationController {
	// Get users
	public function getUsers(){
		$users = DB::table("users")->where("delete_marker", "=", "0")->get();
		return response(200, $users);
	}
}
?>