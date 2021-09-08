<?php
// Blanks for the future
class Migration {
	// Constructor Destructor
	function __construct() {
		$this->DB = new Database(DBHOST, DBUSERNAME, DBPASSWORD, DBNAME);
	} function __destruct() { return; }
	
	// Create a database tables
	public function create() {
		$sql = "";
		if($this->DB->query($sql)) echo "Таблицы успешно созданы";
		else echo "Возникла проблема с созданием таблиц";
	}

	// Deleting a database tables
	public function remove() {
		$tables = ["bookmarks", "comments", "genres", "images", "novels", "novels-genres", "users"];
		foreach($tables as $table) {
			$sql = "DROP TABLE $table";
			$this->DB->query($sql);
		}
	}

	// Refresh database
	public function refresh() {
		$this->remove();
		$this->create();
	}
}
?>