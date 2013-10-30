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
    private $_links = array('header'=>array(), 'footer'=>array());
    
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

    /**
     * load a js like a view
     * SmartLoaderJS try to get this file into the configurated js folder
     * @param string $js_file the js file to load without .js extension
     */
    public function loadView($js_file)
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
    
    /**
     * Add js file url
     * @access public
     * @param mixed (String|Array)
     */
    public function addURL($url,$priority = 0, $header = false)
    {
        $array = &$this->_links['footer'];
        if($header === true)
        {
            $array = &$this->_links['header'];
        }
        
        if(isset($url) && !empty($url))
        { 
            if(isset($array[$priority]))
            {
                // split array
                $first = array_slice($array, 0, $priority);
                $second = array_slice($array, $priority);

                $tmp = array_merge(array(), $first);
                $tmp[$priority] = $url;
                $array = array_merge($tmp, $second);
            }
            else
            {
                $array[$priority] = $url;
            }
        }
    }

    /**
     * Add js lib
     * @access public
     * @param mixed (String|Array)
     */
    public function addLibrary($lib, $priority = 0, $header = false)
    {
        $array = &$this->_links['footer'];
        if($header === true)
        {
            $array = &$this->_links['header'];
        }
        
        if(isset($lib) && !empty($lib))
        { 
            $lib = $this->_protocol.'//'.$this->config->getStaticDomain().'/'.$this->config->getTemplatesDirectory().'/common/js/'.$lib;
         
            if(isset($array[$priority]))
            {
                // split array
                $first = array_slice($array, 0, $priority);
                $second = array_slice($array, $priority);

                $tmp = array_merge(array(), $first);
                $tmp[$priority] = $lib;
                $array = array_merge($tmp, $second);
            }
            else
            {
                $array[$priority] = $lib;
            }
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
    

    public function headerOutput()
    {
        return $this->_output('header');
    }
    
    public function footerOutput()
    {
        return $this->_output('footer');
    }
    
    private function _output($type)
    {
        $libs = '';
        ksort($this->_links[$type]);
        $this->_links[$type] = array_reverse($this->_links[$type]);
        foreach($this->_links[$type] as $lib)
        {
            $libs .= "<script type=\"text/javascript\" src=\"" . $lib . "\"></script>\n";
        }

        $str = '';
        
        $start = "<script type='text/javascript'>";

        if(!empty($this->_jsJquery[$type]))
        {
            $str .= "\njQuery(document).ready(function($){\n\t" . $this->_jsJquery[$type] . "\n});";
        }

        if(!empty($this->_jsinline[$type]))
        {
            $str .= "\n".$this->_jsinline[$type];
        }

        $end = "</script>";
        return $libs . $start . $str . $end;
    }
}

