<?php
// Including files
include "config/validator.php";
include "config/auth.php";

class AuthController {

	// New User Registration
	public function register() {
		// Data validation
		$validator = validator(Request::all(), [
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
		$username 		= trim(Request::input("username"));
		$email 			= trim(Request::input("email"));
		$login 			= trim(Request::input("login"));
		$password 		= crypt(trim(Request::input("password")));
		$password_check = trim(Request::input("password_check"));

		// Default data
		$role = "user";

		// Composing a request to add data to the database
		$insert_sql = sprintf("INSERT INTO `users`(`username`, `email`, `login`, `password`, `role`) VALUES ('%s', '%s', '%s', '%s', '%s')", DB::$connect->real_escape_string($username), DB::$connect->real_escape_string($email), DB::$connect->real_escape_string($login), DB::$connect->real_escape_string($password), DB::$connect->real_escape_string($role));
		// Adding and validating data insertion
		if(!DB::query($insert_sql)) {
			$data = (object)["message" => "Ошибка вставки данных", "error" => DB::$connect->error];
			return response(400, $data);
		}
		
		// Request to add an avatar entry
		$insert_sql = sprintf("INSERT INTO `images`(`foreign_id`, `usage`, `affiliation`) VALUES ('%d', '%s', '%s')", DB::$connect->insert_id, "avatar", "users");
		// Adding and validating data insertion
		if(!DB::query($insert_sql)) {
			$data = (object)["message" => "Ошибка вставки данных", "error" => DB::$connect->error];
			return response(400, $data);
		}

		// In case of successful data insertion
		$data = (object)["message" => "Аккаунт успешно зарегистрирован"];
		return response(200, $data);
	}

	// User authorization
	public function login() {
		// Data validation
		$validator = validator(Request::all(), [
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
		$login = trim(Request::input("login"));
		$password = trim(Request::input("password"));

		// Authorization check
		if(Auth::attempt(["login" => $login, "password" => $password], true)) {
			$token = Auth::token();
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

	// Auth check
	public function auth_check() {
		if (Auth::check()) return response(200, true);
		else return response(200, false);
	}

}
?>