<?php
include_once("Controller.inc.php");
include_once(__DIR__ . "/../models/Role.inc.php");
include_once(__DIR__ . "/../dataaccess/RoleDataAccess.inc.php");

class RoleController extends Controller{

	function __construct($link){
		parent::__construct($link);
	}

	public function handleRoles(){

		$da = new RoleDataAccess($this->link);

		switch($_SERVER['REQUEST_METHOD']){
			case "POST":
				echo("TODO: INSERT ROLE");

				break;
			case "GET":
				// echo("TODO: GET ALL ROLES");
                // get all Roles
                $roles = $da->getAll();
                // var_dump($roles);die();  // sanity check (ask me about 'sanity checks')

                // convert the Roles to JSON
                $jsonRoles = json_encode($roles);

                // set the headers (headers must be set before echoing anything)
                $this->setContentType("json");
                $this->sendStatusHeader(200);

                // set the response body
                echo($jsonRoles);

                // terminate
                die();

				break;
			default:
				// set a 400 header (invalid request)
				$this->sendStatusHeader(400);
		}
	}

}