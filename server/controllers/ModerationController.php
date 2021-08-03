<?php
// Controller with moderation methods
class ModerationController {
	// Get users
	public function get_users(){
		$users = DB::table("users")->where("delete_marker", "=", "0")->get();
		return response(200, $users);
	}

	// Add directory
	public function add_genre() {
		$genre = Request::input("genre");
		$insert = DB::table("genres")->insert(["genre" => $genre]);
		var_dump($insert);
	}

	// Get directory
	public function get_directories() {
		$genres = DB::table("genres")->get();
		$data = [
			"genres" => $genres,
		];
		return response(200, $data);
	}
}
?>