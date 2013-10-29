<?php

namespace SmartLoader\Component;

use SmartLoader\Configuration;

/**
 * SmartLoaderCSS Class
 * Manage your CSS into smartloader
 */
class CSS implements IComponent
{

    // Configuration item
    /**
     * @var \SmartLoader\Configuration   
     */
    private $config;
    private $_link = array();
    private $_autoload_link = '';
    private $_protocol = '';
    private $_autoload_folder = '';

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
     * set the template name
     * each js will be loaded into this directory template
     * @access public
     * @param string $tpl
     */
    public function setTemplate($tpl)
    {
        if(isset($tpl) && !empty($tpl))
        {
            $this->config->setDefaultTemplateName($tpl);
        }

        $this->_autoload_folder = $this->config->getStaticDirectory() . '/' . $this->config->getTemplatesDirectory() . '/' . $this->config->getDefaultTemplateName();
        $this->_autoload_folder .= '/css/' . $this->config->getAutoloadCSSDirectory();

        if($this->config->getEnvironment() == "development")
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
        $concatenedCSSFilename = $this->config->getConcatenedCSSFilename();
        if(!empty($concatenedCSSFilename))
        {
            $concat_file = $this->config->getStaticDirectory() . '/' . $this->config->getTemplatesDirectory() . '/' . $this->config->getDefaultTemplateName() . '/css/' . $this->config->getConcatenedCSSFilename();
            $folder = $this->config->getStaticDirectory() . '/' . $this->config->getTemplatesDirectory() . '/' . $this->config->getDefaultTemplateName() . '/css/';

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
                throw new \Exception('Can\'t write the concatened file : ' . $concat_file);
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
        $compiled_name = $this->compile(md5(implode('-', $this->_link)));
        return $this->_protocol . "//" . $this->config->getStaticDomain() . '/' . $this->config->getTemplatesDirectory() . '/' . $this->config->getDefaultTemplateName() . '/css/compiled/' . $compiled_name . '.css';
    }

    /**
     * compile css file with rule define in configuration file
     * @param string $compiled_name
     * @return string
     */
    private function compile($compiled_name)
    {
        $tpl_folder = $this->config->getStaticDirectory() . '/' . $this->config->getTemplatesDirectory() . '/' . $this->config->getDefaultTemplateName();
        $file = $tpl_folder . '/css/compiled/' . $compiled_name . '.css';
        if(file_exists($file) === false || $this->verify_version($compiled_name) === true)
        {
            $str = '';
            $concatenedCSSFilename = $this->config->getConcatenedCSSFilename();
            if(!empty($concatenedCSSFilename))
            {
                $str .= file_get_contents($tpl_folder . '/css/' . $this->config->getConcatenedCSSFilename());
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
                if(file_exists($tpl_folder . '/css/views/' . $link . '.css'))
                {
                    $str .= file_get_contents($tpl_folder . '/css/views/' . $link . '.css');
                }
                else
                {
                    throw new Exception('File : ' . $tpl_folder . '/css/views/' . $link . '.css');
                }
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
        $compilationRules = $this->config->getCompilationRules();
        if(!empty($compilationRules))
        {
            $css = preg_replace(array_keys($this->config->getCompilationRules()),
                    array_values($this->config->getCompilationRules()), $var);
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
        $tpl_folder = $this->config->getStaticDirectory() . '/' . $this->config->getTemplatesDirectory() . '/' . $this->config->getDefaultTemplateName();
        $file = $tpl_folder . '/css/compiled/' . $compiled_name . '.css';

        if(filemtime($file) < filemtime($tpl_folder . '/css/' . $this->config->getConcatenedCSSFilename()))
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