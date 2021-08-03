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
			"platform" => "required",
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

		return response(200, "NULL");
	}
}
?>