<?php
class Common {
	function __construct() {
		$this->DB = new Database(DBHOST, DBUSERNAME, DBPASSWORD, DBNAME);
		$this->Auth = new Authenticate($this->DB, ATABLE, APKEY, AFPASSWORD, AFTOKEN, AHEADER);
		$this->Request = new Request();
		$this->Validator = new Validator($this->DB);
	} function __destruct() { return; }
}
?>