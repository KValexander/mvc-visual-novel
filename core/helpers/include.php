<?php
	// Connect all files from a folder
	function include_files($path) {
		$files = scandir($path);
		unset($files[0]);
		unset($files[1]);
		$includes = get_included_files();
		foreach($files as $file) {
			if(preg_match("/\.php/", $file))
				include_once $path . $file;
			else include_files($path . $file. "/");
		}
	}
