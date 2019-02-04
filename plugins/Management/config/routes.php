<?php
use Cake\Routing\RouteBuilder;
use Cake\Routing\Router;
use Cake\Routing\Route\DashedRoute;

Router::plugin(
    'Management',
    ['path' => '/management'],
    function (RouteBuilder $routes) {
        $routes->fallbacks(DashedRoute::class);
    }
);

// Rutas ajax.
Router::prefix('ajax', function ($routes) {
    $routes->connect('/:plugin/:controller/:action/*', [], ['routeClass' => 'InflectedRoute']);

    $routes->fallbacks('InflectedRoute');
});