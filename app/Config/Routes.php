<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
// $routes->get('/', 'Home::index');

$routes->post('/create','Tasks::create');
$routes->post('/show/(:num)','Tasks::show/$1');
$routes->post('/','Tasks::index');
// $routes->resource('tasks');