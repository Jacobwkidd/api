<?php
include_once("Controller.inc.php");
include_once(__DIR__ . "/../models/Tenant.inc.php");
include_once(__DIR__ . "/../dataaccess/TenantDataAccess.inc.php");

class TenantController extends Controller{

	function __construct($link){
		parent::__construct($link);
	}

    // action method for the /users route
    public function handleTenant(){

        $da = new TenantDataAccess($this->link);

                switch($_SERVER['REQUEST_METHOD']){
                    case "POST":
                        // parse the JSON sent (in the request body) into an associative array
                    $data = $this->getJSONRequestBody();
                    //print_r($data);die();  // sanity check!

                    // pass the associative array into the LandLord contructor
                    $tenant = new Tenant($data);
                    // var_dump($tenant);die(); // another sanity check!

                    // make sure the tenant is valid, if so TRY to insert
                    if($tenant->isValid()){
                        try{
                            $tenant = $da->insert($tenant);
                            $json = json_encode($tenant);
                            $this->setContentType("json");
                            $this->sendStatusHeader(200);
                            echo($json);
                            die();
                        }catch(Exception $e){
                            $this->sendStatusHeader(500, $e->getMessage());
                            die();
                        }
                    }else{
                        $msg = implode(',', array_values($tenant->getValidationErrors()));
                        $this->sendStatusHeader(400, $msg);
                        die();
                    }

                break;
            case "GET":
                //echo("GET ALL tenant");
                $tenant = $da->getAll();
                //print_r($tenant);die();

                // Convert the tenant to json (and set the Content-Type header)
                $json = json_encode($tenant);

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


    public function handleSingleTenant(){

        // We need to get the url being requested so that we can extract the tenant id
        $url_path = $this->getUrlPath();
        $url_path = $this->removeLastSlash($url_path);
        echo($url_path); die();
    
        // extract the tenant id by using a regular expression
        $id = null;
        if(preg_match('/^tenant\/([0-9]*\/?)$/', $url_path, $matches)){
            $id = $matches[1];
        }
    
        $da = new TenantDataAccess($this->link);
    
        switch($_SERVER['REQUEST_METHOD']){
            case "GET": // getting a LandLord
                $tenant = $da->getById($id);
                $json = json_encode($tenant);
                $this->setContentType("json");
                $this->sendStatusHeader(200);
                echo($json);
                die();
                break;
            case "PUT": //updating the tenant Id
			    //echo("TODO: UPDATE tenant $id");
                $data = $this->getJSONRequestBody();
                $tenant = new Tenant($data);
                
                if($tenant->isValid()){
                    try{
                        if($da->update($tenant)){
                            $json = json_encode($tenant);
                            $this->setContentType("json");
                            $this->sendStatusHeader(200);
                            echo($json);
                        }
                    }catch(Exception $e){
                        $this->sendStatusHeader(500, $e->getMessage());
                    }
                    die();
                }else{
                    $msg = implode(',', array_values($tenant->getValidationErrors()));
                    $this->sendStatusHeader(400, $msg);
                    die();
                }
                break;
            case "DELETE":
                // echo("TODO: DELETE tenant $id");
                if($tenant = $da->getById($id)){
                    $tenant->active = false;
                    $da->update($tenant);
                    $this->sendStatusHeader(200);
                }
                else{
                    $this->sendStatusHeader(400, "Unable to 'delete' tenant $id");
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