<?php
include_once("Model.inc.php");


class Tenant extends Model{
    //Instance variables
    public $id;
	public $firstName;
	public $lastName;
	public $email;
    public $password;

    public function __construct($args = []){
        $this->id = $args["id"] ?? 0;
        $this->firstName = $args["firstName"] ?? "";
        $this->lastName = $args["lastName"] ?? "";
        $this->email = $args["email"] ?? "";
        $this->password = $args["password"] ?? "";
    }

    public function isValid(){
		
		$valid = true;
		$this->validationErrors = [];


        //Validate the ID
        if(!is_numeric($this->id) || $this->id < 0){
            $valid = false;
            $this->validationErrors["id"] = "ID is not valid";
        }


        //Validate the firstName
        if(empty($this->firstName)){
            $valid = false;
            $this->validationErrors["firstName"] = "First Name is required";
        }
        else if(strlen($this->firstName) > 30){
            $valid = false;
            $this->validationErrors["firstName"] = "First Name must be 30 characters or less";
        }

        //validate the last name
        if(empty($this->lastName)){
			$valid = false;
			$this->validationErrors["lastName"] = "Last Name is required";
		}
		if(strlen($this->lastName) > 30){
			$valid = false;
			$this->validationErrors["lastName"] = "Last Name must be 30 characters or less";
		}


        //validate the email
        if(empty($this->email)){
			$valid = false;
			$this->validationErrors["email"] = "Email is required";
		}
		else if(strlen($this->email) > 100){
			$valid = false;
			$this->validationErrors["email"] = "Email must be 100 characters or less";
		}
		else if(!filter_var($this->email, FILTER_VALIDATE_EMAIL)){
			$valid = false;
			$this->validationErrors["email"] = "The email address is not valid";
		}

        //validate password
        if($this->id == 0){
			$valid = false;
			$this->validationErrors["password"] = "Password is required";
			if(empty($this->password)){
				$valid = false;
				$this->validationErrors["password"] = "Password is required";
			}
		}

        return $valid;
    }
}
?>