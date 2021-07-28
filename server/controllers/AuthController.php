<?php
// Including files
include "config/validator.php";
include "config/response.php";
include "config/auth.php";

class AuthController {

	// New User Registration
	public function register($request) {
		// Data validation
		$validator = validator($request, [
			"username" => "required|string|max:30",
			"email" => "required|string|email|max:50",
			"login" => "required|string|max:30|unique:users,login",
			"password" => "required|string|max:100|coincidence:password_check",
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
		$password 		= crypt(trim($request["password"]));
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
		$data = (object)["message" => "Аккаунт успешно зарегистрирован"];
		return response(200, $data);
	}

	// User authorization
	public function login($request) {
		// Data validation
		$validator = validator($request, [
			"login" => "required|string|max:30",
			"password" => "required|string|max:100",
		]);

		// If there are validation errors
		if($validator->fails) {
			$data = (object)[
				"message" => "Ошибка валидации",
				"errors" => $validator->errors
			];
			return response(422, $data);
		}

		// Writing data to variables 
		$login = trim($request["login"]);
		$password = trim($request["password"]);

		// Authorization check
		if(Auth::attempt(["login" => $login, "password" => $password], true)) {
			$data = (object)[
				"message" => "Вы успешно авторизировались",
				"token" => $token
			];
			return response(200, $data);
		} else {
			$data = (object)["message" => "Ошибка логина или пароля"];
			return response(401, $data);
		}
	}

	// Logout
	public function logout() {
		Auth::logout();
	}

}
?>