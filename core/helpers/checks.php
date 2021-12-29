<?php
	// Checking for trailing slash
	function slash_check($str) {
		$length = strlen($str);
		if($length <= 1) return $str;
		$first = substr($str, 0, 1);
		if($first != "/") $str = "/". $str;
		$last = substr($str, -1);
		if($last == "/") return substr($str, 0, -1);
		else return $str;
	}