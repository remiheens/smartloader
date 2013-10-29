# SmartLoader

[![Latest Stable Version](https://poser.pugx.org/remiheens/smartloader/version.png)](https://packagist.org/packages/remiheens/smartloader) 
[![Total Downloads](https://poser.pugx.org/remiheens/smartloader/d/total.png)](https://packagist.org/packages/remiheens/smartloader)
[![Build Status](https://travis-ci.org/remiheens/SmartLoader.png)](https://travis-ci.org/remiheens/SmartLoader)

SmartLoader is a library that allows you to easily manage your js and css on your site.

When you have lot of js & css to include dynamically, it’s difficult and complex. With SmartLoader, you have some functions to enqueue code or links in controllers and you just have to output the queue in your footer.

If you know wordpress development, this library works like the wp_enqueue function. In your controller, you can enqueue some js and css link, and output this in footer.

## Documentation

The object "SmartLoader" has two objects: "js" and "css", these two attributes are the managers for CSS and Javascript.
```php
public function setTemplate($tpl);
```
set the template folder
```php
public function getTemplate();
```
get the template folder
```php
public function forceHTTP();
```
force http because by default output link starts woith "//domain.tld/..."


### JS
```php
public function setTemplate($tpl);
```
set the template name, each js will be loaded into this directory template
```php
public function forceHTTP();
```
force http because by default output link starts woith "//domain.tld/..."
```php
public function add($args);
```
Add js file url or an array of url
```php
public function loadLib($lib);
```
add a js library juste the name located in template_folder/js/lib/ (see configuration).
```php
public function jquery($data, $header = false);
```
Add jquery code in header or footer
```php
public function js($data, $header = false);
```
Add javascript code in header or footer
```php
public function load($js_file);
```
load a js like a view, SmartLoaderJS try to get this file into the configurated js folder (name without .js)

```php
public function loadLinks();
```
Output the list of script balise for all js link added

```php
public function loadJavascript($header = false);
```
Output javascript code


### CSS
```php
public function setTemplate($tpl);
```
set the template name, each js will be loaded into this directory template

```php
public function forceHTTP();
```
force http because by default output link starts woith "//domain.tld/..."

```php
public function add($args);
```
Add css file url

```php
public function output();
```
Output the link balise ref to css compiled file.

Each CSS added + autoload folder are concatenated to a css file into compiled folder. It wil be regenerated if on file are modify

