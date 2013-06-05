<?php

/**
 * SmartLoader Library
 *
 * A library to create and manage hook in your project.
 *
 * @package     CodeIgniter
 * @author      Remi Heens | www.remiheens.fr | ci@remiheens.fr
 * @copyright   Copyright (c) 2013, Remi Heens.
 * @license     http://creativecommons.org/licenses/by-nc/3.0/
 * @link        http://ci.remiheens.fr/smart-loader
 * @version     Version 2.0
 *
 */
class SmartLoader
{

    /**
     * SmartLoaderJS instance
     * @access public
     * @var \SmartLoaderJS
     */
    public $js;

    /**
     * SmartLoaderCSS instance
     * @access public
     * @var \SmartLoaderCSS
     */
    public $css;

    /**
     * template name
     * @access private
     * @var string 
     */
    private $template;

    public function __construct()
    {
        $this->js = new SmartLoaderJS();
        $this->css = new SmartLoaderCSS();
    }

    /**
     * set the template name
     * each css,jss will be loaded into this directory template
     * @access public
     * @param string $tpl
     */
    public function setTemplate($tpl)
    {
        if(isset($tpl) && !empty($tpl))
        {
            $this->template = $tpl;
            $this->css->setTemplate($tpl);
            $this->js->setTemplate($tpl);
        }
    }

    /**
     * get the current template
     * @return string
     */
    public function getTemplate()
    {
        return $this->template;
    }

    public function forceHTTP()
    {
        $this->css->forceHTTP();
        $this->js->forceHTTP();
    }

}

/**
 * SmartLoaderJS Class
 * Manage your JS into smartloader
 * @package shared
 * @subpackage libraries
 */
class SmartLoaderJS
{

    /**
     * Contain classic javascript
     * @access private
     * @var array
     */
    private $_jsinline = array('header' => '', 'footer' => '');

    /**
     * Contain jquery code
     * @access private
     * @var array
     */
    private $_jsJquery = array('header' => '', 'footer' => '');

    /*
     * all external loaded links
     * @access private
     * @var array
     */
    private $_links = array();

    /*
     * all loaded lib
     * @access private
     * @var array
     */
    private $_libs = array();


    /*
     * the path who contain your public folder (css, js)
     * @access private
     * @var string
     */
    private $_site_folder = '';
    /*
     * the js folder to load
     * @access private
     * @var string
     */
    private $_js_folder = '';

    /*
     * the folder name who contains each template
     * @access private
     * @var string
     */
    private $_tpl_folder = "";

    /*
     * the domain name
     * @access private
     * @var string
     */
    private $_tpl_domain = "";


    /*
     * the template name
     * @access private
     * @var string
     */
    private $_tpl_name = "";
    private $_protocol = '';

    public function __construct()
    {
        // Get CI instance 
        $CI = & get_instance();

        // load configuration file
        $CI->config->load('smartloader');
        // get some configuration options
        $this->_site_folder = $CI->config->item('sl_site_folder');
        $this->_js_folder = $CI->config->item('sljs_js_folder');
        $this->_tpl_folder = $CI->config->item('sl_template_folder');
        $this->_tpl_domain = $CI->config->item('sl_template_domain');
        $this->_tpl_name = $CI->config->item('sl_template_name');

    }

    public function forceHTTP()
    {
        $this->_protocol = 'http:';
    }

    /**
     * set the template name, each js will be loaded into this directory template
     * @access public
     * @param string $tpl
     */
    public function setTemplate($tpl)
    {
        if(isset($tpl) && !empty($tpl))
        {
            $this->_tpl_name = $tpl;
        }
    }

    /**
     * Add js file url
     * @access public
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

    /**
     * Add js lib
     * @access public
     * @param mixed (String|Array)
     */
    public function loadLib($lib)
    {
        if(is_array($lib))
        {
            foreach($lib as $a)
            {
                array_push($this->_libs, $a);
            }
        }
        else
        {
            array_push($this->_libs, $lib);
        }
    }

    /**
     * Add jquery code
     * @access public
     * @param string jquery code
     * @param boolean add this code into header TRUE,not leave blank 
     */
    public function jquery($data, $header = false)
    {
        if($header === true)
        {
            $this->_jsJquery['header'] .= $data . "\n";
        }
        else
        {
            $this->_jsJquery['footer'] .= $data . "\n";
        }
    }

    /**
     * Add javascript code
     * @access public
     * @param string javascript code
     * @param boolean add this code into header TRUE,not leave blank 
     */
    public function js($data, $header = false)
    {
        if($header === true)
        {
            $this->_jsinline['header'] .= $data . "\n";
        }
        else
        {
            $this->_jsinline['footer'] .= $data . "\n";
        }
    }

