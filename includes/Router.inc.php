<?php 

class Router{

	private $routes;

	function __construct($routes){
		$this->routes = $routes;
	}

	function getController($url_path){
	
		
		foreach($this->routes as $key => $controller){
			
			if($this->compare_route_and_path($key, $url_path)){
				return $controller;
			}
			
		}
		return false;
	}

	private function compare_route_and_path($route, $path){
		
		// users/:id should match users/7

		$route = rtrim($route, "/");
		$path = rtrim($path, "/");

		if($route == $path){
			return true;
		}

		$route_parts = explode("/", $route);
		$path_parts = explode("/", $path);

		$route_parts_count = count($route_parts);
		$path_parts_count = count($path_parts);

		if($route_parts_count == $path_parts_count){
			for($x = 0; $x < $route_parts_count; $x++){
				$r_part = $route_parts[$x];
				$p_part = $path_parts[$x];
				if($r_part == $p_part || 
					(is_numeric($p_part) && $r_part == ":id")){

					// if we have reached the last elements and everything has been a match
					// then the route matches the path...
					if($x == $route_parts_count - 1){
						return true;
					}

				}else{
					return false;
				}
			}
		}
		return false;
	}
	
}