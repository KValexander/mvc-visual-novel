<?php
// Controller with user methods
class UserController {

	// Getting a user's role
	public function get_role() {
		$user = Auth::user();
		$role = array();
		$role["code"] = $user["role"];
		switch($role["code"]) {
			case "admin": $role["role"] = "Администратор"; break;
			case "moderator": $role["role"] = "Модератор"; break;
			case "user": $role["role"] = "Пользователь"; break;
			default: $role = NULL; break;
		}
		return response(200, $role);
	}

	// Retrieving Authorized User Data
	public function get_user() {
		$user = Auth::user();
		$user["image"] = DB::table("images")->where("foreign_id", "=", $user["user_id"])->select("path_to_image")->first()["path_to_image"];
		return response(200, $user);
	}

	// Updating user avatar
	public function update_avatar() {
		// Data validation
		$validator = validator(Request::all(), [
			"picture" => "image|max:1024|mimes:jpg,png"
		]);
		// If there are validation errors
		if ($validator->fails) {
			$data = [
				"message" => "Ошибка валидации",
				"errors" => $validator->errors
			];
			return response(422, $data);
		}

		// Retrieving user data
		$user = Auth::user();

		// Retrieving a record to delete a past photo
		$image = DB::table("images")->where("foreign_id", "=", $user["user_id"])->select("path_to_image")->first();
		if($image["path_to_image"] != NULL) unlink($image["path_to_image"]);

		// Retrieving image data
		$image = Request::input("picture");
		$extension = explode(".", $image["name"])[1];
		$image_name = "1_". time() ."_". rand() .".". $extension;
		$path_to_image = "public/images/". $image_name;
		$type = $image["type"];
		$size = $image["size"];

		// Uploading an image to the server
		if(!move_uploaded_file($image["tmp_name"], $path_to_image))
			return response(400, (object)["message" => "Ошибка сохранения изображения"]);

		// Updating data in the database
		DB::table("images")->where("foreign_id", "=", $user["user_id"])->update([
			"path_to_image" => $path_to_image,
			"name" => $image_name,
			"type" => $type,
			"size" => $size,
			"extension" => $extension
		]);

		// In case of success of inserting and changing data
		return response(200, ["message" => "Данные успешно обновлены"]);
	}
}
?>