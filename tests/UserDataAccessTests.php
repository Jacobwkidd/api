<?php
include_once(__DIR__ . "/../includes/dataaccess/UserDataAccess.inc.php");
include_once(__DIR__ . "/../includes/models/User.inc.php");
include_once("create-test-database.php");





$testResults = array();

$options = array(
	'id' => 1,
	'firstName' => "Bob",
	'lastName' => "Smith",
	'email' => "bob@smith.com",
	'roleId' => 1,
	'password' => "opensesame",
	'active' => true,
	'salt' => "xxx"
);

//TEST convertRowToModel
$user = new User($options);
$da = new UserDataAccess($link);
$row = $da->convertModelToRow($user);
// var_dump($row);


// TEST convertModelToRow
// $role = new Role($options);
// $row = $da->ConvertModelToRow($role);
// var_dump($row);

die();
// You'll have to run all these tests for each of your data access classes
testConstructor();
testConvertModelToRow();
testConvertRowToModel();
testGetAll();
testGetById();
testInsert(); 
testUpdate(); 
testDelete(); 

echo(implode("<br>", $testResults));


// NOTE: Many of these tests will pass if you haven't already created the User model
// which is a problem (the )

function testConstructor(){

	global $testResults, $link;
	$testResults[] = "<b>TESTING constructor...</b>";

	// TEST - create an instance of the ConcactDataAccess class
	$da = new UserDataAccess($link);
	
	if($da){
		$testResults[] = "PASS - Created instance of UserDataAccess";
	}else{
		$testResults[] = "FAIL - DID NOT creat instance of UserDataAccess";
	}

	// Test - an exception should be thrown if the $link param is not a valid link
	try{
		$da = new UserDataAccess("BLAHHHHHH");
		$testResults[] = "FAIL - Exception is NOT thrown when link param is invalid";
	}catch(Exception $e){
		$testResults[] = "PASS - Exception is thrown when link param is invalid";
	}
}

function testConvertModelToRow(){
	global $testResults, $link;

	$testResults[] = "<b>TESTING convertModelToRow()...</b>";

	$da = new UserDataAccess($link);

	$options = array(
		'id' => 1,
		'firstName' => "Bob",
		'lastName' => "Smith",
		'email' => "bob@smith.com",
		'roleId' => "1",
		'password' => "opensesame",
		'salt' => "xxx",
		'active' => true
	);

	$u = new User($options);

	$expectedResult = array(
		'user_id' => 1,
		'user_first_name' => "Bob",
		'user_last_name' => "Smith",
		'user_email' => "bob@smith.com",
		'user_role' => "1",
		'user_password' => "opensesame",
		'user_salt' => "xxx",
		'user_active' => true
	);
	
	$actualResult = $da->convertModelToRow($u);

	// var_dump($actualResult);
	// var_dump($expectedResult);
	// die();

	if(empty(array_diff_assoc($expectedResult, $actualResult))){
		$testResults[] = "PASS - Converted User to proper assoc array";
	}else{
		$testResults[] = "FAIL - DID NOT convert User to proper assoc array";
	}
}


function testConvertRowToModel(){
	global $testResults, $link;

	$testResults[] = "<b>TESTING convertRowToModel()...</b>";

	$da = new UserDataAccess($link);

	$row = array(
		'user_id' => 1,
		'user_first_name' => "Bob",
		'user_last_name' => "Smith",
		'user_email' => "bob@smith.com",
		'user_role' => "1",
		'user_password' => "opensesame",
		'user_salt' => "xxx",
		'user_active' => true
	);
	
	$actualResult = $da->convertRowToModel($row);
	
	$expectedResult = new User([
		'id' => 1,
		'firstName' => "Bob",
		'lastName' => "Smith",
		'email' => "bob@smith.com",
		'roleId' => "1",
		'password' => "opensesame",
		'salt' => "xxx",
		'active' => true
	]);

	// var_dump($actualResult);
	// var_dump($expectedResult);
	// die();

	if($actualResult->equals($expectedResult)){
		$testResults[] = "PASS - Converted row (assoc array) to User";
	}else{
		$testResults[] = "FAIL - DID NOT Convert row (assoc array) to User";
	}
}


