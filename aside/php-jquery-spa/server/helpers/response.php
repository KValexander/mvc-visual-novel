<?php
	function response($status, $data=NULL) {
		header("HTTP/1.1 ".$status);
		$response["status"] = $status;
		$response["data"] = $data;
		$json_response = json_encode($response);
		echo $json_response;
	}
?>