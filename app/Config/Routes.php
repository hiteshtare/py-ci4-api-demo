<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
// $routes->get('/', 'Home::index');

$routes->post('/create','Tasks::create');
$routes->post('/','Tasks::index');
$routes->post('/show/(:num)','Tasks::show/$1');
$routes->post('/update/(:num)','Tasks::update/$1');
$routes->post('/delete/(:num)','Tasks::delete/$1');
// $routes->resource('tasks');