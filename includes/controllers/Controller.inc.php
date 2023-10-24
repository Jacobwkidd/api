<?php

class Controller {

	// a link to the database
	protected $link;
	
	
	function __construct($link){
		$this->link = $link;
	}


	// Removes the trailing slash from a url string
	// So that users/7/ becomes users/7
	function removeLastSlash($str){
		return rtrim($str, "/");
	}


	// Gets the requested url path
	// Note that in the .htaccess file we append the url path
	// as a query string param named url_path
	function getUrlPath(){
		return $_GET['url_path'];
	}

	// converts the body of a request from a JSON string to an assoc array
	function getJSONRequestBody(){
		$request_body = file_get_contents('php://input');
		$ar = json_decode($request_body, true);
		return $ar;
	}

	// Sets the Content-Type header in the response
	// (to let the client know the format of the data in the response)
	function setContentType($type){
		
		$content_type = "";
		
		switch($type){
			case "json":
				$content_type = "application/json";
				break;
			case "csv":
				$content_type = "text/csv";
				break;
			case "xml":
				$content_type = "application/xml";
				break;
			default:
				// what should we set the default content type to???
				$content_type = "text/html"; // ?????
		}

		header("Content-Type: $content_type");
	}


	// A wrapper method for the php header() function
	// So that we are consistently setting the header message properly
	// When we send a header
	function sendStatusHeader($statusCode, $msg="", $replace=true){

		// Got this array from:  https://gist.github.com/phoenixg/5326222
		$http = array(
	        100 => 'HTTP/1.1 100 Continue',
	        101 => 'HTTP/1.1 101 Switching Protocols',
	        200 => 'HTTP/1.1 200 OK',
	        201 => 'HTTP/1.1 201 Created',
	        202 => 'HTTP/1.1 202 Accepted',
	        203 => 'HTTP/1.1 203 Non-Authoritative Information',
	        204 => 'HTTP/1.1 204 No Content',
	        205 => 'HTTP/1.1 205 Reset Content',
	        206 => 'HTTP/1.1 206 Partial Content',
	        300 => 'HTTP/1.1 300 Multiple Choices',
	        301 => 'HTTP/1.1 301 Moved Permanently',
	        302 => 'HTTP/1.1 302 Found',
	        303 => 'HTTP/1.1 303 See Other',
	        304 => 'HTTP/1.1 304 Not Modified',
	        305 => 'HTTP/1.1 305 Use Proxy',
	        307 => 'HTTP/1.1 307 Temporary Redirect',
	        400 => 'HTTP/1.1 400 Bad Request',
	        401 => 'HTTP/1.1 401 Unauthorized',
	        402 => 'HTTP/1.1 402 Payment Required',
	        403 => 'HTTP/1.1 403 Forbidden',
	        404 => 'HTTP/1.1 404 Not Found',
	        405 => 'HTTP/1.1 405 Method Not Allowed',
	        406 => 'HTTP/1.1 406 Not Acceptable',
	        407 => 'HTTP/1.1 407 Proxy Authentication Required',
	        408 => 'HTTP/1.1 408 Request Time-out',
	        409 => 'HTTP/1.1 409 Conflict',
	        410 => 'HTTP/1.1 410 Gone',
	        411 => 'HTTP/1.1 411 Length Required',
	        412 => 'HTTP/1.1 412 Precondition Failed',
	        413 => 'HTTP/1.1 413 Request Entity Too Large',
	        414 => 'HTTP/1.1 414 Request-URI Too Large',
	        415 => 'HTTP/1.1 415 Unsupported Media Type',
	        416 => 'HTTP/1.1 416 Requested Range Not Satisfiable',
	        417 => 'HTTP/1.1 417 Expectation Failed',
	        500 => 'HTTP/1.1 500 Internal Server Error',
	        501 => 'HTTP/1.1 501 Not Implemented',
	        502 => 'HTTP/1.1 502 Bad Gateway',
	        503 => 'HTTP/1.1 503 Service Unavailable',
	        504 => 'HTTP/1.1 504 Gateway Time-out',
	        505 => 'HTTP/1.1 505 HTTP Version Not Supported'
	    );

	    header($http[$statusCode] . " " . $msg, $replace);
	}

}