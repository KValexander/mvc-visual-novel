<?php
// Moderation middleware
class ModerationMiddleware {
	public function handle() {
		if (Auth::check()) {
			$user = Auth::user();
			if ($user["role"] == "moderator" || $user["role"] == "admin") return true;
			else return response(403, ["message" => "Ошибка прав доступа"]);
		}
		else return response(401, ["message" => "Вы не авторизованы"]);
	}
}
?>