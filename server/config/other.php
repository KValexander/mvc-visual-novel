<?php
// final class Router {
// 	public static $routes = array();
// 	public static $params = array();
// 	public static $requestedUrl = "";

// 	public static function addRoute($route, $destination=null) {
// 		if($destination != null && !is_array($route))
// 			$route = array($route => $destination);
// 		self::$routes = array_merge(self::$routes, $route);
// 	}

// 	public static function splitUrl($url) {
// 		return preg_replace("/\//", $url, -1, PREG_SPLIT_NO_EMPTY);
// 	}

// 	public static function getCurrentUrl() {
// 		return (self::$requestedUrl?:"/");
// 	}

// 	public static function dispatch($requestedUrl=null) {
// 		if($requestedUrl === null) {
// 			$uri = reset(explode("?", $_SERVER["REQUEST_URI"]));
// 			$requestedUrl = urldecode(rtrim($uri, "/"));
// 		}

// 		self::$requestedUrl = $requestedUrl;

// 		if(isset(self::$routes[$requestedUrl])) {
// 			self::$params = self::splitUrl(self::$routes[$requestedUrl]);
// 			return self::executeAction();
// 		}

// 		foreach(self::$routes as $route=>$uri) {
// 			if(strpos($route, ":") !== false)
// 				$route = str_replace(":any", "(.+)", str_replace(":num", "([0-9]+)", $route));

// 			if(preg_match("#^".$route."$#", $requestedUrl)) {
// 				if(strpos($uri, "$") !== false && strpos($route, "(") !== false)
// 					$uri = preg_replace("#^".$route."$#", $uri, $requestedUrl);
// 				self::$params = self::splitUrl($uri);
// 				break;
// 			}
// 		}
// 		return self::executeAction();
// 	}

// 	public static function executeAction() {
// 		$controller = isset(self::$params[0]) ? self::$params[0]: "Controller";
// 		$action = isset(self::$params[1]) ? self::$params[1]: "default";
// 		$params = array_slice(self::$params, 2);
// 		return @call_user_func_array(array($controller, $action), $params);
// 	}
// }

// class Controller {
// 	public function default() {
// 		print("safasd");
// 		return false;
// 	}
// }

// class MainController {
// 	public function main_page() {
// 		echo "asfasd";
// 	}
// }

// spl_autoload_register(function($class) {
// 	if(is_file("server/". $class .".php")) {
// 		include "server/". $class .".php";
// 		if (!class_exists($class, false))
// 			exit("Class $class not found");
// 	} else exit("Module $class not found");
// });

// if(isset($_GET["m"])) {
// 	$controller = new $_GET["m"];

// 	if(!method_exists($controller, "init"))
// 		exit("Method init not found");

// 	$res = $controller->init();
// 	if(isset($res["pagename"]) && is_file("index.php"))
// 		include "index.php";
// 	else exit("File index.php not found");
// }

?>