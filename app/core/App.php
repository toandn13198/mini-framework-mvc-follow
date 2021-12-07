<?php
	require_once(dirname(__FILE__).'/Router.php');
	require_once(dirname(__FILE__).'/../controllers/C_home.php');
	/**
	 * summary
	 */
	class App
	{

	    /**
	     * summary
	     */
	    private $router;

	    public function __construct()
	    {
	    		
	        $this->router=new Router;
	        $this->router->get('home/{id}/{name}','C_home@index');
	    
	        $this->router->get('post/{name}','C_home@add');
	         $this->router->any('any',function(){
	        	echo 'trang any';
	        });
	         $this->router->get('get',function(){
	        	echo 'trang get';
	        });


	        $this->router->any('*',function(){
	        	echo '404 not found!';
	        });
	    }

	    public function run()
	    {
	    	$this->router->run();
	    }
	}

?>