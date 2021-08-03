<?php
// Auth middleware
class AuthMiddleware {
	public function handle() {
		if (Auth::check()) return true;
		else return response(403, ["message" => "Вы не авторизованы"]);
	}
}
?>