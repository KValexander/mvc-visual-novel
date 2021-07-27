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
					if (!is_string($data[$key]))
						$errors->errors[$key] = "Поле должно являться строкой";
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
					if (strlen($data[$key]) > (int)$max[1])
						$errors->errors[$key] = "Поле не должно превышать ". $max[1] ." символов";
					break;

				// Unique
				case "unique":
					$sql = "SELECT `login` FROM `users` WHERE `".$key."`='".$data[$key]."'";
					$result = DB::query($sql);
					if ($result->fetch_assoc() != NULL) $errors->errors[$key] = "Такое значение уже есть в нашей базе";
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
?>