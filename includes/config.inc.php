<?php
// this is the main configuration file for the website

// set up custom error and exception handling
require_once('custom_error_handler.inc.php');
require_once('custom_exception_handler.inc.php');

// detect which environment the code is running in
if($_SERVER['SERVER_NAME'] == "localhost"){
	// DEV ENVIRONMENT SETTINGS
	define("DEBUG_MODE", true); 
	define("DB_HOST", "localhost");
	define("DB_USER", "root");
	define("DB_PASSWORD", "test");
	define("DB_NAME", "draft100");
	define("SITE_ADMIN_EMAIL", "PUT EMAIL ADDRESS HERE");
	define("SITE_DOMAIN", $_SERVER['SERVER_NAME']);
	define("REQUIRE_HTTPS", false);

	// On the dev environment, we may want to disable securing the server resources
	define("SECURE_SERVER_RESOURCES", true);

	/* TODO: uncomment this code when we start dealing with CORS requests
	// On the dev environment we will be making CORS requests 
	header("Access-Control-Allow-Origin: *");  // WE SHOULD REPLACE THE * WITH SPECIFIC DOMAINS
	header("Access-Control-Allow-Headers: *"); // You could also allow only certain headers to be sent in CORS requests
	*/

}else{
	// PRODUCTION SETTINGS
	define("DEBUG_MODE", false); 
	// you may want to set DEBUG_MODE to true when you 
	// are first setting up your live site, but once you get
	// everything working you'd want it off (false).
	define("DB_HOST", "localhost");
	define("DB_USER", "xxxxx");
	define("DB_PASSWORD", "xxxxx");
	define("DB_NAME", "xxxxx");
	define("SITE_ADMIN_EMAIL", "xxxxx");
	define("SITE_DOMAIN", $_SERVER['SERVER_NAME']);
	define("REQUIRE_HTTPS", true);

	// On the live environment, we must ALWAYS secure the server resources!
	define("SECURE_SERVER_RESOURCES", true);
		
}

// if we are in debug mode then display all errors and set error reporting to all 
if(DEBUG_MODE){
	// turn on error messages
	ini_set('display_errors', 1);
	ini_set('display_startup_errors', 1);
	error_reporting(E_ALL);
}

// the $link variable will be our connection to the database
$link = null;

function get_link(){

	global $link;
		
	if($link == null){
		
		$link = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

		if(!$link){
			throw new Exception(mysqli_connect_error()); 
		}
	}

	return $link;
}


// SESSION HANDLING (and authentication)...
// I found that browsers do not send cookies with CORS requests
// So I created a custom header called x-id
// In order for the browser to see the x-id header we need to 'expose it' by sending this header!
header("Access-Control-Expose-Headers: x-id");

// The IF statement below will extract the x-id header from the request and use it to resume the session 
// (note that the LoginController initally sets the x-id header when a user authenticates)
$headers = getallheaders();
if(isset($headers['x-id'])){
	// this x-id header will have the current session id in it
	// and we can restore the session by passing the id into session_id()
	// (make sure this happens before calling session_start())
	session_id($headers['x-id']);
}


// Now that we have specified the session ID, we can start/resume the session
session_start();


// If HTTPS is required them make sure it's being used to make the AJAX calls
if(REQUIRE_HTTPS){
  if(empty($_SERVER['HTTPS']) || $_SERVER["HTTPS"] != "on"){
    header('HTTP/1.1 400 Invalid Request HTTPS is required', true, 400);
    exit();
  }
}



