<?php
// Controller with moderation methods
class DirectoryController extends Common {

	// Get directory
	public function get_directories() {
		$genres = $this->DB->table("genres")->get();
		$data = ["genres" => $genres];
		return response(200, $data);
	}

	// Add genre
	public function add_genre() {
		$validator = $this->Validator->make($this->Request->all(), ["genre" => "required|string|unique:genres,genre_id"]);
		if($validator->fails) return response(422, ["message" => "Ошибка валидации", "errors" => $validator->errors]);
		$genre = $this->Request->input("genre");
		$insert = $this->DB->table("genres")->insert(["genre" => $genre]);
		if($insert) return response(200, ["message" => "Жанр успешно добавлен"]);
	}

	// Delete genre
	public function delete_genre() {
		$genre_id = $this->Request->input("genre_id");
		$delete = $this->DB->table("genres")->where("genre_id", "=", $genre_id)->delete();
		if($delete) return response(200, ["message" => "Жанр успешно удалён"]);
	}
}
?>