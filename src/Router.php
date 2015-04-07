<?php

namespace PluginEndpoints;

class Router {

	/**
	 * @var array
	 */
	public $endpoints = array();

	/**
	 * @var Endpoint
	 */
	public $requested_endpoint = null;

	/**
	 * Constructor
	 */
	public function __construct() {
		add_action( 'muplugins_loaded', array( $this, 'add_filters' ) );
	}

	/**
	 * @param string $url The leading URL for this endpoint
	 * @param array $plugins Array of enabled plugin slugs for this endpoint
	 * @param bool $returns_json Whether this endpoint is expected to return JSON (disables themes, widgets, etc)
	 */
	public function register_endpoint( $url, array $plugins = array(), $returns_json = false ) {
		$endpoint = new Endpoint( $url, $plugins, $returns_json );
		$this->endpoints[] = $endpoint;
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

		$this->requested_endpoint = $this->get_requested_endpoint();

		if( is_null( $this->requested_endpoint ) ) {
			return false;
		}

		// disable cronjobs for this request
		define( 'DISABLE_WP_CRON', true );

		// filter active plugins
		add_filter( 'option_active_plugins', array( $this, 'filter_active_plugins' ) );

		// is this endpoint expected to return json?
		if( $this->requested_endpoint->returns_json ) {

			// don't load themes
			define( 'WP_USE_THEMES', false );

			// stop processing request on `wp_loaded`, this means the plugin returning the JSON should have responded (and exited) by then.
			add_action( 'wp_loaded', array( $this, 'kill_request' ), 1);

			// disable all widgets
			add_filter( 'after_setup_theme', array( $this, 'disable_widgets' ) );
		}
	}

	/**
	 * Only enable the plugins for the currently requested endpoint
	 *
	 * @param array $active_plugins
	 *
	 * @return array
	 */
	public function filter_active_plugins( $active_plugins ) {
		return $this->requested_endpoint->active_plugins;
	}

	/**
	 * Disable all widget (saves additional widget SQL queries)
	 */
	public function disable_widgets() {
		remove_all_actions( 'widgets_init' );
	}

	/**
	 * Kill the request
	 */
	public function kill_request() {

		header( $_SERVER['SERVER_PROTOCOL'] . ' ' . '400 Bad Request' );
		wp_send_json(
			array(
			'status' => 'error',
			'message' => 'Something went wrong.'
			)
		);
		exit;
	}

}