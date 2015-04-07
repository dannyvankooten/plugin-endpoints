<?php

use PluginEndpoints\Router,
	PluginEndpoints\Endpoint;

// load mocked functions
require __DIR__ . '/mocks.php';

// Load plugin classes manually
require __DIR__ .'/../src/Router.php';
require __DIR__ .'/../src/Endpoint.php';

class RouterTest extends PHPUnit_Framework_TestCase {

	/**
	 * @covers Router::register_endpoint
	 */
	public function test_register_endpoint() {
		// sample endpoints
		$endpoint_1 = new Endpoint( '/url-1', array(
			'plugin-1/plugin-1.php'
			)
		);

		$endpoint_2 = new Endpoint( '/url-2', array(
				'plugin-1/plugin-1.php',
				'plugin-2/plugin-2.php'
			)
		);

		$router = new Router();

		// register 1st endpoint
		$router->register_endpoint( $endpoint_1->url, $endpoint_1->active_plugins );
		$this->assertEquals( $router->endpoints, array( $endpoint_1 ) );

		// register 2nd endpoint
		$router->register_endpoint( $endpoint_2->url, $endpoint_2->active_plugins );
		$this->assertEquals( $router->endpoints, array( $endpoint_1, $endpoint_2 ) );
	}

	public function test_get_requested_endpoint() {

		// sample endpoints
		$endpoint_1 = new Endpoint( '/url-1', array(
				'plugin-1/plugin-1.php'
			)
		);

		$endpoint_2 = new Endpoint( '/url-2', array(
				'plugin-1/plugin-1.php',
				'plugin-2/plugin-2.php'
			)
		);

		// no registered endpoints
		$router = new Router();
		$this->assertNull( $router->get_requested_endpoint() );

		// 1 registered endpoint but requesting different URL
		$_SERVER['REQUEST_URI'] = '/';
		$router->register_endpoint( $endpoint_1->url, $endpoint_1->active_plugins );
		$this->assertNull( $router->get_requested_endpoint() );

		// 2 registered endpoints, requesting url of 2nd endpoints
		$router->register_endpoint( $endpoint_2->url, $endpoint_2->active_plugins );
		$_SERVER['REQUEST_URI'] = $endpoint_2->url;
		$this->assertEquals( $endpoint_2, $router->get_requested_endpoint() );

		// 2 registered endpoints, requesting subset of 2nd endpoint's url
		$_SERVER['REQUEST_URI'] = $endpoint_2->url . '/additional-url-string';
		$this->assertEquals( $endpoint_2, $router->get_requested_endpoint() );

	}

}