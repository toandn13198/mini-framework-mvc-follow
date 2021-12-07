<?php
/**
 * summary
 */
class Router 
{
    /**
     * summary
     */
    private $routers=[];

    public function __construct()
    {
        
    }

    private function getRequestURL()
    {
    	$url= isset($_SERVER['REQUEST_URI'])? $_SERVER['REQUEST_URI']:'' ;
    	$url=str_replace('/miniframework/public/', '', $url);
    	$url=$url===''||empty($url)?'/':$url;
    	return $url;
    }

    private function getRequestMethod($value='')
    {
    	$method= isset($_SERVER['REQUEST_METHOD'])? $_SERVER['REQUEST_METHOD']:'' ;
    	return $method;
    }

    private function addRouter($method,$url,$action){
    	$this->routers[]=[$method,$url,$action];
    }

    public function get($url,$action){
    	$this->addRouter('GET',$url,$action);
    }

    public function post($url,$action){
    	$this->addRouter('POST',$url,$action);
    }

    public function any($url,$action){
    	$this->addRouter('GET|POST',$url,$action);
    }

    public function map()
    {
    	$checkRoute=false;
    	$requestURL=$this->getRequestURL();
    	$requestMethod=$this->getRequestMethod();
    	$routers=$this->routers;
    	$params=[];

    	foreach ($routers as $index=>$router) {
    		list($method,$url,$action)=$router;

    		if(strpos($method,$requestMethod)===FALSE){//kiem tra method gui len co = method dang ky hay khong
    			continue;
    		}
    		////////
    		if($url==='*'){
    			$checkRoute=true;

    		}else if(strpos($url, '{')===FALSE||strpos($url, '}')===FALSE ){//neu thieu { hoac } thi coi nhu k dang ky param trong route
    			//neu vay thi nhu 1 route thong thuong
    			if(strcmp(strtolower($url), strtolower($requestURL))===0){
    				$checkRoute=true;
    			}else{
    				continue;
    			}   	
	
    		}else{
    			 $routeParams= explode('/', $url);
    			 $requestParams= explode('/', $requestURL);
    			 
    			if(count($routeParams)!==count($requestParams)){
    				continue;
    			}    

    			foreach ($routeParams as $key => $value) {
    				if(preg_match('/^{\w+}$/', $value)){
    					$params[]=$requestParams[$key];
    				}
    			}		

    			$checkRoute=true;
    		}
    		////
    				
    		///////////
    		if($checkRoute===true){
    			if(is_callable($action)){
    				call_user_func_array($action, $params);
    				
    			}else{
    				if(is_string($action)){
    					$this->compieRouter($action,$params);
    				}
    			}
    		}else{
    			continue;
    		}
    		
    		return;
    	}

    }

    private function compieRouter($action, $params)
    {
    	if(count(explode('@', $action)) !==2){
    		die('Route @ error');
    	}
    	
    	$className= explode('@',$action)[0];
    	$methodName= explode('@',$action)[1];

    	$classNamespace= 'app\\controllers\\'.$className;

    	if(class_exists($classNamespace)){
    		$object= new $classNamespace;

    		if(method_exists($classNamespace, $methodName)){
    			call_user_func_array(array($object,$methodName), $params);
    		}else{
    			die('Method '.$methodName.' not found !');
    		}
    	}else{

    		die('Class '.$className.' not found !');
    	}
    }

    public function run(){

   		$this->map();
    }

}
?>