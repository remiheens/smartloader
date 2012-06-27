<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*
 * SmartLoader Library Configuration
 *
 * Author : Rémi Heens
 * Site : http://www.remiheens.fr
 * Version : 1.0
 * Release Date : 14/04/12
 *
 */
 
 
/*
 * autoload_folder : Path folder of css files to load directly
 * example : $_SERVER['DOCUMENT_ROOT']."/css/autoload/"
 */
$config['slcss_autoload_folder'] = $_SERVER['DOCUMENT_ROOT']."/css/autoload/";

/*
 * concat_file : the new css file wich contains your autoload css files
 * example : /css/base.css
 */
$config['slcss_concat_file'] = "/css/base.css";

/*
 * site_folder : the path to your public folder (index.php folder)
 * example : $_SERVER['DOCUMENT_ROOT']
 */
$config['slcss_site_folder'] = $_SERVER['DOCUMENT_ROOT'];

/* End of file smartloader.php */
/* Location: ./application/config/smartloader.php */