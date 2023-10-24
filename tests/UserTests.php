<?php
include_once("../includes/models/User.inc.php");


// we'll use these options to create valid User in our tests
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


/*
// I put this code here to test that the inherited, protected members (validationErrs)
// does not appear in the JSON
$u = new User($options);
$u->firstName = "";
$u->isValid();
echo(json_encode($u));
die();
*/

// This array will store the test results
$testResults = array();

// run the test functions
testConstructor();
testIsValid();

// display the results
echo(implode("<br>",$testResults));


function testConstructor(){
	global $testResults, $options;
	$testResults[] = "<b>Testing the constructor...</b>";

	// TEST - Make sure we can create a User object
	$u = new User();
	
	if($u){
		$testResults[] = "PASS - Created instance of User model object";
	}else{
		$testResults[] = "FAIL - DID NOT creat instance of a User model object";
	}

	// TEST - Make sure the firstName property gets set correctly
	$u = new User($options);

	if($u->id === 1){
		$testResults[] = "PASS - Set id properly";
	}else{
		$testResults[] = "FAIL - DID NOT set id properly";
	}

	if($u->firstName == "Bob"){
		$testResults[] = "PASS - Set firstName properly";
	}else{
		$testResults[] = "FAIL - DID NOT set firstName properly";
	}

	if($u->lastName == "Smith"){
		$testResults[] = "PASS - Set lastName properly";
	}else{
		$testResults[] = "FAIL - DID NOT set lastName properly";
	}

	if($u->email == "bob@smith.com"){
		$testResults[] = "PASS - Set email properly";
	}else{
		$testResults[] = "FAIL - DID NOT set email properly";
	}

	if($u->roleId === 1){
		$testResults[] = "PASS - Set roleId properly";
	}else{
		$testResults[] = "FAIL - DID NOT set roleId properly";
	}

	if($u->password == "opensesame"){
		$testResults[] = "PASS - Set password properly";
	}else{
		$testResults[] = "FAIL - DID NOT set password properly";
	}

	if($u->active == "yes"){
		$testResults[] = "PASS - Set active properly";
	}else{
		$testResults[] = "FAIL - DID NOT set active properly";
	}
}


