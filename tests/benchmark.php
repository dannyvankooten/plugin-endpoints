<?php

// load & mock
require __DIR__ . '/../vendor/autoload.php';
require __DIR__ . '/mocks.php';
$_SERVER['REQUEST_URI'] = '/';

// read args
global $argv;
$n = isset( $argv[1] ) ? intval( $argv[1] ) : 1000;

$start = microtime(true);


$router = new \PluginEndpoints\Router();

// register 1000 endpoints
for( $i=0; $i < $n; $i++ ) {
    $router->register_endpoint( sprintf( "/url-%d", $i ), array() );
}

// find (un)matched endpoint
$router->get_requested_endpoint();

$end = microtime(true);

echo sprintf( "Benchmark finished in %.2fs", ( $end - $start ) * 1000 ) . PHP_EOL;