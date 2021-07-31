<?php
class Auth {
	// Table name
	private static 	$table 			= "users";
	// Primary key
	private static 	$primary_key 	= "user_id";
	// Password field
	private static 	$field_password = "password";
	// Token field
	private static 	$field_token	= "remember_token";
	// Arbitrary header for authorization token
	private static 	$auth_header	= "HTTP_AUTH_TOKEN";
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
				$data = DB::table(self::$table)->where($key, $val)->select($key)->first()[$key];
				// Checking for the presence of data
				if ($data == NULL) return false;

				// Getting a user
				self::$user = DB::table(self::$table)->where($key, $val)->first();

				// Remember token
				if($state == true) {
					$token = Rand::string(50);
					DB::table(self::$table)->where(self::$primary_key, self::$user[self::$primary_key])->update([
						self::$field_token => $token
					]);
				}
				$_SESSION["id"] = self::$user[self::$primary_key];
			}
		}
		return true;
	}

	// Retrieving Authorized User Data
	public static function user() {
		if (isset($_SESSION["id"])) {
			self::$user = DB::table(self::$table)->where(self::$primary_key, $_SESSION["id"])->first();
			// In any case, self::$user will contain either data or NULL
			return self::$user;
		}
		else return NULL;
	}

	// Returning an encrypted token
	public static function token() {
		$result = DB::table(self::$table)->where(self::$primary_key, $_SESSION["id"])->select(self::$field_token)->first();
		if ($result != NULL) return crypt($result[self::$field_token]);
		else return NULL;
	}

	// Token check
	public static function check() {
		$db_token = self::db_token();
		$auth_token = self::auth_token();
		if(hash_equals($auth_token, crypt($db_token, $auth_token)))
			return true;
		else return false;
	}

	// Logout from authorization
	public static function logout() {
		// unset($_SESSION["id"]);
		session_destroy();
	}

	// Returning an unencrypted token
	private static function db_token() {
		$db_token = DB::table(self::$table)->where(self::$primary_key, $_SESSION["id"])->select(self::$field_token)->first();
		return $db_token[self::$field_token];
	}

	// Getting an arbitrary authorization token from the header
	private static function auth_token() {
		return $_SERVER[self::$auth_header];
	}
}
?>