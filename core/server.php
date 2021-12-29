<?php
// Server
class Server {

	// Search route
	public function search_route($path) {
		$path = explode("?", $path)[0];
		if(Router::search($path, $_SERVER["REQUEST_METHOD"])) {
			return true;
		} else return false;
	}

}
?>