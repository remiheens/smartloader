<?php

if(!defined('BASEPATH'))
    exit('No direct script access allowed');

/*
 * autoload_folder : Path folder of css files to load directly
 * example : $_SERVER['DOCUMENT_ROOT']."/css/autoload/"
 */
$config['slcss_autoload_folder'] = 'autoload';

/*
 * concat_file : the new css file wich contains your autoload css files
 * example : /css/base.css
 */
$config['slcss_concat_file'] = "base.css";

/*
 * site_folder : the path to your public folder (index.php folder)
 * example : $_SERVER['DOCUMENT_ROOT']
 */
$config['sl_site_folder'] = $_SERVER['DOCUMENT_ROOT'].'/SmartLoader/';

/*
 * js_folder : the path to your javascript folder (index.php folder)
 */
$config['sljs_js_folder'] = 'js/views';

/*
 * template_folder : folder name who contains your templates 
 */
$config['sl_template_folder'] = 'tpl';

/*
 * template_name : the default template name
 */
$config['sl_template_name'] = 'default';

/*
 * js_template_domain : from which domain would you load statics files
 */
$config['sl_template_domain'] = 'localhost/SmartLoader';

/*
 * compile_rule : you can set "variables" who will be replaced by its value
 * h1{
 *	color: __RED__;
 * }
 */
$config['slcss_compile_rule'] = array(
    '/__RED__/' => '#f00',
);

/* End of file smartloader.php */
/* Location: ./application/config/smartloader.php */