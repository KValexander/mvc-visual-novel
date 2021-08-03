<?php
// Validator
function validator($data, $arr) {
	// Error object
	$errors = (object)[
		"fails" => false,
		"errors" => [],
	];

	// Validation processing
	foreach($arr as $key => $value) {
		$errors->errors[$key] = "";
		$reg = explode("|", $value);
		// Processing rules
		foreach($reg as $val) {
			$val = (string)$val;
			// Switch
			switch ($val) {
				// Required
				case "required":
					if ($data[$key] === "")
						$errors->errors[$key] = "Поле не должно быть пустым";
					break;

				// String
				case "string":
					if (!is_string($data[$key])) {
						var_dump("Какого хера?", $data[$key], $key, $data);
						$errors->errors[$key] = "Поле должно являться строкой";
					}
					break;

				// Email
				case "email":
					if (strlen($data[$key]) != 0) 
						if (!preg_match("/@/", $data[$key]))
							$errors->errors[$key] = "Поле должно содержать символ @";
					break;

				// Min
				case preg_match("/min:/", $val) == true:
					$min = explode(":", $val);
					if (strlen($data[$key]) < (int)$min[1])
						$errors->errors[$key] = "Поле должно быть не меньше ". $min[1] ." символов";
					break;

				// Max
				case preg_match("/max:/", $val) == true:
					$max = explode(":", $val);
					// Image check
					if(isset($data[$key]["name"])) {
						$check = check_image($data[$key]);
						if ($check === true) {
							$size = filesize($data[$key]["tmp_name"]) / 1024;
							if ($size > (int)$max[1])
								$errors->errors[$key] = "Файл не должен превышать ". $max[1] / 1024 ." мб";
						}
					// Checking text data
					} else
						if (strlen($data[$key]) > (int)$max[1])
							$errors->errors[$key] = "Поле не должно превышать ". $max[1] ." символов";
					break;

				// Unique
				case preg_match("/unique:/", $val) == true:
					$unique = explode(":", $val);
					$unique = explode(",", $unique[1]);
					$availability = DB::table($unique[0])->where($unique[1], "=", $data[$key])->select($unique[1])->first();
					if ($availability != NULL) $errors->errors[$key] = "Такое значение уже есть в нашей базе";
				break;

				// Coincidence
				case preg_match("/coincidence:/", $val) == true:
					$coincidence = explode(":", $val);
					if ($data[$key] != $data[$coincidence[1]])
						$errors->errors[$key] = "Поля не совпадают";
					break;

				// Image
				case "image":
					$check = check_image($data[$key]);
					if ($check != true)
						$errors->errors[$key] = $check;
					break;

				// Mimes
				case preg_match("/mimes:/", $val) == true:
					$check = check_image($data[$key]);
					if ($check === true) {
						$mimes = explode(",", explode(":", $val)[1]);
						$extension = explode(".", $data[$key]["name"])[1];
						if(in_array($extension, $mimes) === false)
							$errors->errors[$key] = "Расширение изображения не подходит";
					} else $errors->errors[$key] = $check;
					break;
			}
		}
	}
	
	// Checking for validation errors 
	foreach ($errors->errors as $key => $val) {
		if ($val != "") $errors->fails = true;
		else unset($errors->errors[$key]);
	}

	// Returning an error object
	return $errors;
}

// Checking a file for an image
function check_image($data) {
	if (isset($data["error"])) {
		if ($data["error"] != UPLOAD_ERR_OK) {
			return "Файл не загружен";
		} else {
			$file = @getimagesize($data["tmp_name"]);
			if ($file == false) return "Файл не является изображением";
			else return true;
		}
	} else return "Данные не являются файлом";
}
?>