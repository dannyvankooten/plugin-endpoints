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
	 * @param string $mode 'only', 'add', 'remove' to only have the plugins listed, to add those plugins to the ones
	 *                     already loaded, or to reomve them from being loaded
	 */
	public function register_endpoint( $url, array $plugins = array(), $mode = 'only' ) {
		$endpoint = new Endpoint( $url, $plugins, $mode );
		$this->endpoints[] = $endpoint;
	}

	public function register_endpoint_add_plugins( $url, array $plugins = array() ) {
		$this->register_endpoint( $url, $plugins, 'add' );
	}

	public function register_endpoint_remove_plugins( $url, array $plugins = array() ) {
		$this->register_endpoint( $url, $plugins, 'remove' );
	}

	public function register_endpoint_only_plugins( $url, array $plugins = array() ) {
		$this->register_endpoint( $url, $plugins, 'only' );
	}

	/**
	 * @return Endpoint|null
	 */
	public function get_requested_endpoint() {

		foreach( $this->endpoints as $endpoint ) {
			if( strpos( $_SERVER['REQUEST_URI'], $endpoint->url ) === 0 ) {
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
		add_filter( 'option_active_plugins', function( $active_plugins ) use ( $endpoint ) {
			$function_name = $endpoint->mode . '_plugins';
			$plugins = $this->$function_name( $active_plugins, $endpoint->active_plugins );
			return $plugins;
		});
	}

	private function add_plugins( $active_plugins, $declared ) {
		return array_unique( array_merge( $active_plugins, $declared ) );
	}

	private function remove_plugins( $active_plugins, $declared ) {
		return array_diff( $active_plugins, $declared );
	}

	private function only_plugins( $active_plugins, $declared ) {
		return $declared;
	}

}
