Plugin Endpoints
=================

This tiny library allows you to register various URL endpoints. When any of these endpoints is requested, only the specified set of enabled plugins will be loaded.

[![Build Status](https://api.travis-ci.org/dannyvankooten/plugin-endpoints.png?branch=master)](https://travis-ci.org/dannyvankooten/plugin-endpoints)
[![Stable Version](https://poser.pugx.org/dannyvankooten/plugin-endpoints/v/stable.svg)](https://packagist.org/packages/dannyvankooten/plugin-endpoints)
[![License](https://poser.pugx.org/dannyvankooten/plugin-endpoints/license.svg)](https://packagist.org/packages/dannyvankooten/plugin-endpoints)
[![Code Climate](https://codeclimate.com/github/dannyvankooten/plugin-endpoints/badges/gpa.svg)](https://codeclimate.com/github/dannyvankooten/plugin-endpoints)

This drastically improves performance for requests to these URL's, only loading the minimum number of required plugins to return the final response.

Results depend on many factors but it is not unusual to experience a 50% gain in speed and a decrease in memory consumption of about 30%.

# Installation

## Using Composer

```json
{
    "require": {
        "dannyvankooten/plugin-endpoints": "dev-master"
    }
}
```

## Manually

```php
require __DIR__ . '/plugin-endpoints/vendor/autoload.php';
```

# Usage

Because the library needs control over which plugins are loaded, you need to instantiate the Router class and register your endpoints from your `mu-plugins` folder. 

The following example registers an endpoint for Easy Digital Downloads & the Software Licensing add-on.

```php

// file: `/wp-content/mu-plugins/endpoints.php`

// load the autoloader manually (or use Composer!)
require __DIR__ . '/plugin-endpoints/vendor/autoload.php';

// instantiate the routing class
$router = new PluginEndpoints\Router;

// register an endpoint
$router->register_endpoint( 
	'/edd-sl-api', 	// listen to requests starting with /edd-sl-api
	array(
		'easy-digital-downloads/easy-digital-downloads.php',
		'edd-software-licensing/edd-software-licenses.php' 
	),				// only enable edd & edd sl plugins
	true 			// this response should return json, do not load themes
);

// done! 
```

Now, point your requests at `https://your-site/edd-sl-api....` and you'll see that only the specified plugins are enabled for the request. 

```
GET http://local.wp/edd-sl-api/?edd_action=activate_license...
```


