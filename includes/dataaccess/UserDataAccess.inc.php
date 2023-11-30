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
		$role->firstName = htmlentities($row['user_first_name']);
		$role->lastName = htmlentities($row['user_last_name']);
		$role->email = htmlentities($row['user_email']);
		$role->roleId = $row['user_role'];
		$role->password = "";
		$role->salt = "";
		$role->active = $row['user_active'] > 0 ? true : false;
  
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

		// salt and hash the password
		$row['user_salt'] = $this->getRandomSalt();
		$row['user_password'] = $this->saltAndHashPassword($row['user_salt'], $row['user_password']);
  
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

		$row = $this->convertModelToRow($user);
	
		$qStr;
	
		if(!empty($user->password)){
			// If the password is NOT empty, then we'll salt and hash it
			$salt = $this->getRandomSalt();
			$hashedPassword = $this->saltAndHashPassword($salt, $row['user_password']);
	
			$qStr = "UPDATE users SET
					user_first_name = '{$row['user_first_name']}',
					user_last_name = '{$row['user_last_name']}',
					user_email = '{$row['user_email']}',
					user_role = '{$row['user_role']}',
					user_password = '$hashedPassword',
					user_salt = '$salt',
					user_active = '{$row['user_active']}'
				WHERE user_id = " . $row['user_id'];
		}else{
			// If the password is emtpy we just won't include the password
			// and the salt in the update query, which will leave them as they are in the database
			$qStr = "UPDATE users SET
					user_first_name = '{$row['user_first_name']}',
					user_last_name = '{$row['user_last_name']}',
					user_email = '{$row['user_email']}',
					user_role = '{$row['user_role']}',
					user_active = '{$row['user_active']}'
				WHERE user_id = " . $row['user_id'];
		}
	
		//die($qStr);
	
		$result = mysqli_query($this->link, $qStr) or $this->handleError(mysqli_error($this->link));
	
		// Remember we discovered a bug, that when you run an update
		// statement for a user that doesn't exist, the $result will be true
		if($result){
			return true;
		}else{
			$this->handleError("unable to update user");
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
	/**
 * Generates a random 'salt' string
 * @return {string} 	A random string
 */
	function getRandomSalt(){
		$bytes = random_bytes(5);
		return bin2hex($bytes);
	}

	/**
	 * Applies salt to a password (before hasing)
	 * @param {string} $salt 		A random salt string
	 * @param {string} $password 	The password to be salted
	 * @return {string}				The salted password
	 */
	function saltPassword($salt, $password){
		return $salt . $password . $salt;
	}


	/**
	 * Salts and hashes a password
	 * @param {string} $salt 		The salt to use
	 * @param {string} $password 	The password to salt and hash
	 * @return {string} 			The salted, hashed, password
	 */
	function saltAndHashPassword($salt, $password){

		$salted_password = $this->saltPassword($salt, $password);
		$encrypted_password = password_hash($salted_password, PASSWORD_DEFAULT);

		return $encrypted_password;

	}

	 /**
    * Authenticates a user
    * @param {string} $email
    * @param {string} $password
    * @return {User} 			Returns a User model object if authentication is successful
    * 							Returns false otherwise
    */
    function login($email, $password){

        //REMINDER: the user should be 'active' in order to login

        // Prevent SQL injection
        $email = mysqli_real_escape_string($this->link, $email);
        $password = mysqli_real_escape_string($this->link, $password);

        // Select all columns from the user table where user_email = $email AND user_active = "yes"
        // Note that we aren't checking the password here, we'll do that next.
        $qStr = "SELECT
                    user_id,
                    user_first_name,
                    user_last_name,
                    user_email,
                    user_role,
                    user_role_name,
                    user_salt,
                    user_password,
                    user_active
                FROM users U
                INNER JOIN user_roles UR on U.user_role = UR.user_role_id
                WHERE user_email = '{$email}' AND user_active=true";

        //die($qStr);

        $result = mysqli_query($this->link, $qStr) or $this->handleError(mysqli_error($this->link));

        if($result && $result->num_rows == 1){

            $row = mysqli_fetch_assoc($result);

            $salted_password = $this->saltPassword($row['user_salt'], $password);

            // verify that the salted password matches the user's password in the database:
            if(password_verify($salted_password, $row['user_password'])){
                $user = $this->convertRowToModel($row);
                // WE PROBABLY SHOULD REMOVE THE SALT PROPERTY FROM THE USER MODEL!!! NO NEED TO SHARE IT OUTSIDE OF THE DB!!!
                return $user;
            }
        }

        return false;
    }

}