<?php
include_once("Model.inc.php");

class User extends Model{

	// INSTANCE VARIABLES
	public $id;
	public $firstName;
	public $lastName;
	public $email;
	public $roleId;
	public $password;
	public $salt;
	public $active;

	
	/**
	 * Constructor for creating Contact model objects
	 * @param {asoociative array} $args 	key value pairs that map to the instance variables
	 *										NOTE: the $args param is OPTIONAL, if it is not passed in
	 * 										The default will be an empty array: []									
	 */
	public function __construct($args = []){
		
	}

	/**
	 * Validates the state of this object. 
	 * Returns true if it is valid, false otherwise.
	 * For any properties that are not valid, a key will be added
	 * to the validationErrors array and it's value will be a description of the error.
	 * 
	 * @return {boolean}
	 */
	public function isValid(){
		
		$valid = true;
		$this->validationErrors = [];
		
		// Validate the ID:
		// It must be a number equal to or greater than 0 
		// (0 is valid becuase it will indicate that we are inserting a new user)
		// If the ID is not valid then you should add an 'id' key to $this->validationErrors with a value of "ID is not valid"
		


		// validate firstName
		// firstName must not be empty
		// firstName must not be longer than 30 characters
		// If the firstName is empty, you should add a 'firstName' key to $this->validationErrors with a value of "First Name is required"
		// If the firstName is longer than 30 characters, you should add a 'firstName' key to $this->validationErrors with a value of "First Name must be 30 characters or less"
		


		// validate lastName
		// lastName must not be empty
		// lastName must not be longer than 30 characters
		// If the lastName is empty, you should add a 'lastName' key to $this->validationErrors with a value of "Last Name is required"
		// If the lastName is longer than 30 characters, you should add a 'lastName' key to $this->validationErrors with a value of "Last Name must be 30 characters or less"
		


		// validate email
		// email must not be empty
		// email must be a valid email address (look up the filter_var() function in PHP)
		// email must not be more than 100 characters
		// If email is empty, you should add an 'email' key to $this->validationErrors with a value of "Email is required"
		// If email is not valid, you should add an 'email' key to $this->validationErrors with a value of "The email address is not valid"
		// If email is longer than 100 characters, you should add an 'email' key to $this->validationErrors with a value of "Email must be 100 characters or less"
		


		// validate roleId
		// roleId should be a 1 or a 2 (it should be a number, not a string)
		// Side Note: 1 = Standard User and 2 = Admin 
		
        

		// validate password
		// If the user id is 0 (which means a new user is about to be inserted), then the password must not be empty.
		// If the user id is 0 and the password is empty, then you should add a 'password' key 
		// to $this->validationErrors with a value of "Password is required";
		// Side NOTE: Ideally we'd be enforcing a strong password
		


		// salt does not need validation (we'll talk about it later)



		// validate active, it must be either true or false
		// SIDE NOTE: when a user is not active, they will not be able to log in
		



		return $valid;
	}

}