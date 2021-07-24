<?php 
spl_autoload_register(function($class) {
	if(is_file("server/". $class .".php")) {
		include "server/". $class .".php";
		if (!class_exists($class, false))
			exit("Class $class not found");
	} else exit("Module $class not found");
});

if(isset($_GET["m"])) {
	$controller = new $_GET["m"];

	if(!method_exists($controller, "init"))
		exit("Method init not found");

	$res = $controller->init();
	if(isset($res["pagename"]) && is_file("index.php"))
		include "index.php";
	else exit("File index.php not found");
}

?>