    /**
     * Output the list of script balise for all js link added
     */
    public function loadLinks()
    {
        foreach($this->_links as $link)
        {
            echo "<script type=\"text/javascript\" src=\"" . $this->_protocol . $link . "\"></script>\n";
        }


        foreach($this->_libs as $lib)
        {
            echo "<script 
                    type=\"text/javascript\" 
                    src=\"" . $this->_protocol . "//" . $this->_tpl_domain . '/' . $this->_tpl_folder . '/' . $this->_tpl_name . '/js/lib/' . $lib . ".js\"></script>\n";
        }
    }

    /**
     * Output javascript code
     * @param boolean output the header code set TRUE, footer leave blank
     */
    public function loadJavascript($header = false)
    {
        if($header === true)
        {
            $str = $this->_getHeader();
        }
        else
        {

            $str = $this->_getFooter();
        }


        echo $str;
        //echo $start.$str.$end;
    }

    private function _getFooter()
    {

        $str = '';
        $start = '';
        $end = '';

        if(!empty($this->_jsinline['footer']) || !empty($this->_jsJquery['footer']))
        {
            $start = "<script type='text/javascript'>\n";
        }

        if(!empty($this->_jsJquery['footer']))
        {
            $str .= "jQuery(document).ready(function($){\n\t" . $this->_jsJquery['footer'] . "\n});\n";
        }

        if(!empty($this->_jsinline['footer']))
        {
            $str .= $this->_jsinline['footer'];
        }

        if(!empty($this->_jsinline['footer']) || !empty($this->_jsJquery['footer']))
        {
            $end = "\n</script>\n";
        }
        return $start . $str. $end;
    }

    private function _getHeader()
    {

        $str = '';
        $start = '';
        $end = '';
        if(!empty($this->_jsinline['header']) || !empty($this->_jsJquery['header']))
        {
            $start = "<script type='text/javascript'>\n";
        }

        if(!empty($this->_jsJquery['header']))
        {
            $str .= "jQuery(document).ready(function($){\n\t" . $this->_jsJquery['header'] . "\n});\n";
        }

        if(!empty($this->_jsinline['header']))
        {
            $str .= $this->_jsinline['header'];
        }

        if(!empty($this->_jsinline['header']) || !empty($this->_jsJquery['header']))
        {
            $end = "\n</script>\n";
        }

        return $start . $str. $end;
    }

    /**
     * load a js like a view
     * SmartLoaderJS try to get this file into the configurated js folder
     * @param string $js_file the js file to load without .js extension
     */
    public function load($js_file)
    {
        if(is_array($js_file))
        {
            $data = '';
            foreach($js_file as $js)
            {
                $file = $this->_site_folder . $this->_tpl_folder . '/' . $this->_tpl_name . '/' . $this->_js_folder . '/' . $js . '.js';
                $data .= file_get_contents($file) . "\n";
            }
        }
        else
        {
            $file = $this->_site_folder . $this->_tpl_folder . '/' . $this->_tpl_name . '/' . $this->_js_folder . '/' . $js_file . '.js';
            $data = file_get_contents($file);
        }
        $this->js($data);
    }

}

/**
 * SmartLoaderCSS Class
 * Manage your CSS into smartloader
 * @package shared
 * @subpackage libraries
 */
class SmartLoaderCSS
{

    // Configuration item
    private $_site_folder = "";
    private $_autoload_folder = "";
    private $_concat_file = "";
    private $_tpl_folder = "";
    private $_tpl_name = "";
    private $_tpl_domain = "";
    private $_link = array();
    private $_autoload_link = '';
    private $CI;
    private $_protocol = '';

    public function __construct()
    {
        // Get CI instance 
        $this->CI = & get_instance();

        // load configuration file
        $this->CI->config->load('smartloader');
        // get some configuration options
        $this->_site_folder = $this->CI->config->item('sl_site_folder');
        $this->_concat_file = $this->CI->config->item('slcss_concat_file');
        $this->_tpl_folder = $this->CI->config->item('sl_template_folder');
        $this->setTemplate($this->CI->config->item('sl_template_name'));
        $this->_tpl_domain = $this->CI->config->item('sl_template_domain');

        $this->_compile_rule = $this->CI->config->item('slcss_compile_rule');

    }

    public function forceHTTP()
    {
        $this->_protocol = 'http:';
    }

