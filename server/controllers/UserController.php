<?php
// Including files
include "config/validator.php";
include "config/auth.php";

class UserController {

	// Getting a user's role
	public function get_role() {
		$user = Auth::user();
		if ($user != NULL) return response(200, $user["role"]);
		else return response(200, NULL);
	}

	// Retrieving Authorized User Data
	public function get_user() {
		return response(200, Auth::user());
	}

	// Updating user avatar
	public function update_avatar() {
		// Data validation
		$validator = validator(Request::all(), [
			"picture" => "image|max:1024"
		]);
		// If there are validation errors
		if ($validator->fails) {
			$data = (object)[
				"message" => "Ошибка валидации",
				"errors" => $validator->errors
			];
			return response(422, $data);
		}

		$image = Request::input("picture");
	}
}
?>