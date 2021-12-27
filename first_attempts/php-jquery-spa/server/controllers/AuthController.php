<?php
// Controller with authorization methods
class AuthController extends Common {

	// New User Registration
	public function register() {
		// Data validation
		$validator = $this->Validator->make($this->Request->all(), [
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
		$username 		= trim($this->Request->input("username"));
		$email 			= trim($this->Request->input("email"));
		$login 			= trim($this->Request->input("login"));
		$password 		= crypt(trim($this->Request->input("password")));
		$password_check = trim($this->Request->input("password_check"));

		// Default data
		$role = "user";

		// Adding data to the database
		$id = $this->DB->table("users")->insert_id([
			"username" => $username,
			"email" => $email,
			"login" => $login,
			"password" => $password,
			"role" => $role,
		]);
		if (!$id) return response(400, $this->DB->connect->error);

		// Adding data to the database
		$insert = $this->DB->table("images")->insert([
			"foreign_id" => $id,
			"usage" => "avatar",
			"affiliation" => "users"
		]);
		if (!$insert) return response(400, $this->DB->connect->error);

		// In case of successful data insertion
		$data = ["message" => "Аккаунт успешно зарегистрирован"];
		return response(200, $data);
	}

	// User authorization
	public function login() {
		// Data validation
		$validator = $this->Validator->make($this->Request->all(), [
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
		$login = trim($this->Request->input("login"));
		$password = trim($this->Request->input("password"));

		// Authorization check
		if($this->Auth->attempt(["login" => $login, "password" => $password], true)) {
			$token = $this->Auth->token();
			$user_id = $this->Auth->user()["user_id"];
			$data = [
				"message" => "Вы успешно авторизировались",
				"token" => $token,
				"user_id" => $user_id
			];
			return response(200, $data);
		} else {
			$data = ["message" => "Ошибка логина или пароля"];
			return response(401, $data);
		}
	}

	// Logout
	public function logout() {
		$this->Auth->logout();
	}

	// Auth check
	public function auth_check() {
		if ($this->Auth->check()) return response(200, true);
		else return response(200, false);
	}

}
?>