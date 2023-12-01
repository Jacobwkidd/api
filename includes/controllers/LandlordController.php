<?php
include_once("Controller.inc.php");
include_once(__DIR__ . "/../models/Landlord.inc.php");
include_once(__DIR__ . "/../dataaccess/LandLordDataAccess.inc.php");

class LandlordController extends Controller{

	function __construct($link){
		parent::__construct($link);
	}

    // action method for the /users route
    public function handleUsers(){

        $da = new LandLordDataAccess($this->link);

                switch($_SERVER['REQUEST_METHOD']){
                    case "POST":
                        // parse the JSON sent (in the request body) into an associative array
                    $data = $this->getJSONRequestBody();
                    //print_r($data);die();  // sanity check!

                    // pass the associative array into the LandLord contructor
                    $LandLord = new LandLord($data);
                    // var_dump($LandLord);die(); // another sanity check!

                    // make sure the LandLord is valid, if so TRY to insert
                    if($LandLord->isValid()){
                        try{
                            $LandLord = $da->insert($LandLord);
                            $json = json_encode($LandLord);
                            $this->setContentType("json");
                            $this->sendStatusHeader(200);
                            echo($json);
                            die();
                        }catch(Exception $e){
                            $this->sendStatusHeader(500, $e->getMessage());
                            die();
                        }
                    }else{
                        $msg = implode(',', array_values($LandLord->getValidationErrors()));
                        $this->sendStatusHeader(400, $msg);
                        die();
                    }

                break;
            case "GET":
                //echo("GET ALL LandLord");
                $LandLord = $da->getAll();
                //print_r($LandLord);die();

                // Convert the LandLord to json (and set the Content-Type header)
                $json = json_encode($LandLord);

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


    public function handleSingleLandLord(){

        // We need to get the url being requested so that we can extract the LandLord id
        $url_path = $this->getUrlPath();
        $url_path = $this->removeLastSlash($url_path);
        echo($url_path); die();
    
        // extract the LandLord id by using a regular expression
        $id = null;
        if(preg_match('/^LandLord\/([0-9]*\/?)$/', $url_path, $matches)){
            $id = $matches[1];
        }
    
        $da = new LandLordDataAccess($this->link);
    
        switch($_SERVER['REQUEST_METHOD']){
            case "GET": // getting a LandLord
                $LandLord = $da->getById($id);
                $json = json_encode($LandLord);
                $this->setContentType("json");
                $this->sendStatusHeader(200);
                echo($json);
                die();
                break;
            case "PUT": //updating the LandLord Id
			    //echo("TODO: UPDATE LandLord $id");
                $data = $this->getJSONRequestBody();
                $LandLord = new LandLord($data);
                
                if($LandLord->isValid()){
                    try{
                        if($da->update($LandLord)){
                            $json = json_encode($LandLord);
                            $this->setContentType("json");
                            $this->sendStatusHeader(200);
                            echo($json);
                        }
                    }catch(Exception $e){
                        $this->sendStatusHeader(500, $e->getMessage());
                    }
                    die();
                }else{
                    $msg = implode(',', array_values($LandLord->getValidationErrors()));
                    $this->sendStatusHeader(400, $msg);
                    die();
                }
                break;
            case "DELETE":
                // echo("TODO: DELETE LandLord $id");
                if($LandLord = $da->getById($id)){
                    $LandLord->active = false;
                    $da->update($LandLord);
                    $this->sendStatusHeader(200);
                }
                else{
                    $this->sendStatusHeader(400, "Unable to 'delete' LandLord $id");
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