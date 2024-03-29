<?php
include_once("Controller.inc.php");
include_once(__DIR__ . "/../models/User.inc.php");
include_once(__DIR__ . "/../dataaccess/UserDataAccess.inc.php");

class UserController extends Controller{

	function __construct($link){
		parent::__construct($link);
	}

    // action method for the /users route
    public function handleUsers(){

        $da = new UserDataAccess($this->link);

                switch($_SERVER['REQUEST_METHOD']){
                    case "POST":


                        if(SECURE_SERVER_RESOURCES){
                            if(!$this->isAdmin()){
                                $this->sendStatusHeader(401, "Only admins can insert new users");
                                die();
                            }
                        }
                        // parse the JSON sent (in the request body) into an associative array
                    $data = $this->getJSONRequestBody();
                    //print_r($data);die();  // sanity check!

                    // pass the associative array into the User contructor
                    $user = new User($data);
                    // var_dump($user);die(); // another sanity check!

                    // make sure the User is valid, if so TRY to insert
                    if($user->isValid()){
                        try{
                            $user = $da->insert($user);
                            $json = json_encode($user);
                            $this->setContentType("json");
                            $this->sendStatusHeader(200);
                            echo($json);
                            die();
                        }catch(Exception $e){
                            $this->sendStatusHeader(500, $e->getMessage());
                            die();
                        }
                    }else{
                        $msg = implode(',', array_values($user->getValidationErrors()));
                        $this->sendStatusHeader(400, $msg);
                        die();
                    }

                break;
            case "GET":

                if(SECURE_SERVER_RESOURCES){
                    if(!$this->isAdmin()){
                        $this->sendStatusHeader(401, "Only admins can get all users");
                        die();
                    }
                }
                //echo("GET ALL USERS");
                $users = $da->getAll();
                //print_r($users);die();

                // Convert the users to json (and set the Content-Type header)
                $json = json_encode($users);

                // set the headers (before echoing anything into the response body)
                $this->setContentType("json");
                $this->sendStatusHeader(200);

                // set the response body
                echo($json);
                die();

                break;
            case "OPTIONS":
                // AJAX CALLS WILL OFTEN SEND AN OPTIONS REQUEST BEFORE A PUT OR DELETE
                // TO SEE IF CERTAIN REQUEST METHODS WILL BE ALLOWED
                header("Access-Control-Allow-Methods: GET,POST");
                break;
            default:
                // set a 400 header (invalid request)
                $this->sendStatusHeader(400);
        }
    }


    public function handleSingleUser(){

        // We need to get the url being requested so that we can extract the user id
        $url_path = $this->getUrlPath();
        $url_path = $this->removeLastSlash($url_path);
        //echo($url_path); die();
    
        // extract the user id by using a regular expression
        $id = null;
        if(preg_match('/^users\/([0-9]*\/?)$/', $url_path, $matches)){
            $id = $matches[1];
        }
    
        $da = new UserDataAccess($this->link);
    
        switch($_SERVER['REQUEST_METHOD']){
            case "GET": // getting a user
                if(SECURE_SERVER_RESOURCES){
                    if(!$this->isAdmin() && !$this->isOwner($id)){
                        $this->sendStatusHeader(401, "Only admins or owners can get a user (a user can get his/her own info)");
                        die();
                    }
                }
                $user = $da->getById($id);
                $json = json_encode($user);
                $this->setContentType("json");
                $this->sendStatusHeader(200);
                echo($json);
                die();
                break;
            case "PUT": //updating the users Id
			    //echo("TODO: UPDATE USER $id");
                if(SECURE_SERVER_RESOURCES){
                    // note that admins can update any user
                    // but standard users can only update their 'own' info
                    if(!$this->isAdmin() && !$this->isOwner($id)){
                        $this->sendStatusHeader(401, "Only admins or owners can put a user (a user can put his/her own info)");
                        die();
                    }
                }
                //ISSUE: Users should not be able to change their own role or active status!
                $data = $this->getJSONRequestBody();
                $user = new User($data);
                
                if($user->isValid()){
                    try{
                        if($da->update($user)){
                            $json = json_encode($user);
                            $this->setContentType("json");
                            $this->sendStatusHeader(200);
                            echo($json);
                        }
                    }catch(Exception $e){
                        $this->sendStatusHeader(500, $e->getMessage());
                    }
                    die();
                }else{
                    $msg = implode(',', array_values($user->getValidationErrors()));
                    $this->sendStatusHeader(400, $msg);
                    die();
                }
                break;
            case "DELETE":
                // echo("TODO: DELETE USER $id");
                if(SECURE_SERVER_RESOURCES){
                    if(!$this->isAdmin()){
                        $this->sendStatusHeader(401, "Only admins can delete a user");
                        die();
                    }
                }
                if($user = $da->getById($id)){
                    $user->active = false;
                    $da->update($user);
                    $this->sendStatusHeader(200);
                }
                else{
                    $this->sendStatusHeader(400, "Unable to 'delete' user $id");
                }
                break;
            case "OPTIONS":
                // AJAX CALLS WILL OFTEN SEND AN OPTIONS REQUEST BEFORE A PUT OR DELETE
                // TO SEE IF THE PUT/DELETE WILL BE ALLOWED
                header("Access-Control-Allow-Methods: GET,PUT,DELETE");
                break;
            default:
                // set a 400 header (invalid request)
                $this->sendStatusHeader(400);
        }
    }

   

}