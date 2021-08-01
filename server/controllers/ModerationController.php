<?php
// Including files
include "config/validator.php";
include "config/auth.php";

class ModerationController {
	public function getUsers(){
		$users = DB::table("users")->get();
		return response(200, $users);
	}
}
?>