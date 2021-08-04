<?php
// Controller with moderation methods
class ModerationController {
	// Get users
	public function get_users(){
		$users = DB::table("users")->where("delete_marker", "=", "0")->get();
		return response(200, $users);
	}

	// Get all novels for moderations
	public function get_moderation_novels() {
		$novels = DB::table("novels")->where("state", "=", "0")->get();
		// Getting genres and images
		foreach($novels as $key => $novel) {
			// Getting cover
			$cover = DB::table("images")->where("foreign_id", "=", $novel["novel_id"])->andWhere("usage", "=", "cover")->andWhere("affiliation", "=", "novels")->first();
			$novels[$key]["cover"] = $cover;
			// Getting genres
			$genres = DB::table("novels-genres")->where("novel_id", "=", $novel["novel_id"])->get();
			foreach($genres as $genre_id) {
				$genre = DB::table("genres")->where("genre_id", "=", $genre_id["genre_id"])->first()["genre"];
				$novels[$key]["genres"] .= $genre ." ";
			}
		}
		return response(200, ["novels" => $novels]);
	}

	// Get directory
	public function get_directories() {
		$genres = DB::table("genres")->get();
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