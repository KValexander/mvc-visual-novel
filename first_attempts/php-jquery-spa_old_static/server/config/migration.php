<?php
// Blanks for the future
class Migration {
	// Create a database tables
	public static function create() {
		$sql = "";
		if(DB::query($sql)) echo "Таблицы успешно созданы";
		else echo "Возникла проблема с созданием таблиц";
	}

	// Deleting a database tables
	public static function remove() {
		$tables = ["bookmarks", "comments", "genres", "images", "novels", "novels-genres", "users"];
		foreach($tables as $table) {
			$sql = "DROP TABLE $table";
			DB::query($sql);
		}
	}

	// Refresh database
	public static function refresh() {
		self::remove();
		self::create();
	}
}
?>