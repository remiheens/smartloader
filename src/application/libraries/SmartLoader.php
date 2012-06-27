<?php

/*
 * SmartLoader Library
 *
 * Author : Rémi Heens
 * Site : http://www.remiheens.fr
 * Version : 1.0
 * Release Date : 14/04/12
 *
 */
class SmartLoader
{
	public $js;
	public $css;
	
	public function __construct()
	{
		$this->js = new SmartLoaderJS();
		$this->css = new SmartLoaderCSS();
	}
}
 
class SmartLoaderJS 
{
	private $_jsinline = "";
	private $_jsJquery = "";
	
	private $_links = array();
	
	
	public function add($args)
	{
		if(is_array($args))
        {
            foreach($args as $a)
            {
                array_push($this->_links, $a);
            }
        }
        else
        {
            array_push($this->_links, $args);
        }
	}
	
	public function jquery($data)
	{
		$this->_jsJquery .= $data."\n";
	}
	
	public function js($data)
	{
		$this->_jsinline .= $data."\n";
	}
	
	public function loadLinks()
	{
		foreach($this->_links as $link)
		{
		   echo "<script type=\"text/javascript\" src=\"".$link."\"></script>"."\n";
	    }
	}
	
	public function loadJavascript()
    {
        echo "<script type='text/javascript'>\njQuery(document).ready(function($){\n\t".$this->_jsJquery."\n});\n".$this->_jsinline."\n</script>";
    }
}

class SmartLoaderCSS
{
    
	// Code Igniter instance
	private $CI;
	// Configuration item
	private $_site_folder = "";
	private $_autoload_folder = "";
	private $_concat_file = "";

	//
	private $_link = array();
	
	
	public function SmartLoaderCSS()
	{
		$this->CI = & get_instance();
	
		$this->CI->config->load('smartloader');
		
		$this->_site_folder= $this->CI->config->item('slcss_site_folder');
		$this->_autoload_folder= $this->CI->config->item('slcss_autoload_folder');
		$this->_concat_file= $this->CI->config->item('slcss_concat_file');
		
		if(ENVIRONMENT == "development")
		{
			$autoload_link = array();
			if(is_dir($this->_autoload_folder))
			{
				$dir = opendir($this->_autoload_folder); 
				
				while($file = readdir($dir))
				{
					if($file != '.' && $file != '..' && strpos($file,".css") !== FALSE)
					{
						array_push($autoload_link,$file);
					}
				}
				closedir($dir);
				
				asort($autoload_link);
				$this->createBaseFile($autoload_link);
			}
		}
	}
	
	private function createBaseFile($autoload_link)
	{
		$f = fopen($this->_site_folder.$this->_concat_file,"w+");
		foreach($autoload_link as $file)
		{
			$data = file_get_contents($this->_autoload_folder.$file);
			fwrite($f,$data);
		}
		fclose($f);
	}
	
    public function add($args)
    { 
        if(is_array($args))
        {
            foreach($args as $a)
            {
                array_push($this->_link, $a);
            }
        }
        else
        {
            array_push($this->_link, $args);
        }
    }

    public function loadLinks()
    {
		if(!empty($this->_concat_file))
    	{
	    	echo "<link rel=\"stylesheet\" href=\"".$this->_concat_file."\" type=\"text/css\" />"."\n";
    	}
	
		foreach($this->_link as $link)
		{
			echo "<link rel=\"stylesheet\" href=\"".$link."\" type=\"text/css\" />"."\n";
		}
    }
}