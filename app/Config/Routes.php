<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');
$routes->get('/register', 'Register::index');
$routes->post('/register/save', 'Register::save');
$routes->get('/login', 'Login::index');
$routes->post('/login/authenticate', 'Login::authenticate');

// Dashboard routes
$routes->get('/dashboard', 'Dashboard::index');
$routes->get('/dashboard/pengelola', 'Dashboard::pengelola');

// Pengelola routes
$routes->post('dashboard/savePengelola', 'Dashboard::savePengelola');
$routes->get('/pengelola', 'Dashboard::pengelola');
$routes->post('/pengelola/save', 'Dashboard::savePengelola');

$routes->get('/dashboard/riwayat', 'Dashboard::riwayat'); // Route to access the transaction history

