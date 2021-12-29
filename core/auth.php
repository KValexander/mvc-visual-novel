<?php
// Authenticate
class Authenticate {
	private $db;

	// Constructor
	function __construct() {
		$this->db = new Database(DBHOST, DBUSERNAME, DBPASSWORD, DBNAME);
	}

	// Destructor
	function __destruct() {
		unset($db);
	}

	// Authorization
	public function attempt($array, $token=false) {
		$user = array();
		foreach($array as $key => $val) {
			// Password field
			if ($key == AUTH_PASSWORD) {
				if(hash_equals($user[$key], crypt($val, $user[$key]))) continue;
				else return false;
			}
			// Plain fields
			else {
				// User data validation
				if(!$user = $this->db->table(AUTH_TABLE)->where($key, $val)->first())
					return false;
				// Writing user id to session
				$_SESSION["auth_user_id"] = $user[AUTH_PRIMARY_KEY];
				// Token
				if($token)
					$this->db->table(AUTH_TABLE)->where(AUTH_PRIMARY_KEY, $_SESSION["auth_user_id"])->update([AUTH_TOKEN => rand_string(50)]);
			}
		} return true;
	}

	// Get user data
	public function user() {
		if($this->check())
			return $this->db->table(AUTH_TABLE)->where(AUTH_PRIMARY_KEY, $_SESSION["auth_user_id"])->first();
	}

	// Auth check
	public function check($type="id") {
		switch($type) {
			// Authentication with a session
			case "id":
				if(isset($_SESSION["auth_user_id"]))
					return true;
				else return false;
			break;
			// Authentication with a token
			case "token":
				if($this->check()) {
					$db_token = $this->db_token();
					$sent_token = $this->sent_token();
					if ($db_token == NULL || $sent_token == NULL) return false;
					if(hash_equals($sent_token, crypt($db_token, $sent_token)))
						return true;
					else return false;
				}
			break;
		}
	}

	// Logout
	public function logout() {
		if($this->check()) {
			$this->db->table(AUTH_TABLE)->where(AUTH_PRIMARY_KEY, $_SESSION["auth_user_id"])->update([AUTH_TOKEN => NULL]);
			unset($_SESSION["auth_user_id"]);
		}
	}

	// Get token from database
	private function db_token() {
		return $this->db->table(AUTH_TABLE)->where(AUTH_PRIMARY_KEY, $_SESSION["auth_user_id"])->select(AUTH_TOKEN)->first()[AUTH_TOKEN];
	}

	// Get the sent token
	private function sent_token() {
		return $_SERVER[AUTH_HEADER];
	}

}
?>