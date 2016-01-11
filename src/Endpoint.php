<?php

namespace PluginEndpoints;

class Endpoint {

	/**
	 * @var string The (starting) string for this endpoint
	 */
	public $url = '';

	/**
	 * @var array Array of activated plugins for this endpoint
	 */
	public $active_plugins = array();

	/**
	 * @param string $url
	 * @param array  $plugins
	 */
	public function __construct( $url = '/', array $plugins = array() ) {
		$this->url = $url;
		$this->active_plugins = $plugins;
	}
}