function testGetAll(){
	global $testResults, $link;
	$testResults[] = "<b>TESTING getAll()...</b>";

	$da = new UserDataAccess($link);
	$actualResult = $da->getAll();
	//var_dump($actualResult); die();

	$expectedResult = [
		new User(['id' => 1, 'firstName' => "John", 'lastName' => "Doe",'email' => "john@doe.com",	'roleId' => "1",'password' => "opensesame",	'salt' => "xxx",'active' => true]),
		new User(['id' => 2, 'firstName' => "Jane", 'lastName' => "Anderson",'email' => "jane@doe.com",	'roleId' => "2",'password' => "letmein",'salt' => "xxx",'active' => true]),
		new User(['id' => 3, 'firstName' => "Bob", 'lastName' => "Smith",'email' => "bob@smith.com", 'roleId' => "2",'password' => "test", 'salt' => "xxx",'active' => false])
	];


	// var_dump($actualResult);
	// var_dump($expectedResult);
	// die();
	

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
	$da = new UserDataAccess($link);
	$actualResult = $da->getById(1);
	//var_dump($actualResult); die();

	$expectedResult = new User(['id' => 1, 'firstName' => "John", 'lastName' => "Doe",'email' => "john@doe.com",	'roleId' => "1",'password' => "opensesame",	'salt' => "xxx",'active' => true]);

	// var_dump($actualResult);
	// var_dump($expectedResult);
	// die();


	if($actualResult->equals($expectedResult)){
		$testResults[] = "PASS - getById() returned the expected result";
	}else{
		$testResults[] = "FAIL - getById() DID NOT return the expected result";
	}
}

function testInsert(){
	global $testResults, $link;
	$testResults[] = "<b>TESTING insert()...</b>";

	$da = new UserDataAccess($link);

	$options = array(
		//'id' => 1,
		'firstName' => "Chris",
		'lastName' => "Jones",
		'email' => "chris@jones.com", //email must be unique
		'roleId' => "1",
		'password' => "test",
		'salt' => "xxx",
		'active' => true
	);

	$u = new User($options);

	$actualResult = $da->insert($u);
	//var_dump($actualResult); die();

	$expectedResult = new User(['id' => 4, 'firstName' => "Chris", 'lastName' => "Jones",'email' => "chris@jones.com",	'roleId' => "1",'password' => "test",	'salt' => "xxx",'active' => true]);

	// var_dump($actualResult);
	// var_dump($expectedResult);
	// die();

	if($actualResult->equals($expectedResult)){
		$testResults[] = "PASS - insert() returned the expected result";
	}else{
		$testResults[] = "FAIL - insert() DID NOT return the expected result";
	}

	/*
	// The insert method should throw an error if you try to 
	// insert a user_email that already exists in the database
	try{
		$newUser = $da->insert($u);
		die("FAIL - Did not throw error when user with duplicate email is inserted");
	}catch(Exception $e){
		die("PASS - Here's the exception that was thrown: " . $e->getMessage());
	}
	*/
	
}

function testUpdate(){

	global $testResults, $link;
	$testResults[] = "<b>TESTING update()...</b>";

	$da = new UserDataAccess($link);

	$options = array(
		'id' => 1,
		'firstName' => "Bob Updated",
		'lastName' => "Smith Updated",
		'email' => "bob-updated@smith.com", //email must be unique
		'roleId' => "1",
		'password' => "opensesameUpdated",
		'salt' => "xxx",
		'active' => true
	);

	$u = new User($options);

	$actualResult = $da->update($u);
	//var_dump($actualResult);die();

	if($actualResult === true){
		$testResults[] = "PASS - update() returned the expected result";
	}else{
		$testResults[] = "FAIL - update() DID NOT return the expected result";
	}

	/*
	$expectedResult = new User(['id' => 1, 'firstName' => "Bob Updated", 'lastName' => "Smith Updated",'email' => "bob-updated@smith.com",	'roleId' => "1",'password' => "opensesameUpdated", 'salt' => "xxx",'active' => true]);

	// var_dump($actualResult);
	// var_dump($expectedResult);
	// die();

	if($actualResult->equals($expectedResult)){
		$testResults[] = "PASS - update() returned the expected result";
	}else{
		$testResults[] = "FAIL - update() DID NOT return the expected result";
	}
	*/
	
	/*
	// The update method should throw an error if a different user has the same email as the one being updated.
	$u->email = "jane@doe.com";
	try{
		$da->update($u);
		die("FAIL - Did not throw error when user with duplicate email is updated");
	}catch(Exception $e){
		die("PASS - Here's the exception that was thrown: " . $e->getMessage());
	}
	*/

}

function testDelete(){
	// Note sure how we want to handle this
	// If you allow deletes then it can get messy with FK relationships
	// It might be better to set active = no
}


?>