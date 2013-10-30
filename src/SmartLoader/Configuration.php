<?php

namespace SmartLoader;

class Configuration
{

    private $environment;
    private $staticDirectory;
    private $concatenedCSSFilename = 'base.css';
    private $concatenedJSFilename = 'base.js';
    private $autoloadCSSDirectory;
    private $jsViewsDirectory;
    private $templatesDirectory;
    private $defaultTemplateName;
    private $staticDomain;
    private $compilationRules;

    public function loadFromJSON($json)
    {
        $config = (array) json_decode($json);
        $this->loadFromArray($config);
    }

    public function loadFromArray($config)
    {
        foreach($config as $var => $val)
        {
            if(isset($this->$var))
            {
                $this->$var = $val;
            }
        }
    }

    public function getStaticDirectory()
    {
        return $this->staticDirectory;
    }

    public function setStaticDirectory($staticDirectory)
    {
        $this->staticDirectory = $staticDirectory;
        return $this;
    }

    public function getConcatenedCSSFilename()
    {
        return $this->concatenedCSSFilename;
    }

    public function getConcatenedJSFilename()
    {
        return $this->concatenedJSFilename;
    }

    public function getAutoloadCSSDirectory()
    {
        return $this->autoloadCSSDirectory;
    }

    public function setAutoloadCSSDirectory($autoloadCSSDirectory)
    {
        $this->autoloadCSSDirectory = $autoloadCSSDirectory;
        return $this;
    }

    public function getJsViewsDirectory()
    {
        return $this->jsViewsDirectory;
    }

    public function setJsViewsDirectory($jsViewsDirectory)
    {
        $this->jsViewsDirectory = $jsViewsDirectory;
        return $this;
    }

    public function getTemplatesDirectory()
    {
        return $this->templatesDirectory;
    }

    public function setTemplatesDirectory($templatesDirectory)
    {
        $this->templatesDirectory = $templatesDirectory;
        return $this;
    }
    public function getDefaultTemplateName()
    {
        return $this->defaultTemplateName;
    }

    public function setDefaultTemplateName($defaultTemplateName)
    {
        $this->defaultTemplateName = $defaultTemplateName;
        return $this;
    }

    public function getStaticDomain()
    {
        return $this->staticDomain;
    }

    public function setStaticDomain($staticDomain)
    {
        $this->staticDomain = $staticDomain;
        return $this;
    }

    public function getCompilationRules()
    {
        return $this->compilationRules;
    }

    public function setCompilationRules($compilationRules)
    {
        $this->compilationRules = $compilationRules;
        return $this;
    }
    
    public function getEnvironment()
    {
        return $this->environment;
    }

    public function setEnvironment($environment)
    {
        $this->environment = $environment;
        return $this;
    }
}

?>