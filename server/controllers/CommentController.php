<?php
// Comment controller
class CommentController {

	// Add comment
	public function add_comment() {
		echo Request::route("id");
		echo Request::input("comment");
	}

	// Delete comment
	public function delete_comment() {

	}
}
?>