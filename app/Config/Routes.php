<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Raceyear::index');
$routes->get('zavody/rok/(:num)', 'Racedetail::index/$1');

