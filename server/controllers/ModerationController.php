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

	// Novel approve
	public function novel_approve() {
		echo Request::route("id");
	}
}
?>