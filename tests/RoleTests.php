<?php

include_once("../includes/models/Role.inc.php");

// we need an associative array to pass into the constructor
$options = [
    "id" => 1,
    "name" => "Test Role",
    "description" => "This role is for testing"
];

// When each test completes, the result message will get added to this array:
$testResults = [];

// This function will run tests on the constructor in the Role class:
testConstructor();

// This function will run tests on the isValid() method:
testIsValid();

// This will echo the test results:
echo(implode("<br>", $testResults));

function testConstructor(){
    global $testResults, $options;
    $testResults[] = "<b>Testing Constructor</b>";

    // Test 1 - Make sure we can instantiate a Role object
    $r = new Role();
    if($r){
        $testResults[] = "PASS - created instance";
    }else{
        $testResults[] = "Fail - did not instance";
    }
    
    // Test 2 - Make sure that the id property gets set properly
    $r = new Role($options);
    if($r->id === 1){
        $testResults[] = "PASS - id set properly";
    }else{
        $testResults[] = "Fail - id NOT set properly";
    }

    // Test 3 - Make sure that the name property gets set properly
    $r = new Role($options);
    if($r->name === "Test Role"){
        $testResults[] = "PASS - name set properly";
    }else{
        $testResults[] = "Fail - name NOT set properly";
    }


    // Test 4 - Make sure that the description property gets set properly
    $r = new Role($options);
    if($r->description === "This role is for testing"){
        $testResults[] = "PASS - description set properly";
    }else{
        $testResults[] = "Fail - description NOT set properly";
    }
}

function testIsValid(){
    global $testResults, $options;
    $testResults[] = "<b>Testing isValid()</b>";

    //isValid() should return false if ID is not numeric
    $r = new Role($options);
    $r->id = ""; // set the id to an INVALID value

    if($r->isValid() === false){
        $testResults[] = "PASS - isValid() returns false when ID is not numeric";
    }else{
        $testResults[] = "FAIL - isValid() DOES NOT return false when ID is not numeric";
    }

    //isValid() should return false if ID is a negative number
    $r = new Role($options);
    $r->id = -1;

    if($r->isValid() === false){
        $testResults[] = "PASS - isValid() returns false when ID is a negative number";
    }else{
        $testResults[] = "FAIL - isValid() DOES NOT return false when ID is a negative number";
    }

    // if the ID is not valid, the validationErrors should contain an ID key
    $r = new Role($options);
    $r->id = -1;
    $r->isValid();
    $errors = $r->getValidationErrors();
    //var_dump($errors);

    if(isset($errors["id"])){
        $testResults[] = "PASS - ID key exists in the validation errors";
    }else{
        $testResults[] = "FAIL - ID key DOES NOT exist in the validation errors";
    }


    // The role name should not be empty
    $r = new Role($options);
    $r->name = "";

    if($r->isValid() === false){
        $testResults[] = "PASS - isValid() returned false when the name is empty";
    }else{
        $testResults[] = "FAIL - isValid() DID NOT return false when the name is empty";
    }

    // The role name should not be more than 30 characters
    $r = new Role($options);
    $r->name = "xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx";

    if($r->isValid() === false){
        $testResults[] = "PASS - isValid() returned false when the name is too long";
    }else{
        $testResults[] = "FAIL - isValid() DID NOT return false when the name is too long";
    }

    // If the role name is empty there should be an error messsage that says 'Role cannot be empty'
    $r = new Role($options);
    $r->name = "";
    $r->isValid();
    $errors = $r->getValidationErrors();

    if(isset($errors["name"]) && $errors['name'] === "Role cannot be empty"){
        $testResults[] = "PASS - the error message for the name was correct when name was empty";
    }else{
        $testResults[] = "FAIL - the error message for the name was NOT correct when name was empty";
    }

    // If the role name is more than 30 characters there should be an error messsage that says 'Role cannot be more than 30 characters'
    $r = new Role($options);
    $r->name = "xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx";
    $r->isValid();
    $errors = $r->getValidationErrors();

    if(isset($errors["name"]) && $errors['name'] === "Role cannot be more than 30 characters"){
        $testResults[] = "PASS - the error message for the name was correct when the name was too long";
    }else{
        $testResults[] = "FAIL - the error message for the name was NOT correct when the name was too long";
    }
    
}
?>