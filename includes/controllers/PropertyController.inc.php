<?php
include_once("Controller.inc.php");
include_once(__DIR__ . "/../models/Property.inc.php");
include_once(__DIR__ . "/../dataaccess/PropertiesDataAccess.inc.php");

class PropertyController extends Controller{

	function __construct($link){
		parent::__construct($link);
	}

    // action method for the /users route
    public function handleProperty(){

        $da = new PropertyDataAccess($this->link);

                switch($_SERVER['REQUEST_METHOD']){
                    case "POST":
                        // parse the JSON sent (in the request body) into an associative array
                    $data = $this->getJSONRequestBody();
                    //print_r($data);die();  // sanity check!

                    // pass the associative array into the LandLord contructor
                    $property = new Property($data);
                    // var_dump($landlord);die(); // another sanity check!

                    // make sure the LandLord is valid, if so TRY to insert
                    if($property->isValid()){
                        try{
                            $property = $da->insert($property);
                            $json = json_encode($property);
                            $this->setContentType("json");
                            $this->sendStatusHeader(200);
                            echo($json);
                            die();
                        }catch(Exception $e){
                            $this->sendStatusHeader(500, $e->getMessage());
                            die();
                        }
                    }else{
                        $msg = implode(',', array_values($property->getValidationErrors()));
                        $this->sendStatusHeader(400, $msg);
                        die();
                    }

                break;
            case "GET":
                //echo("GET ALL property");
                $property = $da->getAll();
                //print_r($property);die();

                // Convert the property to json (and set the Content-Type header)
                $json = json_encode($property);

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


    public function handleSingleProperty(){

        // We need to get the url being requested so that we can extract the property id
        $url_path = $this->getUrlPath();
        $url_path = $this->removeLastSlash($url_path);
        echo($url_path); die();
    
        // extract the property id by using a regular expression
        $id = null;
        if(preg_match('/^property\/([0-9]*\/?)$/', $url_path, $matches)){
            $id = $matches[1];
        }
    
        $da = new PropertyDataAccess($this->link);
    
        switch($_SERVER['REQUEST_METHOD']){
            case "GET": // getting a property
                $property = $da->getById($id);
                $json = json_encode($property);
                $this->setContentType("json");
                $this->sendStatusHeader(200);
                echo($json);
                die();
                break;
            case "PUT": //updating the property Id
			    //echo("TODO: UPDATE property $id");
                $data = $this->getJSONRequestBody();
                $property = new Property($data);
                
                if($property->isValid()){
                    try{
                        if($da->update($property)){
                            $json = json_encode($property);
                            $this->setContentType("json");
                            $this->sendStatusHeader(200);
                            echo($json);
                        }
                    }catch(Exception $e){
                        $this->sendStatusHeader(500, $e->getMessage());
                    }
                    die();
                }else{
                    $msg = implode(',', array_values($property->getValidationErrors()));
                    $this->sendStatusHeader(400, $msg);
                    die();
                }
                break;
            case "DELETE":
                // echo("TODO: DELETE property $id");
                if($property = $da->getById($id)){
                    $property->active = false;
                    $da->update($property);
                    $this->sendStatusHeader(200);
                }
                else{
                    $this->sendStatusHeader(400, "Unable to 'delete' property $id");
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