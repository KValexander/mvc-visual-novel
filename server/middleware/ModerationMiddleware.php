<?php
// Moderation middleware
class ModerationMiddleware extends Common {
	public function handle() {
		if ($this->Auth->check()) {
			$user = $this->Auth->user();
			if ($user["role"] == "moderator" || $user["role"] == "admin") return true;
			else return response(403, ["message" => "Ошибка прав доступа"]);
		}
		else return response(401, ["message" => "Вы не авторизованы"]);
	}
}
?>