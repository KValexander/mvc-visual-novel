<?php
// Including files
include "config/validator.php";
include "config/response.php";

class AuthController {

	// Getting a connection to the database
	public function __construct($db) {
		$this->db = $db;
	}

	// New User Registration
	public function register($request) {
		// Writing data to variables 
		$username 		= trim($request["username"]);
		$email 			= trim($request["email"]);
		$login 			= trim($request["login"]);
		$password 		= trim($request["password"]);
		$password_check = trim($request["password_check"]);

		// Default data
		$role = "user";

		// Data validation
		$err = validator($request, [
			"username" => "required|string|max:30",
			"email" => "required|string|email|max:50",
			"login" => "required|string|max:30",
			"password" => "required|string|max:100",
			"password_check" => "required",
		]);
		
		// If there are validation errors
		if ($err->fails) {
			$data = (object)[
				"message" => "Ошибка валидации",
				"errors" => $err->errors
			];
			return response(422, $data);
		}

		// Composing a request to add data to the database
		$insert_sql = sprintf("INSERT INTO `users`(`username`, `email`, `login`, `password`, `role`) VALUES ('%s', '%s', '%s', '%s', '%s')",
			$this->db->real_escape_string($username),
			$this->db->real_escape_string($email),
			$this->db->real_escape_string($login),
			$this->db->real_escape_string($password),
			$this->db->real_escape_string($role)
		);

		// Adding and validating data insertion
		if(!$this->db->query($insert_sql)) {
			$data = (object)["message" => "Ошибка вставки данных"];
			return response(400, $data);
		}

		// In case of successful data insertion
		$data = (object)["message" => "You have successfully registered"];
		return response(200, $data);
	}

	// User authorization
	public function login($request) {
		print("Metalyga");
	}

}
?>