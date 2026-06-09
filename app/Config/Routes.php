<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Raceyear::index');
$routes->get('zavody/rok/(:num)', 'Racedetail::index/$1');

$routes->post('racedetail/add', 'Racedetail::add');
$routes->post('racedetail/edit/(:num)', 'Racedetail::edit/$1');
$routes->get('racedetail/delete/(:num)', 'Racedetail::delete/$1');