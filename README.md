# SmartLoader

SmartLoader is a library that allows you to easily manage your js and css on your site.

When you have lot of js & css to include dynamically, it’s difficult and complex. With SmartLoader, you have some functions to enqueue code or links in controllers and you just have to output the queue in your footer.

If you know wordpress development, this library works like the wp_enqueue function. In your controller, you can enqueue some js and css link, and output this in footer.


## Installation 

* Configure settings in "conf/smartloader.php"
* Autoload the library in "conf/autoload.php"
* Modify MY_Controller to load your default js and css.


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


## Usage

There is a configuration file: conf/smartloader.php. Where you find some configuration for the autoload of css. Indeed, the library autoload css file in a directory and concat this to output in one css file.
The files will be sorted according to their names. Therefore renamed like 0-base.css, 1-style.css, etc..

__Controller example__
```php
class Welcome extends MY_Controller {

	public function index()
	{
		/* want to load a js plugin with css */
		
		$this->smartloader->js->loadLib("myplugin");
		
		$this->smartloader->css->load("welcome");
		
		$this->smartloader->js->jquery('$("#body").css("background-color","red");');
		
		$this->load->view('welcome_message');
	}
	
}
```
__View example__
```html
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title>Welcome to CodeIgniter</title>
	<link href='<?php echo $this->smartloader->css->output(); ?>' rel='stylesheet'/>
</head>
<body>

<div id="container">
	<h1>Welcome to CodeIgniter!</h1>

	<div id="body">
		<p>The page you are looking at is being generated dynamically by CodeIgniter.</p>

		<p>If you would like to edit this page you'll find it located at:</p>
		<code>application/views/welcome_message.php</code>

		<p>The corresponding controller for this page is found at:</p>
		<code>application/controllers/welcome.php</code>

		<p>If you are exploring CodeIgniter for the very first time, you should start by reading the <a href="user_guide/">User Guide</a>.</p>
	</div>

	<p class="footer">Page rendered in <strong>{elapsed_time}</strong> seconds</p>
</div>

<?php echo $this->smartloader->js->loadLinks(); ?>
<?php echo $this->smartloader->js->LoadJavascript(); ?>
</body>
</html>
```
__Specifiy CSS Variable__

Go to your configuration file config/smartloader.php and you can specify variable who will be replaced by its value in your css file. 
