<?php
/*
NOTE - The database column names do not map directly to the property names of the User model object
		
		User Model 		users table columns
		-----------------------------------
		id				user_id
		firstName		user_first_name
		lastName		user_last_name
		email			user_email
		roleId			user_role
		password		user_password
		salt			user_salt
		active			user_active

This is the purpose of the methods convertModelToRow() 
and convertRowToModel(). Note that both of these functions
also serve another purpose, they scrub the data to prevent hacks
(sometimes it makes sense to break the single responsibility rule!)

*/


require_once("DataAccess.inc.php");
include_once(__DIR__ . "/../models/User.inc.php"); // I had problems including this file, not sure why!


class UserDataAccess extends DataAccess{
	
	/**
	* Constructor
	* @param $link 		The connection to the database
	*/
	function __construct($link){
		parent::__construct($link);
	}

	/**
	* Converts a model object into an assoc array and sets the keys
	* to the proper names. For example a $user->id is converted to $row['user_id']
	* The data should also be scrubbed to prevent SQL injection attacks
	*
	* @param {User} $user 
	* @return {array}
	*/
	function convertModelToRow($user){
		return []; // comment this out when you implement the code for this method		
	}

	/**
	* Converts a row from the database to a model object
	* And scrubs the data to prevent XSS attacks
	*
	* @param {array} $row
	* @return {User}		Returns a subclass of a Model 
	*/
	function convertRowToModel($row){
		// Note that if you have a column that allows some HMTL content
		// then use $this->sanitizeHTML() instead of htmlentities()

		return new User(); // comment this out when you implement the code for this method
	}

	/**
	* Gets all rows from a table in the database
	* @param {assoc array} 	This optional param would allow you to filter the result set
	* 						For example, you could use it to somehow add a WHERE claus to the query
	* 
	* @return {array}		Returns an array of model objects
	*/
	function getAll($args = null){
		return []; // comment this out when you implement the code for this method
	}


	/**
	* Gets a row from the database by it's id
	* @param {number} $id 	The id of the item to get from a row in the database
	* @return {User}		Returns an instance of a model object 
	*/
	function getById($id){
		return new User(); // comment this out when you implement the code for this method
	}

	
	/**
	 * Inserts a user into the database
	 * @param {User} $user
	 * @return {User}		Returns a User including the newly assignd user id			
	 */
	function insert($user){
		return new User(); // comment this out when you implement the code for this method
	}

	/**
	 * Updates a user in the database
	 * @param {User} $user
	 * @return {boolean}				Returns true if the update succeeds			
	 */
	function update($user){
        return false; // comment this out when you implement the code for this method
	}


	/**
    * Deletes a row from a table in the database
    * @param {number} 	The id of the row to delete
    * @return {boolean}	Returns true if the row was sucessfully deleted, false otherwise
    */
	function delete($args = null){
		return null; // comment this out when you implement the code for this method
	}

	
	// Note - we'll add methods for authenticating users and handling passwords

}