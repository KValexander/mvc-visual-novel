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
			$data = [
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

		// Adding data to the database
		$id = DB::table("users")->insert_id([
			"username" => $username,
			"email" => $email,
			"login" => $login,
			"password" => $password,
			"role" => $role,
		]);
		if (!$id) return response(400, DB::$connect->error);

		// Adding data to the database
		$insert = DB::table("images")->insert([
			"foreign_id" => $id,
			"usage" => "avatar",
			"affiliation" => "users"
		]);
		if (!$insert) return response(400, DB::$connect->error);

		// In case of successful data insertion
		$data = ["message" => "Аккаунт успешно зарегистрирован"];
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
			$data = [
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
			$data = [
				"message" => "Вы успешно авторизировались",
				"token" => $token
			];
			return response(200, $data);
		} else {
			$data = ["message" => "Ошибка логина или пароля"];
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