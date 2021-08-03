<?php
// Controller with moderation methods
class ModerationController {
	// Get users
	public function get_users(){
		$users = DB::table("users")->where("delete_marker", "=", "0")->get();
		return response(200, $users);
	}

	// Get directory
	public function get_directories() {
		$genres = DB::table("genres")->orderBy("genre", "ASC")->get();
		$data = ["genres" => $genres];
		return response(200, $data);
	}

	// Add genre
	public function add_genre() {
		$validator = validator(Request::all(), ["genre" => "required|string|unique:genres,genre_id"]);
		if($validator->fails) return response(422, ["message" => "Ошибка валидации", "errors" => $validator->errors]);
		$genre = Request::input("genre");
		$insert = DB::table("genres")->insert(["genre" => $genre]);
		if($insert) return response(200, ["message" => "Жанр успешно добавлен"]);
	}

	// Delete genre
	public function delete_genre() {
		$genre_id = Request::input("genre_id");
		$delete = DB::table("genres")->where("genre_id", "=", $genre_id)->delete();
		if($delete) return response(200, ["message" => "Жанр успешно удалён"]);
	}
}
?>