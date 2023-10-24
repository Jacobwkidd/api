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
}

function testIsValid(){
    global $testResults, $options;
    $testResults[] = "<b>Testing isValid()</b>";

    
}
?>