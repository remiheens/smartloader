<?php 

namespace SmartLoader;

/**
 * SmartLoader Library
 *
 * Author : Rémi Heens
 * Site : http://ci.remiheens.fr/smart-loader
 * Version : 3.0
 * Release Date : 14/04/12
 * update : 29/10/2013
 */
class SmartLoader
{

    /**
     * SmartLoaderJS instance
     * @access public
     * @var \SmartLoader\Component\JS
     */
    public $js;

    /**
     * SmartLoaderCSS instance
     * @access public
     * @var \SmartLoader\Component\CSS
     */
    public $css;

    /**
     * template name
     * @access private
     * @var string 
     */
    private $template;

    public function __construct(Configuration $config)
    {
        $this->js = new \SmartLoader\Component\JS($config);
        $this->css = new \SmartLoader\Component\CSS($config);
        
        $this->template = $config->getDefaultTemplateName();
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

    public function header()
    {
        $str = $this->css->headerOutput();
        $str .= $this->js->headerOutput();
        return $str;
    }
    
    public function footer()
    {
        $str = $this->css->footerOutput();
        $str .= $this->js->footerOutput();
        return $str;
    }
    
}