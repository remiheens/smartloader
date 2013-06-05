<?php

class MY_Controller extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->smartloader->js->add("http://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js");
	}
	
}