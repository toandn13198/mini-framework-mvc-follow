<?php
	namespace app\controllers;


	/**
	 * C_home
	 */
	class C_home
	{
	    /**
	     * C_home
	     */
	    public function __construct()
	    {
	        echo 'this is C_home class !<br>';

	    }

	    public function index($value1,$value2)
	    {
	    	echo 'this is index method !';
	    	echo $value1;
	    	echo $value2;
	    }

	    public function add($name)
	    {
	    	echo 'function add '.$name;
	    }
	
	}
 ?>