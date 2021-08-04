<?php
// Controller with novel methods
class NovelController {

	// Add novel
	public function add_novel() {
		// Data validation
		$validator = validator(Request::all(), [
			"title" => "required|string",
			"cover" => "image|mimes:jpg,png|max:1024",
			"developer" => "required|string",
			"year_release" => "required|string|year",
			"description" => "required|string",
			"genres" => "required|string",
			"type" => "required",
			"duration" => "required",
			"platforms" => "required",
			"age_rating" => "required",
			"country" => "required",
			"language" => "required"
		]);
		// Checking for Validation Errors
		if($validator->fails) {
			$data = [
				"message" => "Ошибка валидации",
				"errors" => $validator->errors
			];
			return response(422, $data);
		}

		// Getting screenshots
		$screenshots = array();
		foreach(Request::all() as $key => $val)
			if (strpos($key, "screenshot") !== false)
				$screenshots[$key] = $val;
		if(count($screenshots) < 1)
			return response(400, "Добавьте не менее одного скриншота");

		// Retrieving user data
		$user = Auth::user();

		// Writing data to variables
		$title 				= Request::input("title");
		$original_title 	= Request::input("original_title");
		$alternative_title 	= Request::input("alternative_title");
		$developer 			= Request::input("developer");
		$year_release 		= Request::input("year_release");
		$description 		= Request::input("description");
		// Lists
		$type 		= Request::input("type");
		$duration 	= Request::input("duration");
		$platforms 	= Request::input("platforms");
		$age_rating = Request::input("age_rating");
		$country 	= Request::input("country");
		$language	= Request::input("language");

		// Inserting data into the database with getting id
		$novel_id = DB::table("novels")->insert_id([
			"user_id" => $user["user_id"],
			"title" => $title,
			"original_title" => $original_title,
			"alternative_title" => $alternative_title,
			"developer" => $developer,
			"year_release" => $year_release,
			"description" => $description,
			"type" => $type,
			"duration" => $duration,
			"platforms" => $platforms,
			"age_rating" => $age_rating,
			"country" => $country,
			"language" => $language
		]);
		if(!$novel_id) return response(400, DB::$connect->error);

		// Adding genres
		$genres = explode(",", Request::input("genres"));
		foreach($genres as $genre_id) {
			DB::table("novels-genres")->insert([
				"novel_id" => $novel_id,
				"genre_id" => $genre_id
			]);
		}

		// Adding cover and screenshots
		// Getting cover data
		$cover = Request::input("cover");
		$extension = explode(".", $cover["name"])[1];
		$image_name = "1_". time() ."_". rand() .".". $extension;
		$path_to_image = "public/images/". $image_name;
		$type = $cover["type"];
		$size = $cover["size"];
		// Adding data to the database
		$insert = DB::table("images")->insert([
			"foreign_id" => $novel_id,
			"usage" => "cover",
			"path_to_image" => $path_to_image,
			"name" => $image_name,
			"type" => $type,
			"size" => $size,
			"extension" => $extension,
			"affiliation" => "novels"
		]);
		if(!$insert) return response(400, DB::$connect->error);
		// Uploading an image to the server
		if(!move_uploaded_file($cover["tmp_name"], $path_to_image))
			return response(400, "Ошибка сохранения изображения");

		// Screenshot adding handling
		foreach($screenshots as $key => $screenshot) {
			// Getting screenshot data
			$extension = explode(".", $screenshot["name"])[1];
			$image_name = "1_". time() ."_". rand() .".". $extension;
			$path_to_image = "public/images/". $image_name;
			$type = $screenshot["type"];
			$size = $screenshot["size"];
			// Adding data to the database
			$insert = DB::table("images")->insert([
				"foreign_id" => $novel_id,
				"usage" => "screenshot",
				"path_to_image" => $path_to_image,
				"name" => $image_name,
				"type" => $type,
				"size" => $size,
				"extension" => $extension,
				"affiliation" => "novels"
			]);
			if(!$insert) return response(400, DB::$connect->error);
			// Uploading an image to the server
			if(!move_uploaded_file($screenshot["tmp_name"], $path_to_image))
				return response(400, "Ошибка сохранения изображения");
		}

		// In case of success
		return response(200, "Новелла отправлена на модерацию");
	}
}
?>