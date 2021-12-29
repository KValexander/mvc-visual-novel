<?php
// Parent class of middlewares
class Middleware {
	protected $db;
	protected $auth;
	protected $request;
	protected $validator;

	// Constructor
	function __construct() {
		$this->db = new Database(DBHOST, DBUSERNAME, DBPASSWORD, DBNAME);
		$this->auth = new Authenticate();
		$this->request = new Request();
		$this->validator = new Validator();
	}

	// Destructor
	function __destruct() {
		unset($this->db);
		unset($this->auth);
		unset($this->request);
		unset($this->validator);
	}

}

?>