function testIsValid(){
	global $testResults, $options;
	$testResults[] = "<b>Testing isValid()...</b>";
		
	// isValid() should return false when the ID is not numeric
	$u = new User($options);
	$u->id = "";

	if($u->isValid() === false){
		$testResults[] = "PASS - isValid() returns false when ID is not numeric";
	}else{
		$testResults[] = "FAIL - isValid() DOES NOT return false when ID is not numeric";
	}

	// isValid() should return false when the ID is a negative number
	$u->id = -1;
	if($u->isValid() === false){
		$testResults[] = "PASS - isValid() returns false when ID is a negative number";
	}else{
		$testResults[] = "FAIL - isValid() DOES NOT return false when ID is a negative number";
	}

	// If the ID is not valid, then the validation errors array should include an 'id' key
	$errors = $u->getValidationErrors();
	if(isset($errors['id'])){
		$testResults[] = "PASS - validationErrors includes key for ID";
	}else{
		$testResults[] = "FAIL - validationErrors does NOT include key for ID";
	}
	
	// isValid() should return false when the firstName is empty
	$u = new User($options);
	$u->firstName = "";
	if($u->isValid() === false){
		$testResults[] = "PASS - isValid() returns false when firstName is empty";
	}else{
		$testResults[] = "FAIL - isValid() DOES NOT return false when firstName is empty";
	}

	// When the firstName is empty, the error message for it should be 'First Name is required'
	$errors = $u->getValidationErrors();
	if(isset($errors['firstName']) && $errors['firstName'] == "First Name is required"){
		$testResults[] = "PASS - validation message is 'First Name is required'";
	}else{
		$testResults[] = "FAIL - validation message is NOT 'First Name is required'";
	}

	// When the firstName is longer than 30 characters, isValid() should return false
	$u = new User($options);
	$u->firstName = "xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx";
	if($u->isValid() === false){
		$testResults[] = "PASS - isValid() returns false when firstName is too long";
	}else{
		$testResults[] = "FAIL - isValid() DOES NOT return false when firstName is too long";
	}

	// When the firstName is longer than 30 characters, the error message should be 'First Name must be 30 characters or less'
	$errors = $u->getValidationErrors();
	if(isset($errors['firstName']) && $errors['firstName'] == "First Name must be 30 characters or less"){
		$testResults[] = "PASS - Validation message is 'First Name must be 30 characters or less'";
	}else{
		$testResults[] = "FAIL - Validation message is NOT 'First Name must be 30 characters or less'";
	}

	// isValid() should return false when the lastName is empty
	$u = new User($options);
	$u->lastName = "";
	if($u->isValid() === false){
		$testResults[] = "PASS - isValid() returns false when lastName is empty";
	}else{
		$testResults[] = "FAIL - isValid() DOES NOT return false when lastName is empty";
	}

	// When the lastName is empty, the error message for it should be 'Last Name is required'
	$errors = $u->getValidationErrors();
	if(isset($errors['lastName']) && $errors['lastName'] == "Last Name is required"){
		$testResults[] = "PASS - validation message is 'Last Name is required'";
	}else{
		$testResults[] = "FAIL - validation message is NOT 'Last Name is required'";
	}

	// When the lastName is longer than 30 characters, isValid() should return false
	$u = new User($options);
	$u->lastName = "xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx";
	if($u->isValid() === false){
		$testResults[] = "PASS - isValid() returns false when lastName is too long";
	}else{
		$testResults[] = "FAIL - isValid() DOES NOT return false when lastName is too long";
	}

	// When the lastName is longer than 30 characters, the error message should be 'Last Name must be 30 characters or less'
	$errors = $u->getValidationErrors();
	if(isset($errors['lastName']) && $errors['lastName'] == "Last Name must be 30 characters or less"){
		$testResults[] = "PASS - Validation message is 'Last Name must be 30 characters or less'";
	}else{
		$testResults[] = "FAIL - Validation message is NOT 'Last Name must be 30 characters or less'";
	}

	// isValid() should return false when the email is empty
	$u = new User($options);
	$u->email = "";
	if($u->isValid() === false){
		$testResults[] = "PASS - isValid() returns false when email is empty";
	}else{
		$testResults[] = "FAIL - isValid() DOES NOT return false when email is empty";
	}

	// When the email is empty, the error message for it should be 'Email is required'
	$errors = $u->getValidationErrors();
	if(isset($errors['email']) && $errors['email'] == "Email is required"){
		$testResults[] = "PASS - validation message is 'Email is required'";
	}else{
		$testResults[] = "FAIL - validation message is NOT 'Email is required'";
	}


	// isValid() should return false when the email is not a valid email address
	$u = new User($options);
	$u->email = "x";
	if($u->isValid() === false){
		$testResults[] = "PASS - isValid() returns false when email is not a valid email address";
	}else{
		$testResults[] = "FAIL - isValid() DOES NOT return false when email is not a valid email address";
	}

	// When the email is not a valid email address, the error message for it should be 'The email address is not valid'
	$errors = $u->getValidationErrors();
	if(isset($errors['email']) && $errors['email'] == "The email address is not valid"){
		$testResults[] = "PASS - validation message is 'The email address is not valid'";
	}else{
		$testResults[] = "FAIL - validation message is NOT 'The email address is not valid'";
	}

	// When the email is longer than 100 characters, isValid() should return false
	$u = new User($options);
	$u->email = "xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx@xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx.com";
	if($u->isValid() === false){
		$testResults[] = "PASS - isValid() returns false when email is too long";
	}else{
		$testResults[] = "FAIL - isValid() DOES NOT return false when email is too long";
	}

	// When the email is longer than 100 characters, the error message should be 'Email must be 100 characters or less'
	$errors = $u->getValidationErrors();
	if(isset($errors['email']) && $errors['email'] == "Email must be 100 characters or less"){
		$testResults[] = "PASS - Validation message is 'Email must be 100 characters or less'";
	}else{
		$testResults[] = "FAIL - Validation message is NOT 'Email must be 100 characters or less'";
	}

	// isValid() should return false when the roleId is not valid (the roleId should be either 1 or 2)
	$u = new User($options);
	$u->roleId = "";

	if($u->isValid() === false){
		$testResults[] = "PASS - isValid() returns false when the roleId is not valid";
	}else{
		$testResults[] = "FAIL - isValid() DOES NOT return false when the roleId is not valid";
	}

	// When the roleId is not valid, the error message should be 'The role is not valid'
	$errors = $u->getValidationErrors();
	if(isset($errors['roleId']) && $errors['roleId'] == "The role is not valid"){
		$testResults[] = "PASS - Validation message is 'The role is not valid'";
	}else{
		$testResults[] = "FAIL - Validation message is NOT 'The role is not valid'";
	}

	// isValid() should return false if the user id is 0 AND the password is empty
	$u = new User($options);
	$u->id = 0;
	$u->password = "";

	if($u->isValid() === false){
		$testResults[] = "PASS - isValid() returns false when the user id is 0 AND the password is empty";
	}else{
		$testResults[] = "FAIL - isValid() DOES NOT return false when the user id is 0 AND the password is empty";
	}

	// When the password is not valid, the error message should be 'The role is not valid'
	$errors = $u->getValidationErrors();
	if(isset($errors['password']) && $errors['password'] == "Password is required"){
		$testResults[] = "PASS - Validation message is 'Password is required'";
	}else{
		$testResults[] = "FAIL - Validation message is NOT 'Password is required'";
	}

	// isValid() should return false when active is not valid (the active should be either 'yes' or 'no')
	$u = new User($options);
	$u->active = "";

	if($u->isValid() === false){
		$testResults[] = "PASS - isValid() returns false when the active is not valid";
	}else{
		$testResults[] = "FAIL - isValid() DOES NOT return false when the active is not valid";
	}

	// When the active is not valid, the error message should be 'The active setting is not valid'
	$errors = $u->getValidationErrors();
	if(isset($errors['active']) && $errors['active'] == "The active setting is not valid"){
		$testResults[] = "PASS - Validation message is 'The active setting is not valid'";
	}else{
		$testResults[] = "FAIL - Validation message is NOT 'The active setting is not valid'";
	}

	// When the user is valid, isValid() should return true
	$u = new User($options);

	if($u->isValid() === true){
		$testResults[] = "PASS - isValid() returns true when the user is valid";
	}else{
		$testResults[] = "FAIL - isValid() DOES NOT return true when the user is valid";
	}

	// When the user is valid, the validation errors should be an empty array
	$errors = $u->getValidationErrors();
	if(empty($errors)){
		$testResults[] = "PASS - There are no error messages when the user is valid";
	}else{
		$testResults[] = "FAIL - There ARE error messages when the user is valid";
	}

}





