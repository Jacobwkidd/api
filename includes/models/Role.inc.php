<?php 

// Add your code here
include_once("Model.inc.php");

class Role extends Model{

    public $id;
    public $name;
    public $description;
    //constructor
    function __construct($args=[]){
        $this->id = $args["id"] ?? 0;
        $this->name = $args["name"] ?? "";
        $this->description = $args["description"] ?? "";
    }

    public function isValid(){
        $valid = true;
        $this->validationErrors = [];

        //valid the id 
        if(!is_numeric($this->id) || $this->id < 0){
            $valid = false;
            $this->validationErrors["id"] = "ID is not valid";
        }

        // name should be less than 30 characters
        // name should not be empty
        if(empty($this->name)){
            $valid = false;
            $this->validationErrors["name"] = "Name cannot be empty";
        }
        else if(strlen($this->name) > 30){
            $valid = false;
            $this->validationErrors["name"] = "Name cannot be longer than 30 characters";
        }

        return $valid;
    }

}