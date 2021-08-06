<?php
// Comment controller
class CommentController {

	// Get comments
	public function get_comments() {
		// Novel id
		$novel_id = Request::route("id");
		// Comments
		$comments = DB::table("comments")->where("novel_id", "=", $novel_id)->get();
		// User data
		foreach($comments as $key => $comment) {
			$comments[$key]["user"] = DB::table("users")->where("user_id", "=", $comment["user_id"])->first();
			$comments[$key]["user"]["avatar"] = DB::table("images")
				->where("foreign_id", "=", $comments[$key]["user"]["user_id"])
				->andWhere("usage", "=", "avatar")
				->andWhere("affiliation", "=", "users")
				->first();
		}
		// Response
		return response(200, ["comments" => $comments]);
	}

	// Add comment
	public function add_comment() {
		// Data validation
		$validator = validator(Request::all(), ["comment" => "required|string"]);
		// If there are validation errors
		if($validator->fails) return response(422, $validator->errors["comment"]);

		// Writing data to variables
		$novel_id = Request::route("id");
		$user_id = Auth::user()["user_id"];
		$comment = Request::input("comment");

		// Adding data to the database
		$insert = DB::table("comments")->insert([
			"user_id" => $user_id,
			"novel_id" => $novel_id,
			"content" => $comment
		]);
		if(!$insert) return response(400, DB::$connect->error);
		// In case of success
		return response(200, "Комментарий успешно добавлен");
	}

	// Delete comment
	public function delete_comment() {

	}
}
?>