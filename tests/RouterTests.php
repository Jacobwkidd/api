<?php

include_once("../includes/Router.inc.php");


$routes = [
	"users/" => ["controller" => "UserController", "action" => "handleUsers"],
	"users/:id" => ["controller" => "UserController", "action" => "handleSingleUser"],
	"roles/" => ["controller" => "RoleController", "action" => "handleRoles"],
	"customers/:id/orders" =>	["controller" => "CustomerController", "action" => "getCustomerOrders"]
];


$testResults = array();

testInvalidPath();
testValidPaths();

echo(implode("<br>", $testResults));


function testInvalidPath(){
	global $testResults, $routes;

	$testResults[] = "<b>TESTING invalid paths</b>";

	$router = new Router($routes);

	// If an invalid path is passed into getController() then false should be returned
	$path = "some/invalid/path";
	if($router->getController($path) === false){
		$testResults[] = "PASS -  Returned  false for invalid path.";
	}else{
		$testResults[] = "Failed -  Did NOT return  false for invalid path.";
	}


}

function testValidPaths(){

	global $testResults, $routes;

	
	$testResults[] = "<b>TESTING users/</b>";
	$router = new Router($routes);
	$path = "users/";
	$expectedRoute = ["controller" => "UserController", "action" => "handleUsers"];
	$actualRoute = $router->getController($path);
	
	// There should be no differences between the expectedRoute and the actualRoute
	$diff = array_diff_assoc($expectedRoute, $actualRoute);

	if(empty($diff)){
		$testResults[] = "PASS -  Returned the proper controller and action for the <b>users/</b> path.";
	}else{
		$testResults[] = "Failed -  Did NOT return the proper controller and action for the <b>users/</b> path.";
	}



	$testResults[] = "<b>TESTING users</b>";
	$router = new Router($routes);
	$path = "users";
	$expectedRoute = ["controller" => "UserController", "action" => "handleUsers"];
	$actualRoute = $router->getController($path);
	
	// There should be no differences between the expectedRoute and the actualRoute
	$diff = array_diff_assoc($expectedRoute, $actualRoute);

	if(empty($diff)){
		$testResults[] = "PASS -  Returned the proper controller and action for the <b>users</b> path.";
	}else{
		$testResults[] = "Failed -  Did NOT return the proper controller and action for the <b>users</b> path.";
	}


	$testResults[] = "<b>TESTING users/7</b>";
	$router = new Router($routes);
	$path = "users/7";
	$expectedRoute = ["controller" => "UserController", "action" => "handleSingleUser"];
	$actualRoute = $router->getController($path);
	
	// There should be no differences between the expectedRoute and the actualRoute
	$diff = array_diff_assoc($expectedRoute, $actualRoute);

	if(empty($diff)){
		$testResults[] = "PASS -  Returned the proper controller and action for the <b>users/7</b> path.";
	}else{
		$testResults[] = "Failed -  Did NOT return the proper controller and action for the <b>users/7</b> path.";
	}


	$testResults[] = "<b>TESTING users/7/</b>";
	$router = new Router($routes);
	$path = "users/7";
	$expectedRoute = ["controller" => "UserController", "action" => "handleSingleUser"];
	$actualRoute = $router->getController($path);
	
	// There should be no differences between the expectedRoute and the actualRoute
	$diff = array_diff_assoc($expectedRoute, $actualRoute);

	if(empty($diff)){
		$testResults[] = "PASS -  Returned the proper controller and action for the <b>users/7/</b> path.";
	}else{
		$testResults[] = "Failed -  Did NOT return the proper controller and action for the <b>users/7/</b> path.";
	}





	$testResults[] = "<b>TESTING roles</b>";
	$router = new Router($routes);
	$path = "roles";
	$expectedRoute = ["controller" => "RoleController", "action" => "handleRoles"];
	$actualRoute = $router->getController($path);
	
	// There should be no differences between the expectedRoute and the actualRoute
	$diff = array_diff_assoc($expectedRoute, $actualRoute);

	if(empty($diff)){
		$testResults[] = "PASS -  Returned the proper controller and action for the <b>roles</b> path.";
	}else{
		$testResults[] = "Failed -  Did NOT return the proper controller and action for the <b>roles</b> path.";
	}


	$testResults[] = "<b>TESTING roles/</b>";
	$router = new Router($routes);
	$path = "roles/";
	$expectedRoute = ["controller" => "RoleController", "action" => "handleRoles"];
	$actualRoute = $router->getController($path);
	
	// There should be no differences between the expectedRoute and the actualRoute
	$diff = array_diff_assoc($expectedRoute, $actualRoute);

	if(empty($diff)){
		$testResults[] = "PASS -  Returned the proper controller and action for the <b>roles/</b> path.";
	}else{
		$testResults[] = "Failed -  Did NOT return the proper controller and action for the <b>roles/</b> path.";
	}




	$testResults[] = "<b>TESTING customers/7/orders</b>";
	$router = new Router($routes);
	$path = "customers/7/orders";
	$expectedRoute = ["controller" => "CustomerController", "action" => "getCustomerOrders"];
	$actualRoute = $router->getController($path);
	
	// There should be no differences between the expectedRoute and the actualRoute
	$diff = array_diff_assoc($expectedRoute, $actualRoute);

	if(empty($diff)){
		$testResults[] = "PASS -  Returned the proper controller and action for the <b>customers/7/orders</b> path.";
	}else{
		$testResults[] = "Failed -  Did NOT return the proper controller and action for the <b>customers/7/orders</b> path.";
	}


	$testResults[] = "<b>TESTING customers/7/orders/</b>";
	$router = new Router($routes);
	$path = "customers/7/orders/";
	$expectedRoute = ["controller" => "CustomerController", "action" => "getCustomerOrders"];
	$actualRoute = $router->getController($path);
	
	// There should be no differences between the expectedRoute and the actualRoute
	$diff = array_diff_assoc($expectedRoute, $actualRoute);

	if(empty($diff)){
		$testResults[] = "PASS -  Returned the proper controller and action for the <b>customers/7/orders/</b> path.";
	}else{
		$testResults[] = "Failed -  Did NOT return the proper controller and action for the <b>customers/7/orders/</b> path.";
	}

	

}