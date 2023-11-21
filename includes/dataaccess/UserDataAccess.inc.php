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
		$row = [];

		$row["user_id"] = $user->id;
		$row["user_first_name"] = $user->firstName;
		$row["user_last_name"] = $user->lastName;
		$row["user_email"] = $user->email;
		$row["user_role"] = $user->roleId;
		$row["user_password"] = $user->password;
		$row["user_salt"] = $user->salt;
		$row["user_active"] = $user->active;

		return $row;
	
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

		/*
		id				user_id
		firstName		user_first_name
		lastName		user_last_name
		email			user_email
		roleId			user_role
		password		user_password
		salt			user_salt
		active			user_active 
		*/
		$role = new User();
		$role->id = $row['user_id'];
		$role->firstName = $row['user_first_name'];
		$role->lastName = $row['user_last_name'];
		$role->email = $row['user_email'];
		$role->roleId = $row['user_role'];
		$role->password = $row['user_password'];
		$role->salt = $row['user_salt'];
		$role->active = $row['user_active'];
  
      	return $role;

		// return new User(); // comment this out when you implement the code for this method
	}

	/**
	* Gets all rows from a table in the database
	* @param {assoc array} 	This optional param would allow you to filter the result set
	* 						For example, you could use it to somehow add a WHERE claus to the query
	* 
	* @return {array}		Returns an array of model objects
	*/
	function getAll($args = null){
		$qStr = "SELECT
						user_id,
						user_first_name,
						user_last_name,
						user_email,
						user_role,
						user_password,
						user_salt,
						user_active
				FROM users";

		$result = mysqli_query($this->link, $qStr) or $this->handleError(mysqli_error($this->link));

		$allRoles = [];

		while($row = mysqli_fetch_assoc($result)){
		$allRoles[] = $this->convertRowToModel($row);
		}

		return $allRoles;//code for this method
	}


	/**
	* Gets a row from the database by it's id
	* @param {number} $id 	The id of the item to get from a row in the database
	* @return {User}		Returns an instance of a model object 
	*/
	function getById($id){
		// return new User(); // comment this out when you implement the code for this method
		$qStr = "SELECT
                  user_id,
                  user_first_name,
                  user_last_name,
				  user_email,
				  user_role,
				  user_password,
				  user_salt,
				  user_active

              FROM users
              WHERE user_id =" . mysqli_real_escape_string($this->link, $id);
  

			// id				user_id
			// firstName		user_first_name
			// lastName			user_last_name
			// email			user_email
			// roleId			user_role
			// password			user_password
			// salt				user_salt
			// active			user_active
		$result = mysqli_query($this->link, $qStr) or $this->handleError(mysqli_error($this->link));
	
		if($result->num_rows == 1){
			$row = mysqli_fetch_assoc($result);
			//var_dump($row);die();
			$role = $this->convertRowToModel($row);
			return $role;
		}
	
		return false;
	
	}

	
	/**
	 * Inserts a user into the database
	 * @param {User} $user
	 * @return {User}		Returns a User including the newly assignd user id			
	 */
	function insert($user){
		// return new User(); // comment this out when you implement the code for this method
		$row = $this->convertModelToRow($user);
  
		$qStr = "INSERT INTO users (
					user_first_name,
					user_last_name,
					user_email,
					user_role,
					user_password,
					user_salt,
					user_active
				) VALUES (
					'{$row['user_first_name']}',
					'{$row['user_last_name']}',
					'{$row['user_email']}',
					'{$row['user_role']}',
					'{$row['user_password']}',
					'{$row['user_salt']}',
					'{$row['user_active']}'
				)";
				// die($qStr);

			// id				user_id
			// firstName		user_first_name
			// lastName			user_last_name
			// email			user_email
			// roleId			user_role
			// password			user_password
			// salt				user_salt
			// active			user_active

		//die($qStr);
		$result = mysqli_query($this->link, $qStr) or $this->handleError(mysqli_error($this->link));
	
		if($result){
			$user->id = mysqli_insert_id($this->link);
			return $user;
		}else{
			$this->handleError("unable to insert user");
		}
	
		return false;
	}

	/**
	 * Updates a user in the database
	 * @param {User} $user
	 * @return {boolean}				Returns true if the update succeeds			
	 */
	function update($user){
        // return false; // comment this out when you implement the code for this method
		$row = $this->convertModelToRow($user);
  
		$qStr = "UPDATE users SET
					user_first_name = '{$row['user_first_name']}',
					user_last_name ='{$row['user_last_name']}',
					user_email = '{$row['user_email']}',
					user_role = '{$row['user_role']}',
					user_password = '{$row['user_password']}'
					WHERE user_id = " . $row['user_id'];
		//die($qStr);
		// id				user_id
			// firstName		user_first_name
			// lastName			user_last_name
			// email			user_email
			// roleId			user_role
			// password			user_password
			// salt				user_salt
			// active			user_active
		// die($qStr);	
		$result = mysqli_query($this->link, $qStr) or $this->handleError(mysqli_error($this->link));
		//var_dump($result); die();
		if($result){
			return true;
		}else{
			$this->handleError("Unable to update user");
		}
		return false;
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