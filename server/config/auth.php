<?php
class Auth {
	private static 	$table 			= "users";
	private static 	$field_password = "password";
	private static 	$field_token	= "remember_token";
	private static 	$check 			= false;
	private static 	$token;
	private static 	$user;

	// User authorization
	public static function attempt($arr, $state=false) {
		// Checking for data coincidence
		foreach($arr as $key => $val) {
			// Password check
			if ($key == self::$field_password)
				if(hash_equals(self::$user[$key], crypt($val, self::$user[$key]))) continue;
				else return false;
			else {
				// Sending a request
				$data = DB::query(sprintf("SELECT `%s` FROM `%s` WHERE `%s`='%s'", $key, self::$table, $key, $val));
				// Retrieving data from a request
				$row = $data->fetch_assoc()[$key];
				// Checking for the presence of data
				if ($row == NULL) return false;

				// Getting a user
				$sql = sprintf("SELECT * FROM `%s` WHERE `%s`='%s'", self::$table, $key, $val);
				self::$user = DB::query($sql)->fetch_assoc();
				self::$check = true;

				if($state == true) self::$token = Rand::string(50);

				$_SESSION["user"] = self::$user;
				$_SESSION["check"] = self::$check;
			}
		}
		return true;
	}

	// Retrieving Authorized User Data
	public static function user() {
		return $_SESSION["user"];
	}

	// Authorization check
	public static function check() {
		return $_SESSION["check"];
	}
}
?>