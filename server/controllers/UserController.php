<?php
// Including files
include "config/validator.php";
include "config/auth.php";

class UserController {

	// Getting a user's role
	public function get_role() {
		$user = Auth::user();
		if ($user != NULL) return response(200, $user["role"]);
		else return response(200, NULL);
	}

	// Retrieving Authorized User Data
	public function get_user() {
		return response(200, Auth::user());
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
		$path_to_image = "server/". $path_to_image;

		// Retrieving user data
		$user = Auth::user();

		// Updating data in the database
		DB::table("images")->where("foreign_id", $user["user_id"])->update([
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