#!/usr/bin/env php
<?php

$templateName = $argv[1];
$path = $argv[2];

// trailing slash
if(!preg_match('/\/$/',$path))
{
    $path .= '/';
}

$path .= $templateName.'/';

$owner = exec('whoami');

exec('mkdir ' . $path);
exec('mkdir ' . $path . 'css');
exec('mkdir ' . $path . 'js');
exec('mkdir ' . $path . 'css/autoload');
exec('mkdir ' . $path . 'css/compiled');
exec('mkdir ' . $path . 'css/img');
exec('mkdir ' . $path . 'css/views');
exec('touch ' . $path . 'css/base.css');
exec('mkdir ' . $path . 'js/lib');
exec('mkdir ' . $path . 'js/views');
/*
exec('chmod -R 775 '.$path.'css/compiled');
exec('chmod -R 775 '.$path.'css/base.css');
exec('chown -R '.$path.'css/base.css');*/

