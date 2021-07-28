<?php
class Auth {
	private static 	$table 			= "users";
	private static 	$primary_key 	= "user_id";
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

				// Remember token
				if($state == true) {
					self::$token = Rand::string(50);
					$_SESSION["token"] = self::$token;
					DB::query(sprintf("UPDATE `%s` SET `%s`='%s' WHERE `%s`='%s'", self::$table, self::$field_token, self::$token, self::$primary_key, self::$user[self::$primary_key]));
				}
				$_SESSION["check"] = self::$check;
			}
		}
		return true;
	}

	// Retrieving Authorized User Data
	public static function user() {
		if (self::check()) {
			self::$user = DB::query(sprintf("SELECT * FROM `%s` WHERE `%s`='%s'", self::$table, self::$field_token, $_SESSION["token"]))->fetch_assoc();
			return self::$user;
		}
		else return NULL;
	}

	// Authorization check
	public static function check() {
		return $_SESSION["check"];
	}

	// Logout from authorization
	public static function logout() {
		// unset($_SESSION["token"]);
		// unset($_SESSION["check"]);
		session_destroy();
	}
}
?>