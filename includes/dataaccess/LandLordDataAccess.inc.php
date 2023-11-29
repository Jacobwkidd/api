<?php

/*
NOTE - The database column names do not map directly to the property names of the landlord model object
		
		Role Model 		users_roles table columns
		-----------------------------------
		id				user_role_id
		name			user_role_name
		description		user_role_desc
*/

include_once("DataAccess.inc.php");
include_once(__DIR__ . "/../models/Landlord.inc.php"); 


class LandLordDataAccess extends DataAccess{

    /**
    * Constructor function for this class
    * @param {mysqli} $link      A preconfigured connection to the database
    */
    function __construct($link){
		  parent::__construct($link); // call the super constructor
    }

    /**
    * Converts a model object into an assoc array and sets the keys
    * to the proper names. For example a $role->id is converted to $row['user_role_id']
    * The data should also be scrubbed to prevent SQL injection attacks
    *
    * @param {Role} $role 
    * @return {array}
    */
    function convertModelToRow($landlord){
      $row = [];

      $row["landlord_user_id"] = $landlord->id;
      $row["landlord_user_first_name"] = $landlord->firstName;
      $row["landlord_user_last_name"] = $landlord->lastName;
      $row["landlord_user_email"] = $landlord->email;
      $row["landlord_user_password"] = $landlord->password;

      return $row;

    }

    /**
    * Converts a row from the database to a model object
    * And scrubs the data to prevent XSS attacks
    *
    * @param {array} $row
    * @return {Role}		Returns a subclass of a Model 
    */
    function convertRowToModel($row){

        $landlord = new LandLord();
        $landlord->id = $row['landlord_user_id'];
        $landlord->firstName = $row['landlord_user_first_name'];
        $landlord->lastName = $row['landlord_user_last_name'];
        $landlord->email = $row['landlord_user_email'];   
        $landlord->password = $row['landlord_user_password']; 
  
      return $landlord;
    }


    /**
    * Gets all rows from a table in the database
    * @param {assoc array} 	This optional param would allow you to filter the result set
    * 						For example, you could use it to somehow add a WHERE claus to the query
    * 
    * @return {array}		Returns an array of model objects
    */
    function getAll($args = []){

      $qStr = "SELECT
                  landlord_user_id,
                  landlord_user_first_name,
                  landlord_user_last_name,
                  landlord_user_email,
                  landlord_user_password
              FROM landlord";
  
      $result = mysqli_query($this->link, $qStr) or $this->handleError(mysqli_error($this->link));
  
      $allRoles = [];
  
      while($row = mysqli_fetch_assoc($result)){
          $allRoles[] = $this->convertRowToModel($row);
      }
  
      return $allRoles;
    }

    /**
    * Gets a row from the database by it's id
    * @param {number} $id 	The id of the item to get from a row in the database
    * @return {Role}		Returns an instance of a model object 
    */
    function getById($id){

      $qStr = "SELECT
                  landlord_user_id,
                  landlord_user_first_name,
                  landlord_user_last_name,
                  landlord_user_email,
                  landlord_user_password
              FROM landlord
              WHERE landlord_user_id =" . mysqli_real_escape_string($this->link, $id);
  
      $result = mysqli_query($this->link, $qStr) or $this->handleError(mysqli_error($this->link));
  
      if($result->num_rows == 1){
          $row = mysqli_fetch_assoc($result);
          //var_dump($row);die();
          $landlord = $this->convertRowToModel($row);
          return $landlord;
      }
  
      return false;
  
    }

    /**
    * Inserts a row into a table in the database
    * @param {Role}	$role	The model object to be inserted
    * @return {Role}		Returns the same model object, but with the id property set 
    *						(the id is assigned by the database)
    */
    function insert($role){

      $row = $this->convertModelToRow($landlord);
  
      $qStr = "INSERT INTO landlord (
                  landlord_user_id,
                  landlord_user_first_name,
                  landlord_user_last_name,
                  landlord_user_email,
                  landlord_user_password
              ) VALUES (
                  '{$row['landlord_user_id']}',
                  '{$row['landlord_user_first_name']}',
                  '{$row['landlord_user_last_name']}',
                  '{$row['landlord_user_email']}',
                  '{$row['landlord_user_password']}'
              )";
      //die($qStr);
      $result = mysqli_query($this->link, $qStr) or $this->handleError(mysqli_error($this->link));
  
      if($result){
          $landlord->id = mysqli_insert_id($this->link);
          return $landlord;
      }else{
          $this->handleError("unable to insert landlord");
      }
  
      return false;
    }

    /**
    * Updates a row in a table of the database
    * @param {Role}	$role	The model object to be updated
    * @return {boolean}		Returns true if the updated succeeded, false otherwise
    */
    function update($role){

      $row = $this->convertModelToRow($landlord);
  
      $qStr = "UPDATE landlord SET
                  landlord_user_id = '{$row['landlord_user_id']}',
                  landlord_user_first_name = '{$row['landlord_user_first_name']}',
                  landlord_user_last_name = '{$row['landlord_user_last_name']}',
                  landlord_user_email = '{$row['landlord_user_email']}',
                  landlord_user_password = '{$row['landlord_user_password']}'
                  WHERE landlord_user_id = " . $row['landlord_user_id'];
      //die($qStr);
  
      $result = mysqli_query($this->link, $qStr) or $this->handleError(mysqli_error($this->link));
      //var_dump($result); die();
      if($result){
          return true;
      }else{
          $this->handleError("Unable to update landlord");
      }
      return false;
    }


    /**
    * Deletes a row from a table in the database
    * @param {number} 	The id of the row to delete
    * @return {boolean}	Returns true if the row was sucessfully deleted, false otherwise
    */
    function delete($id){
    	// should we really delete a row?
    	// it can get super tricky when there are foreign keys!
		  return null; // replace this with the real code
    }
}