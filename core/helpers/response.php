<?php
	// Sending a response
	function response($status, $data=NULL) {
		global $response_args;
		header("HTTP/1.1 ".$status);
		$response_args["status"] = $status;
		$response_args["data"] = $data;
		$json_response = json_encode($response_args);
		$response_args = array();
		echo $json_response;
	}

	// Passing data to a response
	function response_share($args) {
		global $response_args;
		foreach($args as $key => $val)
			$response_args[$key] = $val;
	}
