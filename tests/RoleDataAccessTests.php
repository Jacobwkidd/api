<?php
include_once(__DIR__ . "/../includes/dataaccess/RoleDataAccess.inc.php");
include_once(__DIR__ . "/../includes/models/Role.inc.php");
include_once("create-test-database.php");


$testResults = array();

// You'll have to run all these tests for each of your data access classes
testConstructor(); // we should test the constructor, but maybe we'll skip this one in the interest of time
testConvertModelToRow();
testConvertRowToModel();
testGetAll();
testGetById();
testInsert(); 
testUpdate(); 
testDelete(); 

echo(implode("<br>",$testResults));


function testConstructor(){

	global $testResults, $link;
	$testResults[] = "<b>TESTING constructor...</b>";

	// TEST - create an instance of the ConcactDataAccess class
	$da = new RoleDataAccess($link);
	
	if($da){
		$testResults[] = "PASS - Created instance of RoleDataAccess";
	}else{
		$testResults[] = "FAIL - DID NOT creat instance of RoleDataAccess";
	}

	// Test - an exception should be thrown if the $link param is not a valid link
	try{
		$da = new RoleDataAccess("BLAHHHHHH");
		$testResults[] = "FAIL - Exception is NOT thrown when link param is invalid";
	}catch(Exception $e){
		$testResults[] = "PASS - Exception is thrown when link param is invalid";
	}
}

function testConvertModelToRow(){

	global $testResults, $link;

	$testResults[] = "<b>TESTING convertModelToRow()...</b>";

	$da = new RoleDataAccess($link);

	$options = array(
		'id' => 1,
		'name' => "Test Role",
		'description' => "This is a test role"
	);

	$r = new Role($options);

	$expectedResult = array(
		'user_role_id' => 1,
		'user_role_name' => "Test Role",
		'user_role_desc' => "This is a test role"
	);
	
	$actualResult = $da->convertModelToRow($r);

	/*
	// This helped me to discover that I needed to convert the id to an int (with intval())
	echo("<h2>Expected Result</h2>");
	var_dump($expectedResult);
	echo("<h2>Actual Result</h2>");
	var_dump($actualResult);
	die();
	*/
	

	if(empty(array_diff_assoc($expectedResult, $actualResult))){
		$testResults[] = "PASS - Converted Role to proper assoc array";
	}else{
		$testResults[] = "FAIL - DID NOT convert Role to proper assoc array";
	}
}


function testConvertRowToModel(){
	global $testResults, $link;

	$testResults[] = "<b>TESTING convertRowToModel()...</b>";

	$da = new RoleDataAccess($link);

	// Create an assoc array that simulates the data coming from a query
	$row = array('user_role_id' => 1, 'user_role_name' => "Test Role", 'user_role_desc' => "This is a test role");
	
	$actualResult = $da->convertRowToModel($row);
	
	// Create a Role model that is equivalent to what we expect to be returned by convertRowToModel
	$expectedResult = new Role([
		'id' => 1,
		'name' => "Test Role",
		'description' => "This is a test role"
	]);

	if($actualResult->equals($expectedResult)){
		$testResults[] = "PASS - Converted row (assoc array) to Role";
	}else{
		$testResults[] = "FAIL - DID NOT Convert row (assoc array) to Role";
	}
}


function testGetAll(){
	global $testResults, $link;
	$testResults[] = "<b>TESTING getAll()...</b>";

	$da = new RoleDataAccess($link);
	$actualResult = $da->getAll();

	//$testResults[] = print_r($actualResult, true);
	//var_dump($actualResult);//die();
	//print_r($actualResult);die();

	
	$expectedResult = [
		new Role(["id" => 1, "name" => "Standard User", "description" => "Normal user with no special permissions"]),
		new Role(["id" => 2, "name" => "Admin", "description" => "Extra permissions"])
	];

	// Note that I defined modelArraysAreEqual() in the create-test-database.php file so it could be used in any
	// data access test (smells, but I wasn't sure where to put it!)
	if(modelArraysAreEqual($actualResult, $expectedResult)){
		$testResults[] = "PASS - getAll() returned the expected result";
	}else{
		$testResults[] = "FAIL - getAll() DID NOT return the expected result";
	}
	
}


function testGetById(){
	global $testResults, $link;
	$testResults[] = "<b>TESTING getById()...</b>";

	// We need an instance of a UserDataAccess object so that we can call the method we want to test
	$da = new RoleDataAccess($link);
	$actualResult = $da->getById(1);
	//var_dump($actualResult);die();

	$expectedResult = new Role(["id" => 1, "name" => "Standard User", "description" => "Normal user with no special permissions"]);

	if($actualResult->equals($expectedResult)){
		$testResults[] = "PASS - getById() returned the expected result";
	}else{
		$testResults[] = "FAIL - getById() DID NOT return the expected result";
	}

}

function testInsert(){
	global $testResults, $link;
	$testResults[] = "<b>TESTING insert()...</b>";

	$da = new RoleDataAccess($link);

	$options = array(
		'name' => "NEW Test Role",
		'description' => "This is a test role"
	);

	$actualResult = $da->insert(new Role($options));
	//var_dump($actualResult);die();

	$expectedResult = new Role(["id" => 3, "name" => "NEW Test Role", "description" => "This is a test role"]);

	if($actualResult->equals($expectedResult)){
		$testResults[] = "PASS - insert() returned the expected result";
	}else{
		$testResults[] = "FAIL - insert() DID NOT return the expected result";
	}
}

function testUpdate(){

	global $testResults, $link;
	$testResults[] = "<b>TESTING update()...</b>";

	$da = new RoleDataAccess($link);

	$options = array(
		'id' => 1,
		'name' => "UPDATED Test Role",
		'description' => "This is an updated test role"
	);

	$actualResult = $da->update(new Role($options));
	//var_dump($actualResult);die();

	$expectedResult = new Role(["id" => 1, "name" => "UPDATED Test Role", "description" => "This is an updated test role"]);
	$actualResult = $da->getById(1);

	if($actualResult->equals($expectedResult)){
		$testResults[] = "PASS - update() returned the expected result";
	}else{
		$testResults[] = "FAIL - update() DID NOT return the expected result";
	}
	
}

function testDelete(){
	// Note sure how we want to handle this
	// If you allow deletes then it can get messy with FK relationships
	// It might be better to set active = no
}

?>