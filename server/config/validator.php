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
							if (!strpos("@", $data[$key]))
								$errors->errors[$key] = "Поле должно содержать символ @";
						break;

					// Min
					case strpos("min:", $val):
						$min = explode(":", $val);
						if (strlen($data[$key]) < (int)$min[1])
							$errors->errors["email"] = "Поле не должно быть меньше ". $min[1] ." символов";
						break;

					// Max
					case strpos("max:", $val):
						$max = explode(":", $val);
						if (strlen($data[$key]) > (int)$max[1])
							$errors->errors["email"] = "Поле не должно превышать ". $max[1] ." символов";
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