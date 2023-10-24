<?php

// Students will need to add the insert and create statements for their tables below
// (Add them to the $sql variable) 

$testServer = "localhost";
$testLogin = "root";
$testPassword = "test"; // set the password
$testDB = "api_test_db"; 
$link = mysqli_connect($testServer, $testLogin, $testPassword);

if(!$link){
	die("Unable to connect to test db");
}

$sql = "
	DROP DATABASE IF EXISTS api_test_db;

	CREATE DATABASE api_test_db;

	USE api_test_db;

	CREATE TABLE `user_roles` (
	  `user_role_id` int(11) NOT NULL PRIMARY KEY AUTO_INCREMENT,
	  `user_role_name` varchar(30) NOT NULL,
	  `user_role_desc` varchar(200) NOT NULL
	);

	INSERT INTO `user_roles` (`user_role_id`, `user_role_name`, `user_role_desc`) VALUES
	(1, 'Standard User', 'Normal user with no special permissions'),
	(2, 'Admin', 'Extra permissions');

	CREATE TABLE users (
	  user_id int(11) NOT NULL PRIMARY KEY AUTO_INCREMENT,
	  user_first_name varchar(30) NOT NULL,
	  user_last_name varchar(30) NOT NULL,
	  user_email varchar(100) NOT NULL UNIQUE,
	  user_password char(255) NOT NULL,
	  user_salt char(32) NOT NULL,
	  user_role INT NOT NULL DEFAULT '1',
	  user_active boolean NOT NULL DEFAULT true,
	  FOREIGN KEY (user_role) REFERENCES user_roles(user_role_id)
	);

	INSERT INTO users (user_first_name,user_last_name, user_email, user_password, user_salt, user_role, user_active) VALUES 
		('John', 'Doe','john@doe.com', 'opensesame', 'xxx', '1', true),
		('Jane', 'Anderson','jane@doe.com', 'letmein', 'xxx', '2', true),
		('Bob', 'Smith','bob@smith.com', 'test', 'xxx', '2', false);

";


mysqli_multi_query($link, $sql) or die(mysqli_error($link));

// You have to close the connection (and pause) after you do a multi query:
mysqli_close($link);
sleep(1);
$link = mysqli_connect($testServer, $testLogin, $testPassword, $testDB);
echo("<b>TEST DATABASE SUCESSFULLY RECREATED</b><br>");

// This function can be used in tests to see
// if an actual array (of model objects) is the same
// as the expected array
function modelArraysAreEqual($array1, $array2){
	
	function compare_models($a, $b){
		if($a->equals($b)){
			return 0;
		}else{
			return 1;
		}
	}

	$result = array_udiff($array1, $array2, "compare_models");

	//var_dump($array1);
	//var_dump($array2);
	//var_dump($result); die();
	return empty($result) ? true : false;
}