    /**
     * set the template name
     * each js will be loaded into this directory template
     * @access public
     * @param string $tpl
     */
    public function setTemplate($tpl)
    {
        if(isset($tpl) && !empty($tpl))
        {
            $this->_tpl_name = $tpl;
        }

        $this->_autoload_folder = $this->_site_folder . $this->_tpl_folder . '/' . $this->_tpl_name;
        $this->_autoload_folder .= '/css/' . $this->CI->config->item('slcss_autoload_folder');

        if(ENVIRONMENT == "development")
        {
            $this->_autoload_link = array();
            if(is_dir($this->_autoload_folder))
            {
                $dir = opendir($this->_autoload_folder);

                while($file = readdir($dir))
                {
                    if(strpos($file, ".css") !== FALSE)
                    {
                        array_push($this->_autoload_link, $file);
                    }
                }


                closedir($dir);
                asort($this->_autoload_link);
                $this->createBaseFile($this->_autoload_link);
            }
        }
    }

    /**
     * Create a css file who contain every autoload css
     * @warning if the base file isn't writable
     * @param array list of css pathfile
     */
    private function createBaseFile($autoload_link)
    {
        if(!empty($this->_concat_file))
        {
            $concat_file = $this->_site_folder . $this->_tpl_folder . '/' . $this->_tpl_name . '/css/' . $this->_concat_file;
            $folder = $this->_site_folder . $this->_tpl_folder . '/' . $this->_tpl_name . '/css/';
            if(is_writable($concat_file) || is_writable($folder))
            {
                $f = fopen($concat_file, "w+");
                foreach($autoload_link as $file)
                {
                    $data = file_get_contents($this->_autoload_folder . '/' . $file);
                    fwrite($f, $data);
                }
                fclose($f);
            }
            else
            {
                log_message('error', 'Can\'t write the concatened file : ' . $concat_file);
            }
        }
    }

    /**
     * Add css file url
     * @param mixed (String|Array)
     */
    public function load($args)
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

    /**
     * Output the list of script balise for all css link added
     */
    public function output()
    {
        $compiled_name = md5(implode('-', $this->_link));
        $compiled_name = $this->compile($compiled_name);
        return $this->_protocol . "//" . $this->_tpl_domain . '/' . $this->_tpl_folder . '/' . $this->_tpl_name . '/css/compiled/' . $compiled_name . '.css';
    }

    /**
     * compile css file with rule define in configuration file
     * @param string $compiled_name
     * @return string
     */
    private function compile($compiled_name)
    {
        $tpl_folder = $this->_site_folder . $this->_tpl_folder . '/' . $this->_tpl_name;
        $file = $tpl_folder . '/css/compiled/' . $compiled_name . '.css';
        if(file_exists($file) === false || $this->verify_version($compiled_name) === true)
        {
            $str = '';
            if(!empty($this->_concat_file))
            {
                $str .= file_get_contents($tpl_folder . '/css/' . $this->_concat_file);
            }
            else
            {
                foreach($this->_autoload_link as $tmp)
                {
                    $str .= file_get_contents($this->_autoload_folder . '/' . $tmp);
                }
            }

            foreach($this->_link as $link)
            {
                $str .= file_get_contents($tpl_folder . '/css/views/' . $link . '.css');
            }


            $f = fopen($file, 'w+');
            fwrite($f, $this->clean($str));
            fclose($f);
        }

        return $compiled_name;
    }

    /**
     * minify css
     * @param string $var
     * @return string
     */
    private function clean($var)
    {
        if(!empty($this->_compile_rule))
        {
            $css = preg_replace(array_keys($this->_compile_rule), array_values($this->_compile_rule), $var);
        }
        else
        {
            $css = $var;
        }
        // Remove comments
        $css = preg_replace('#/\*.*?\*/#s', '', $css);
        // Remove whitespace
        $css = preg_replace('/\s*([{}|:;,])\s+/', '$1', $css);
        // Remove trailing whitespace at the start
        $css = preg_replace('/\s\s+(.*)/', '$1', $css);
        // Remove unnecesairy ;'s
        $css = str_replace(';}', '}', $css);

        return trim($css);
    }

    /**
     * verify if yes or not, we have to recompile file 
     * @param string $compiled_name
     * @return boolean
     */
    private function verify_version($compiled_name)
    {
        $tpl_folder = $this->_site_folder . $this->_tpl_folder . '/' . $this->_tpl_name;
        $file = $tpl_folder . '/css/compiled/' . $compiled_name . '.css';

        if(filemtime($file) < filemtime($tpl_folder . '/css/' . $this->_concat_file))
        {
            return true;
        }


        foreach($this->_link as $link)
        {
            if(filemtime($file) < filemtime($tpl_folder . '/css/views/' . $link . '.css'))
            {
                return true;
            }
        }

        return false;
    }

}