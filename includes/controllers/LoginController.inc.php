<?php

include_once("Controller.inc.php");
include_once(__DIR__ . "/../models/User.inc.php");
include_once(__DIR__ . "/../dataaccess/UserDataAccess.inc.php");


class LoginController extends Controller{


    function __construct($link){
        parent::__construct($link);
    }


    public function handleLogin(){

        $da = new UserDataAccess($this->link);

        switch($_SERVER['REQUEST_METHOD']){
            case "POST":

                $data = $this->getJSONRequestBody();
                // print_r($data);die(); // this just shows you the data that was sent in the POST request

                // TODO: add code to handle POST request here
                try{
                    if($user = $da->login($data['email'], $data['password'])){
                
                        // encode the user obj to json string
                        $json = json_encode($user);
                
                        // create a new session id (this is recommended whenever a user logs in)
                        session_regenerate_id();
                
                        // TODO: send the session id in the x-id header
                
                        // set some session variables for the user who has just logged in
                        $_SESSION['authenticated'] = "yes";
                        $_SESSION['user_id'] = $user->id;
                        $_SESSION['user_first_name'] = $user->firstName;
                        $_SESSION['user_role_id'] = $user->roleId;
                
                        $this->setContentType("json");
                        $this->sendStatusHeader(200);
                        echo($json);
                        die();
                    }else{
                        $this->sendStatusHeader(401, "Invalid login or password");
                        die();
                    }
                }catch(Exception $e){
                    $this->sendStatusHeader(400, $e->getMessage());
                    die();
                }

            case "OPTIONS":
                // AJAX CALLS WILL OFTEN SEND AN OPTIONS REQUEST BEFORE A PUT OR DELETE
                // TO SEE IF THE PUT/DELETE WILL BE ALLOWED
                header("Access-Control-Allow-Methods: POST");
                $this->sendStatusHeader(200);
                die();
                break;
            default:
                // set a 400 header (invalid request)
                $this->sendStatusHeader(400);
                die();
        }
    }


    public function handleLogout(){

        // destroy the session cookie
        if (isset( $_COOKIE[session_name()])){
            setcookie( session_name(), "", time()-3600, "/" );
        }

        //empty the $_SESSION array
        $_SESSION = array();

        //destroy the session on the server
        session_destroy();
        $this->sendStatusHeader(200);
    }
}