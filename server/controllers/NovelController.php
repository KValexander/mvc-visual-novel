<?php
// Controller with novel methods
class NovelController extends Common {

	// Add novel
	public function add_novel() {
		// Data validation
		$validator = $this->Validator->make($this->Request->all(), [
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
		foreach($this->Request->all() as $key => $val)
			if (strpos($key, "screenshot") !== false)
				$screenshots[$key] = $val;
		if(count($screenshots) < 1)
			return response(400, "Добавьте не менее одного скриншота");

		// Retrieving user data
		$user = $this->Auth->user();

		// Writing data to variables
		$title 				= $this->Request->input("title");
		$original_title 	= $this->Request->input("original_title");
		$alternative_title 	= $this->Request->input("alternative_title");
		$developer 			= $this->Request->input("developer");
		$year_release 		= $this->Request->input("year_release");
		$description 		= $this->Request->input("description");
		// Lists
		$type 		= $this->Request->input("type");
		$duration 	= $this->Request->input("duration");
		$platforms 	= $this->Request->input("platforms");
		$age_rating = $this->Request->input("age_rating");
		$country 	= $this->Request->input("country");
		$language	= $this->Request->input("language");

		// Inserting data into the database with getting id
		$novel_id = $this->DB->table("novels")->insert_id([
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
		if(!$novel_id) return response(400, $this->DB->$connect->error);

		// Adding genres
		$genres = explode(",", $this->Request->input("genres"));
		foreach($genres as $genre_id) {
			$this->DB->table("novels-genres")->insert([
				"novel_id" => $novel_id,
				"genre_id" => $genre_id
			]);
		}

		// Adding cover and screenshots
		// Getting cover data
		$cover = $this->Request->input("cover");
		$extension = explode(".", $cover["name"])[1];
		$image_name = "1_". time() ."_". rand() .".". $extension;
		$path_to_image = "public/images/". $image_name;
		$type = $cover["type"];
		$size = $cover["size"];
		// Adding data to the database
		$insert = $this->DB->table("images")->insert([
			"foreign_id" => $novel_id,
			"usage" => "cover",
			"path_to_image" => $path_to_image,
			"name" => $image_name,
			"type" => $type,
			"size" => $size,
			"extension" => $extension,
			"affiliation" => "novels"
		]);
		if(!$insert) return response(400, $this->DB->$connect->error);
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
			$insert = $this->DB->table("images")->insert([
				"foreign_id" => $novel_id,
				"usage" => "screenshot",
				"path_to_image" => $path_to_image,
				"name" => $image_name,
				"type" => $type,
				"size" => $size,
				"extension" => $extension,
				"affiliation" => "novels"
			]);
			if(!$insert) return response(400, $this->DB->$connect->error);
			// Uploading an image to the server
			if(!move_uploaded_file($screenshot["tmp_name"], $path_to_image))
				return response(400, "Ошибка сохранения изображения");
		}

		// In case of success
		return response(200, "Новелла отправлена на модерацию");
	}

	// Get novels
	public function get_novels() {
		// Get novels
		$novels = $this->DB->table("novels")->where("state", "=", 1)->get();
		if ($novels == null) return response(200, null);

		// Get genres and images
		foreach($novels as $key => $novel) {
			$join = $this->DB->table("novels-genres")->leftJoin("genres", "genre_id")->where("novel_id", $novel["novel_id"])->get();
			// Getting cover
			$cover = $this->DB->table("images")->where("foreign_id", "=", $novel["novel_id"])->andWhere("usage", "=", "cover")->andWhere("affiliation", "=", "novels")->first();
			$novels[$key]["cover"] = $cover;
			// Getting genres
			$genres = $this->DB->table("novels-genres")->leftJoin("genres", "genre_id")->where("novel_id", $novel["novel_id"])->get();
			foreach($genres as $genre)
				$novels[$key]["genres"] .= $genre["genre"] ." ";
		}
		// Returning data
		return response(200, ["novels" => $novels]);
	}

	// Get novel
	public function get_novel() {
		// Getting novel_id from route
		$id = $this->Request->route("id");

		// Getting novel
		$novel = $this->DB->table("novels")->where("novel_id", "=", $id)->first();
		if ($novel == null) return response(200, null);

		// Getting genres
		$genres = $this->DB->table("novels-genres")->leftJoin("genres", "genre_id")->where("novel_id", $id)->get();
		foreach($genres as $genre)
			$novel["genres"] .= $genre["genre"] ." ";

		// Getting images
		$cover = $this->DB->table("images")
			->where("foreign_id", "=", $id)
			->andWhere("usage", "=", "cover")
			->andWhere("affiliation", "=", "novels")
			->first();
		$novel["cover"] = $cover;
		$screenshots = $this->DB->table("images")
			->where("foreign_id", "=", $id)
			->andWhere("usage", "=", "screenshot")
			->andWhere("affiliation", "=", "novels")
			->get();
		$novel["screenshots"] = $screenshots;

		// Getting user data
		$user = $this->DB->table("users")->where("user_id", "=", $novel["user_id"])->first();
		$user["avatar"] = $this->DB->table("images")
			->where("foreign_id", "=", $user["user_id"])
			->andWhere("usage", "=", "avatar")
			->andWhere("affiliation", "=", "users")
			->first();
		$novel["user"] = $user;

		// Returning data
		return response(200, $novel);
	}
}
?>