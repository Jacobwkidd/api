<?php
// We need to discuss URL rewriting before we do this

include_once("includes/config.inc.php");
include_once("includes/Router.inc.php");

/*
Note that the .htaccess file is configured to redirect to this page (index.php)
and append the requested path to the query string. 
For example, this request:
		http://localhost/api/users/1	 
will be redirected to this:
		http://localhost/api/index.php?url_path=users/1
*/
$url_path = $_GET['url_path'] ?? "";
// die("REQUEST METHOD: " . $_SERVER['REQUEST_METHOD'] . "<br>URL:" . $url_path);


$routes = [
	"users/" => ["controller" => "UserController", "action" => "handleUsers"],
	"users/:id" => ["controller" => "UserController", "action" => "handleSingleUser"],
	"roles/" => ["controller" => "RoleController", "action" => "handleRoles"],
	"login/" =>	["controller" => "LoginController", "action" => "handleLogin"],
	"logout/" => ["controller" => "LoginController", "action" => "handleLogout"]
];

$router = new Router($routes);
$route = $router->getController($url_path);

if($route){
	$className = $route['controller'];
	$methodName = $route['action'];
	//die("Instantiate $className and invoke $methodName");

	// Dynamically import a class, instantiate it, and call a method on it
	include_once("includes/controllers/$className.inc.php"); // Imports the controller
	$controller = new $className(get_link()); // Instantiates the controller
	call_user_func(array($controller, $methodName)); // Invokes the action method

}else{
	header('HTTP/1.1 404 Route Not Found');
}
/*
if($route){
	include_once("includes/controllers/$className.inc.php");
	$controller = new $className(get_link());
	call_user_func(array($controller, $methodName));
	echo("Instantiate this controller: <b>{$route['controller']}</b> and invoke this method: <b>{$route['action']}</b>");
}else{
	header("HTTP/1.1 404 Not Found");
	echo("We don't have a route that matches " . $url_path);
}
*/

die();

?>