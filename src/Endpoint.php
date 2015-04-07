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
	 * @var bool Whether the request should return JSON (disables loading of themes if enabled)
	 */
	public $returns_json = false;

	/**
	 * @param string $url
	 * @param array  $plugins
	 * @param bool $returns_json
	 */
	public function __construct( $url = '/', array $plugins = array(), $returns_json = false ) {
		$this->url = $url;
		$this->active_plugins = $plugins;
		$this->returns_json = $returns_json;
	}
}