<?php
class Common {
	function __construct() {
		$this->DB = new Database(DBHOST, DBUSERNAME, DBPASSWORD, DBNAME);
		$this->Auth = new Authenticate(ATABLE, APKEY, AFPASSWORD, AFTOKEN, AHEADER);
		$this->Request = new Request();
		$this->Validator = new Validator();
	} function __destruct() { return; }
}
?>