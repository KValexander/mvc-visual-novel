<?php
// Comment controller
class CommentController {

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