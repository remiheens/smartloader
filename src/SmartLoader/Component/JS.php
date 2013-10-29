<?php

namespace SmartLoader\Component;

use SmartLoader\Configuration;

/**
 * JS Class
 * Manage your JS into smartloader
 */
class JS implements IComponent
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
    private $_linksHeader = array();

    /*
     * all external loaded links
     * @access private
     * @var array
     */
    private $_root = array();

    /*
     * all loaded lib
     * @access private
     * @var array
     */
    private $_libs = array();
    private $_libsHeader = array();

    /*
     * the template name
     * @access private
     * @var string
     */
    private $_protocol = '';

    /**
     * @var \SmartLoader\Configuration   
     */
    private $config;

    public function __construct(Configuration $config)
    {

        if(isset($config) && !empty($config))
        {
            $this->config = $config;
            $this->setTemplate($config->getDefaultTemplateName());
        }
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
            $this->config->setDefaultTemplateName($tpl);
        }
    }

    public function root($args)
    {
        if(is_array($args))
        {
            foreach($args as $a)
            {
                if(!in_array($a, $this->_root))
                {
                    array_push($this->_root, $a);
                }
            }
        }
        else
        {
            if(!in_array($args, $this->_root))
            {
                array_push($this->_root, $args);
            }
        }
    }

    /**
     * Add js file url
     * @access public
     * @param mixed (String|Array)
     */
    public function add($args, $header = false)
    {
        $array = &$this->_links;
        if($header === true)
        {
            $array = &$this->_linksHeader;
        }
        if(is_array($args))
        {
            foreach($args as $a)
            {
                if(!in_array($a, $array))
                {
                    array_push($array, $a);
                }
            }
        }
        else
        {
            if(!in_array($args, $array))
            {
                array_push($array, $args);
            }
        }
    }

    /**
     * Add js lib
     * @access public
     * @param mixed (String|Array)
     */
    public function loadLib($lib, $header = false)
    {
        $array = &$this->_libs;
        if($header === true)
        {
            $array = &$this->_libsHeader;
        }

        if(is_array($lib))
        {
            foreach($lib as $a)
            {
                array_push($array, $a);
            }
        }
        else
        {
            array_push($array, $lib);
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

        foreach($this->_root as $link)
        {
            echo "<script type=\"text/javascript\" src=\"" . $this->_protocol . $link . "\"></script>\n";
        }

        foreach($this->_libs as $lib)
        {
            echo "<script type=\"text/javascript\" src=\"" . $this->_protocol . "//" . $this->_tpl_domain . '/' . $this->_tpl_folder . '/common/js/' . $lib . ".js\"></script>\n";
        }

        foreach($this->_links as $link)
        {
            echo "<script type=\"text/javascript\" src=\"" . $this->_protocol . $link . "\"></script>\n";
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
        return $start . $str . $end;
    }

    private function _getHeader()
    {
        $libs = '';
        foreach($this->_linksHeader as $lib)
        {
            $libs .= "<script type=\"text/javascript\" src=\"" . $lib . "\"></script>\n";
        }
        foreach($this->_libsHeader as $lib)
        {
            $libs .= "<script type=\"text/javascript\" src=\"" . $this->_protocol . "//" . $this->_tpl_domain . '/' . $this->_tpl_folder . '/common/js/' . $lib . ".js\"></script>\n";
        }

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

        return $libs . $start . $str . $end;
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
                $file = $this->config->getStaticDirectory() . '/' . $this->config->getTemplatesDirectory() . '/' . $this->config->getDefaultTemplateName() . '/' . $this->config->getJsViewsDirectory() . '/' . $js . '.js';
                $data .= file_get_contents($file) . "\n";
            }
        }
        else
        {
            $file = $this->config->getStaticDirectory() . '/' . $this->config->getTemplatesDirectory() . '/' . $this->config->getDefaultTemplateName() . '/' . $this->config->getJsViewsDirectory() . '/' . $js_file . '.js';
            $data = file_get_contents($file);
        }
        $this->js($data);
    }

}

