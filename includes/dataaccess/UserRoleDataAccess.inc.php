<?php

/*
NOTE - The database column names do not map directly to the property names of the Role model object
		
		Role Model 		users_roles table columns
		-----------------------------------
		id				user_role_id
		name			user_role_name
		description		user_role_desc
*/

include_once("DataAccess.inc.php");
include_once(__DIR__ . "/../models/Role.inc.php"); 


class UserRolesDataAccess extends DataAccess{

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
    function convertModelToRow($role){
      $row = [];

      $row["user_role_id"] = $role->id;
      $row["user_role_name"] = $role->name;
      $row["user_role_desc"] = $role->description;

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

      $role = new Role();
      $role->id = $row['user_role_id'];
      $role->name = $row['user_role_name'];
      $role->description = $row['user_role_desc'];
  
      return $role;
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
                  user_role_id,
                  user_role_name,
                  user_role_desc
              FROM user_roles";
  
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
                  user_role_id,
                  user_role_name,
                  user_role_desc
              FROM user_roles
              WHERE user_role_id =" . mysqli_real_escape_string($this->link, $id);
  
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
    * Inserts a row into a table in the database
    * @param {Role}	$role	The model object to be inserted
    * @return {Role}		Returns the same model object, but with the id property set 
    *						(the id is assigned by the database)
    */
    function insert($role){

      $row = $this->convertModelToRow($role);
  
      $qStr = "INSERT INTO user_roles (
                  user_role_name,
                  user_role_desc
              ) VALUES (
                  '{$row['user_role_name']}',
                  '{$row['user_role_desc']}'
              )";
      //die($qStr);
      $result = mysqli_query($this->link, $qStr) or $this->handleError(mysqli_error($this->link));
  
      if($result){
          $role->id = mysqli_insert_id($this->link);
          return $role;
      }else{
          $this->handleError("unable to insert role");
      }
  
      return false;
    }

    /**
    * Updates a row in a table of the database
    * @param {Role}	$role	The model object to be updated
    * @return {boolean}		Returns true if the updated succeeded, false otherwise
    */
    function update($role){

      $row = $this->convertModelToRow($role);
  
      $qStr = "UPDATE user_roles SET
                  user_role_name = '{$row['user_role_name']}',
                  user_role_desc='{$row['user_role_desc']}'
                  WHERE user_role_id = " . $row['user_role_id'];
      //die($qStr);
  
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
    function delete($id){
    	// should we really delete a row?
    	// it can get super tricky when there are foreign keys!
		  return null; // replace this with the real code
    }
}