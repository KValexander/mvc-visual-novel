<?php
// Including files
include "config/validator.php";
include "config/response.php";

class AuthController {

	// New User Registration
	public function register($request) {
		// Data validation
		$validator = validator($request, [
			"username" => "required|string|max:30",
			"email" => "required|string|email|max:50",
			"login" => "required|string|max:30|unique",
			"password" => "required|string|max:100",
			"password_check" => "required",
		]);

		// If there are validation errors
		if ($validator->fails) {
			$data = (object)[
				"message" => "Ошибка валидации",
				"errors" => $validator->errors
			];
			return response(422, $data);
		}

		// Writing data to variables 
		$username 		= trim($request["username"]);
		$email 			= trim($request["email"]);
		$login 			= trim($request["login"]);
		$password 		= trim($request["password"]);
		$password_check = trim($request["password_check"]);

		// Default data
		$role = "user";

		// Composing a request to add data to the database
		$insert_sql = sprintf("INSERT INTO `users`(`username`, `email`, `login`, `password`, `role`) VALUES ('%s', '%s', '%s', '%s', '%s')",
			DB::$connect->real_escape_string($username),
			DB::$connect->real_escape_string($email),
			DB::$connect->real_escape_string($login),
			DB::$connect->real_escape_string($password),
			DB::$connect->real_escape_string($role)
		);

		// Adding and validating data insertion
		if(!DB::query($insert_sql)) {
			$data = (object)["message" => "Ошибка вставки данных"];
			return response(400, $data);
		}

		// In case of successful data insertion
		$data = (object)["message" => "You have successfully registered"];
		return response(200, $data);
	}

	// User authorization
	public function login($request) {
		return response(422, "auf");
	}

}
?>