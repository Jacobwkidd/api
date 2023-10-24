<?php
include_once("../includes/models/Model.inc.php");

class TestModel extends Model{

	public $firstName = "";

	public function __construct($args = []){

		// NOTE that in PHP we use bracket notation for associative arrays
		$this->firstName = $args['firstName'] ?? "";

	}

	function isValid(){
		// if isValid returns false, then it should populate the validationErrors array
		// with key/value pairs (the key is the name of the models property and the value 
		// is a string that explains why the property is not valid)
		
		$valid = true;
		$this->validationErrors =  []; // Make sure to clear out any old error messages

		if(empty($this->firstName)){
			$this->validationErrors["firstName"] = "First name is required";
			$valid = false;
		}

		// TODO: Add code here that checks the length of the firstName property
		// If the firstName is longer than 30 characters, then the error message 
		// should be 'First name must be than 30 characters or less'
		if(strlen($this->firstName) > 30){
			$this->validationErrors["firstName"] = "First name must be than 30 characters or less";
			$valid = false;
		} 
	
		return $valid;
	}

}


$testResults = array();

// RUN THE TEST FUNCTIONS HERE

$testResults[] = "<h3>Model Tests</h3>";
testConstructor();
testIsValid();
testToArray();
testToJSON();
testToXML();
testEquals();
echo(implode("<br>", $testResults));



function testConstructor(){
	global $testResults;
	$testResults[] = "<b>Testing constructor...</b>";
	
	$args = ["firstName" => "Bob"];
	$tm = new TestModel($args);
	
	if($tm->firstName == "Bob"){
		$testResults[] = "PASS - Constructor sets firstName properly";
	}else{
		$testResults[] = "FAIL - Constructor does NOT set firstName properly";
	}

}

function testIsValid(){
	global $testResults;
	$testResults[] = "<b>Testing isValid()</b>";

	// Test 1
	// isValid() should return true when firstName is set to a valid name such as "Bob"
	$tm = new TestModel();
	$tm->firstName = "Bob";
	if($tm->isValid() === true){
		$testResults[] = "PASS - isValid() returns true when firstName is Bob";
	}else{
		$testResults[] = "FAIL - isValid() DOES NOT return tru when firstName is Bob";
	}
	
	// Test 2
	// isValid() should return false when firstName is empty
	$tm = new TestModel();
	// note that $tm is now a new model object, and firstName has not been initialized
	
	if($tm->isValid() === false){
		$testResults[] = "PASS - isValid() returns false when firstName is empty";
	}else{
		$testResults[] = "FAIL - isValid() DOES NOT return false when firstName is empty";
	}

	// Tests 3
	// When the firstName is not valid, there will be 'firstName' key in the validationErrors array
	$tm = new TestModel();
	$tm->isValid();
	$errors = $tm->getValidationErrors();

	if(isset($errors['firstName'])){
		$testResults[] = "PASS - validationErrors includes key for firstName";
	}else{
		$testResults[] = "FAIL - validationErrors does NOT include key for firstName";
	}


	
	// Test 4
	//If firstName is empty, then the error message should be "First name is required"
	$tm = new TestModel();
	$tm->isValid();
	$errors = $tm->getValidationErrors();
	//var_dump($errors);

	if($errors['firstName'] === "First name is required"){
		$testResults[] = "PASS - The firstName key is set to 'First name is required'";
	}else{
		$testResults[] = "Fail - The firstName key is NOT set to 'First name is required'";
	}
	

	function testToArray(){
		global $testResults, $options;
		$testResults[] = "<b>Testing toArray()...</b>";
	

		$args = ["firstName" => "Bob"];

		$tm = new TestModel($args);
		$result = $tm->toArray();
		//var_dump($result);
		//var_dump(array_diff_assoc($args, $result));
		//var_dump($args === $result); // THIS ONE BLEW MY MIND!


		if(empty(array_diff_assoc($args, $result))){
			$testResults[] = "PASS - toArray() returns the expected result";
		}else{
			$testResults[] = "FAIL - toArray() DOES NOT return the expected result";
		}
		
	}

	function testToJSON(){
		global $testResults, $options;
		$testResults[] = "<b>Testing toJSON...</b>";

		$args = ["firstName" => "Bob"];

		$tm = new TestModel($args);
		$expect = '{"firstName":"Bob"}';
		$result = $tm->toJSON();
		//var_dump($result); 	// var_dump() shows the data type and length
		//die($result); // die() shows the raw data

		if($result == $expect){
			$testResults[] = "PASS - Convert toJSON returns expected result";
		}else{
			$testResults[] = "FAIL - Convert toJSON DOES NOT return the expected result";
		}

	}

	function testToXML(){
		global $testResults, $options;
		$testResults[] = "<b>Testing to XML..</b>";

		$args = ["firstName" => "Bob"];

		$tm = new TestModel($args);
		$result = $tm->toXML();
		//die($tm->toXML());
		$expect = "<testmodel><firstName>Bob</firstName></testmodel>";

		if($result == $expect){
			$testResults[] = "PASS - Convert toXML returns expected result";
		}else{
			$testResults[] = "FAIL - Convert toXML DOES NOT return the expected result";
		}

	}


	function testEquals(){
		global $testResults, $options;
		$testResults[] = "<b>Testing equals()...</b>";

		$tm1 = new TestModel(["firstName" => "Bob"]);
		$tm2 = new TestModel(["firstName" => "Bob"]);
		
		if($tm1->equals($tm2)){
			$testResults[] = "PASS - equals() returns true for two equivalent models";
		}else{
			$testResults[] = "FAIL - equals() DOES NOT return true for two equivalent models";
		}

		$tm1 = new TestModel(["firstName" => "Bob"]);
		$tm2 = new TestModel(["firstName" => "Sally"]);
		
		if($tm1->equals($tm2) === false){
			$testResults[] = "PASS - equals() returns false for two non-equivalent models";
		}else{
			$testResults[] = "FAIL - equals() DOES NOT return false for two non-equivalent models";
		}
	}
	
	

}







