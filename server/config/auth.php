<?php
// Authenticate class
class Authenticate {
	// Constructor
	function __construct($DB, $table, $primary_key, $field_password, $field_token, $auth_header) {
		$this->DB = $DB;
		$this->table = $table;
		$this->primary_key = $primary_key;
		$this->field_password = $field_password;
		$this->field_token = $field_token;
		$this->auth_header = $auth_header;
	} function __destruct() { return; }

	// User authorization
	public function attempt($arr, $state=false) {
		// Checking for data coincidence
		foreach($arr as $key => $val) {
			// Password check
			if ($key == $this->field_password)
				if(hash_equals($this->user[$key], crypt($val, $this->user[$key]))) continue;
				else return false;
			else {
				$data = $this->DB->table($this->table)->where($key, "=", $val)->select($key)->first()[$key];
				// Checking for the presence of data
				if ($data == NULL) return false;

				// Getting a user
				$this->user = $this->DB->table($this->table)->where($key, "=", $val)->first();

				// Remember token
				if($state == true) {
					$token = rand_string(50);
					$this->DB->table($this->table)->where($this->primary_key, "=", $this->user[$this->primary_key])->update([
						$this->field_token => $token
					]);
				}
				$_SESSION["id"] = $this->user[$this->primary_key];
			}
		}
		return true;
	}

	// Retrieving Authorized User Data
	public function user() {
		if (self::check()) {
			$this->user = $this->DB->table($this->table)->where($this->primary_key, "=", $_SESSION["id"])->first();
			// In any case, $this->user will contain either data or NULL
			return $this->user;
		}
		else return NULL;
	}

	// Returning an encrypted token
	public function token() {
		$result = $this->DB->table($this->table)->where($this->primary_key, "=", $_SESSION["id"])->select($this->field_token)->first();
		if ($result != NULL) return crypt($result[$this->field_token]);
		else return NULL;
	}

	// Token check
	public function check() {
		$db_token = $this->db_token();
		$auth_token = $this->auth_token();
		if ($db_token == NULL || $auth_token == NULL) return false;
		if(hash_equals($auth_token, crypt($db_token, $auth_token)))
			return true;
		else return false;
	}

	// Logout from authorization
	public function logout() {
		// unset($_SESSION["id"]);
		session_destroy();
	}

	// Returning an unencrypted token
	private function db_token() {
		$db_token = $this->DB->table($this->table)->where($this->primary_key, "=", $_SESSION["id"])->select($this->field_token)->first();
		return $db_token[$this->field_token];
	}

	// Getting an arbitrary authorization token from the header
	private function auth_token() {
		return $_SERVER[$this->auth_header];
	}
}
?>