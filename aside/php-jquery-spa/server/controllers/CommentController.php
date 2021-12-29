<?php
// Comment controller
class CommentController extends Common {

	// Get comments
	public function get_comments() {
		// Novel id
		$novel_id = $this->Request->route("id");
		// Comments
		$comments = $this->DB->table("comments")->where("novel_id", "=", $novel_id)->get();
		// User data
		foreach($comments as $key => $comment) {
			$comments[$key]["user"] = $this->DB->table("users")->where("user_id", "=", $comment["user_id"])->first();
			$comments[$key]["user"]["avatar"] = $this->DB->table("images")
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
		$validator = $this->Validator->make($this->Request->all(), ["comment" => "required|string"]);
		// If there are validation errors
		if($validator->fails) return response(422, $validator->errors["comment"]);

		// Writing data to variables
		$novel_id = $this->Request->route("id");
		$user_id = $this->Auth->user()["user_id"];
		$comment = $this->Request->input("comment");

		// Adding data to the database
		$insert = $this->DB->table("comments")->insert([
			"user_id" => $user_id,
			"novel_id" => $novel_id,
			"content" => $comment
		]);
		if(!$insert) return response(400, $this->DB->$connect->error);
		// In case of success
		return response(200, "Комментарий успешно добавлен");
	}

	// Delete comment
	public function delete_comment() {
		// Retrieving data
		$novel_id = $this->Request->route("id");
		$comment_id = $this->Request->input("id");
		$comment_user_id = $this->DB->table("comments")->where("comment_id", "=", $comment_id)->first()["user_id"];
		$user_id = $this->Auth->user()["user_id"];

		// Owner verification
		if ($comment_user_id != $user_id)
			return response(403, "Вы не являетесь владельцем комментария");
		
		// Deleting a comment
		$delete = $this->DB->table("comments")->where("comment_id", "=", $comment_id)->delete();
		if(!$delete)
			return response(400, $this->DB->$connect->error);

		// In case of successful deletion
		return response(200, "Комментарий удалён");
	}
}
?>