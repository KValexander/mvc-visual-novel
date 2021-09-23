<?php
class FavoriteController extends Common {
	public function favorite_add() {
		$novel_id = $this->Request->route('id');
		$user_id = $this->Auth->user()["user_id"];

		$this->DB->table("bookmarks")->insert([
			"user_id" => $user_id,
			"novel_id" => $novel_id,
			"type" => "favorite"
		]);

		return response(200, "Новелла добавлена в избранное");
	}

	public function favorite_delete() {
		$novel_id = $this->Request->route('id');
		$user_id = $this->Auth->user()["user_id"];
		$this->DB->table("bookmarks")->where("novel_id", $novel_id)->andWhere("user_id", $user_id)->delete();
		return response(200, "Новелла удалена из избранного");
	}
} 
?>