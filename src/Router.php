<?php

namespace PluginEndpoints;

class Router {

	/**
	 * @var array
	 */
	protected $endpoints = array();

	/**
	 * Constructor
	 */
	public function __construct() {
		add_action( 'muplugins_loaded', array( $this, 'add_filters' ) );
	}

	/**
	 * @param string $url The leading URL for this endpoint
	 * @param array $plugins Array of enabled plugin slugs for this endpoint
	 */
	public function register_endpoint( $url, array $plugins = array() ) {
		$endpoint = new Endpoint( $url, $plugins );
		$this->endpoints[] = $endpoint;
	}

	/**
	 * @return Endpoint|null
	 */
	public function get_requested_endpoint() {

		foreach( $this->endpoints as $endpoint ) {
			preg_match( '@' . $endpoint->url . '@', $_SERVER['REQUEST_URI'], $matches );

			if( ! empty( $matches ) ) {
				return $endpoint;
			}
		}

		return null;
	}

	/**
	 * If one of the registered endpoints is selected, add the required filters.
	 *
	 * @return bool
	 */
	public function add_filters() {

		$endpoint = $this->get_requested_endpoint();

		if( ! $endpoint ) {
			return false;
		}

		// disable cronjobs for this request
		if(!defined('DISABLE_WP_CRON')){
			define( 'DISABLE_WP_CRON', true );
		}

		// filter active plugins
		add_filter( 'option_active_plugins', function() use ( $endpoint ) {
			return $endpoint->active_plugins;
		});
	}

}
