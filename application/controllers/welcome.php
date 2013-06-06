<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Welcome extends MY_Controller {

	public function index()
	{
		/* want to load a js plugin with css */
		
		$this->smartloader->js->loadLib("plugin");
		
		$this->smartloader->css->load("welcome");
		
		$this->smartloader->js->jquery('$("#body").css("background-color","red");');
		
		$this->load->view('welcome_message');
	}
	
}
