<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');

// Auth Routes
$routes->get('/register', 'Register::index');
$routes->post('/register/save', 'Register::save');
$routes->get('/login', 'Login::index');
$routes->post('/login/authenticate', 'Login::authenticate');

// Dashboard Routes
$routes->get('/dashboard', 'Dashboard::index', ['filter' => 'auth']);
$routes->get('/dashboard/pengelola', 'Dashboard::pengelola', ['filter' => 'auth']);
$routes->post('/dashboard/savePengelola', 'Dashboard::savePengelola', ['filter' => 'auth']);

// Riwayat Routes
$routes->get('/dashboard/riwayat', 'Dashboard::riwayat', ['filter' => 'auth']);
// Route baru untuk AJAX get data edit
$routes->get('/dashboard/riwayat/(:num)/edit', 'Dashboard::editRiwayat/$1', ['filter' => 'auth']);
// Route untuk update data
$routes->put('/dashboard/riwayat/(:num)/update', 'Dashboard::updateRiwayat/$1', ['filter' => 'auth']);
// atau jika menggunakan POST method
$routes->post('/dashboard/riwayat/(:num)/update', 'Dashboard::updateRiwayat/$1', ['filter' => 'auth']);
$routes->delete('/dashboard/delete/(:num)', 'Dashboard::deleteRiwayat/$1', ['filter' => 'auth']);

// Profile Routes
$routes->get('/profile', 'Profile::index', ['filter' => 'auth']);
$routes->post('/change-password', 'ChangePassword::update', ['filter' => 'auth']);
$routes->post('/profile-photo/update', 'ProfileController::updateProfilePhoto', ['filter' => 'auth']);

// Other Routes
$routes->get('/about', 'About::about');
$routes->get('/logout', 'Login::logout');