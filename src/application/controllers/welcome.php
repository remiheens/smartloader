<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Welcome extends MY_Controller {

	public function index()
	{
		/* want to load a js plugin with css */
		
		$this->smartloader->js->add("http://localhost/js/myplugin.js");
		
		$this->smartloader->css->add("http://localhost/css/button.css");
		
		$this->smartloader->js->jquery('$("#body").css("background-color","red");');
		
		$this->load->view('welcome_message');
	}
	
}
