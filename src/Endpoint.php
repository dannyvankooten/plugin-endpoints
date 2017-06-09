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
	 * @var string string to denote the mode of the plugins. Add to, remove from, or only those declared.
	 */
	public $mode = 'only';

	/**
	 * @param string $url
	 * @param array  $plugins
	 */
	public function __construct( $url = '/', array $plugins = array(), $mode = 'only' ) {
		$this->url = $url;
		$this->active_plugins = $plugins;

		if ( ! is_string( $mode ) || ! in_array( $mode, array( 'only', 'add', 'remove' ) ) ) {
			$this->mode = 'only';
		} else {
			$this->mode = $mode;
		}
	}
}