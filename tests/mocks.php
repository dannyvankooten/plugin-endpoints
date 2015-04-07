<?php

$GLOBALS['wp_actions'] = array();
function add_action( $hook, $callback, $priority = 10, $arguments = 2 ) {
	$GLOBALS['wp_actions'][$hook] = $callback;
}