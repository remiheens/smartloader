<?php

/*
 * SmartLoader Library
 *
 * Author : Rémi Heens
 * Site : http://ci.remiheens.fr/smart-loader
 * Version : 1.0
 * Release Date : 14/04/12
 *
 */
class SmartLoader
{
	/*
	 * SmartLoaderJS instance
	 */
	public $js;
	/*
	 * SmartLoaderCSS instance
	 */
	public $css;
	
	public function __construct()
	{
		$this->js = new SmartLoaderJS();
		$this->css = new SmartLoaderCSS();
	}
}
/*
 * SmartLoaderJS Class
 * Manage your JS into smartloader
 */
class SmartLoaderJS 
{
	/*
	 * Contain classic javascript
	 */
	private $_jsinline = array('header'=>'','footer'=>'');
	/*
	 * Contain jquery code
	 */
	private $_jsJquery = array('header'=>'','footer'=>'');
	
	private $_links = array();
	
	/*
	 * Add js file url
	 * @param mixed (String|Array)
	 */
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
	
	/*
	 * Add jquery code
	 * @param string jquery code
	 * @param boolean if you want to add this code into header TRUE,not leave blank 
	 */
	public function jquery($data,$header=false)
	{
		if($header === true)
		{
			$this->_jsJquery['header'] .= $data."\n";
		}
		else
		{
			$this->_jsJquery['footer'] .= $data."\n";
		}
		
	}
	
	/*
	 * Add javascript code
	 * @param string javascript code
	 * @param boolean if you want to add this code into header TRUE,not leave blank 
	 */
	public function js($data,$header=false)
	{
		if($header === true)
		{
			$this->_jsinline['header'] .= $data."\n";
		}
		else
		{
			$this->_jsinline['footer'] .= $data."\n";
		}
	}
	
	/*
	 * Output the list of script balise for all js link added
	 */
	public function loadLinks()
	{
		foreach($this->_links as $link)
		{
		   echo "<script type=\"text/javascript\" src=\"".$link."\"></script>"."\n";
	    }
	}
	
	/*
	 * Output javascript code
	 * @param boolean if you want to ouput the header code set TRUE, footer leave blank
	 */
	public function loadJavascript($header=false)
    {
    	if($header===true)
    	{
	    	echo "<script type='text/javascript'>\njQuery(document).ready(function($){\n\t".$this->_jsJquery['header']."\n});\n".$this->_jsinline['header']."\n</script>";
    	}
    	else
    	{
	    	echo "<script type='text/javascript'>\njQuery(document).ready(function($){\n\t".$this->_jsJquery['footer']."\n});\n".$this->_jsinline['footer']."\n</script>";
    	}
        
    }
}
/*
 * SmartLoaderCSS Class
 * Manage your CSS into smartloader
 */
class SmartLoaderCSS
{
    
	// Code Igniter instance
	private $CI;
	// Configuration item
	private $_site_folder = "";
	private $_autoload_folder = "";
	private $_concat_file = "";

	private $_link = array();
	
	
	public function __construct()
	{
		// Get CI instance 
		$this->CI = & get_instance();
	
		// load configuration file
		$this->CI->config->load('smartloader');
		// get some configuration options
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
	
	/*
	 * Create a css file who contain every autoload css
	 * @warning if the base file isn't writable
	 * @params array list of css pathfile
	 */
	private function createBaseFile($autoload_link)
	{
		if(is_writable($this->_site_folder.$this->_concat_file))
		{
			$f = fopen($this->_site_folder.$this->_concat_file,"w+");
			foreach($autoload_link as $file)
			{
				$data = file_get_contents($this->_autoload_folder.$file);
				fwrite($f,$data);
			}
			fclose($f);
		}
		else
		{
			trigger_error('Can\'t write the concatened file : '.$this->_site_folder.$this->_concat_file);	
		}
	}
	
	/*
	 * Add css file url
	 * @param mixed (String|Array)
	 */
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
    
    /*
	 * Output the list of script balise for all js link added
	 */
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