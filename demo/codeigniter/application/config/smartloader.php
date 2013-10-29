<?php
$config['smartloader_configuration'] = '';

$configuration = new \SmartLoader\Configuration();
$configuration->setStaticDirectory(FCPATH.'../static');
$configuration->setAutoloadCSSDirectory('autoload');
$configuration->setJsViewsDirectory('js/views');
$configuration->setTemplatesDirectory('tpl');
$configuration->setDefaultTemplateName('v3');
$configuration->setStaticDomain('localhost/smartloader/demo/static/');
$configuration->setEnvironment(ENVIRONMENT);

$configuration->setCompilationRules(array(
    '/__LIGHTGREY__/' => '#ccc',
    '/__BLACK__/' => '#000',
));

$CI = &get_instance();
$CI->smartloader = new SmartLoader\SmartLoader($configuration);