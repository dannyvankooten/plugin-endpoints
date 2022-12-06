<?php
/**
 * Plugin Name: Plugin Endpoints
 * Description: Create URL endpoints, select enabled plugins per endpoint.
 * Author: Danny van Kooten
 * Version: 1.0.1
 * Author URI: https://github.com/dannyvankooten/plugin-endpoints
 */

namespace PluginEndpoints;

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
	)				// only enable edd & edd sl plugins
);
