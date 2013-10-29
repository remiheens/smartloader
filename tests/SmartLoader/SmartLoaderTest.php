<?php

namespace SmartLoader;

class SmartLoaderTest extends \PHPUnit_Framework_TestCase
{
    private function loadSmartLoader()
    {
        
        $configuration = new \SmartLoader\Configuration();
        $configuration->setStaticDirectory(__DIR__.'/../../demo/static');
        $configuration->setAutoloadCSSDirectory('autoload');
        $configuration->setJsViewsDirectory('js/views');
        $configuration->setTemplatesDirectory('tpl');
        $configuration->setDefaultTemplateName('v3');
        $configuration->setStaticDomain('localhost/smartloader/demo/static/');
        $configuration->setEnvironment('development');

        $configuration->setCompilationRules(array(
            '/__LIGHTGREY__/' => '#ccc',
            '/__BLACK__/' => '#000',
        ));
        $smartloader = new \SmartLoader\SmartLoader($configuration);
        return $smartloader;
    }
    
    public function testConstruct()
    {
        $smartloader = $this->loadSmartLoader();
        
        $this->assertInstanceOf('SmartLoader\Component\CSS', $smartloader->css);
        $this->assertInstanceOf('SmartLoader\Component\JS', $smartloader->js);
    }
    
    
    public function testDefaultTemplateLoaded()
    {
        $smartloader = $this->loadSmartLoader();
        $this->assertNotEmpty($smartloader->getTemplate());
    }
    
    
    public function testHeaderOuput()
    {
        $smartloader = $this->loadSmartLoader();
        $this->assertNotEmpty($smartloader->header());
    }
    
    public function testFooterOuput()
    {
        $smartloader = $this->loadSmartLoader();
        $this->assertNotEmpty($smartloader->footer());
    }
}