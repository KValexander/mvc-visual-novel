<?php
// Auth middleware
class AuthMiddleware extends Common {
	public function handle() {
		if ($this->Auth->check()) return true;
		else return response(403, ["message" => "Вы не авторизованы"]);
	}
}
?>