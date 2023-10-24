<?php
/*
ERROR HANDLING
*/

set_error_handler("myErrorHandler");

function myErrorHandler($errno, $errstr, $errfile, $errline){

	$str = "THIS IS OUR CUSTOM ERROR HANDLER\n";
	$str .= "ERROR NUMBER: " . $errno . "\nERROR MSG: " . $errstr . "\nFILE: " . $errfile . "\nLINE NUMBER: " . $errline . "\n\n";
	
	/*
	// You might want to send all the super globals with the error message 
	$str .= "\nPOST\n" . print_r($_POST, true);
	$str .= "\nGET\n" . print_r($_GET, true);
	$str .= "\nSERVER\n" . print_r($_SERVER, true);
	$str .= "\nFILES\n" . print_r($_FILES, true);
	$str .= "\nCOOKIE\n" . print_r($_COOKIE, true);
	$str .= "\nSESSION\n" . print_r($_SESSION, true);
	$str .= "\nREQUEST\n" . print_r($_REQUEST, true);
	$str .= "\nENV\n" . print_r($_ENV, true);
	*/
	
	if(DEBUG_MODE){
		echo($str);
		// Note that we aren't sending the SERVER ERROR header, so that you can easily read the error message in the body
	}else{
		
		//send email to web admin
		mail(SITE_ADMIN_EMAIL, SITE_DOMAIN . " - ERROR", $str);
		header($_SERVER['SERVER_PROTOCOL'] . ' 500 Internal Server Error', true, 500);
	}
}




