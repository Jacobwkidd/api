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
die("REQUEST METHOD: " . $_SERVER['REQUEST_METHOD'] . "<br>URL:" . $url_path);


?>