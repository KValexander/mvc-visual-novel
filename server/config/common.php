<?php
class Common {
	function __construct() {
		$this->DB = new Database(DBHOST, DBUSERNAME, DBPASSWORD, DBNAME);
		$this->Auth = new Authenticate($this->DB, [
			"table" => ATABLE,
			"primary_key" => APKEY,
			"field_password" => AFPASSWORD,
			"field_token" => AFTOKEN,
			"auth_header" => AHEADER
		]);
		$this->Request = new Request();
		$this->Validator = new Validator($this->DB);
	} function __destruct() { return; }
}
?>