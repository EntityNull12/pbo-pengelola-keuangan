<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

// Auth Routes - Letakkan di ATAS routes yang menggunakan filter auth
$routes->get('/register', 'Register::index');
$routes->post('/register/save', 'Register::save');
$routes->get('/login', 'Login::index');
$routes->post('/login/authenticate', 'Login::authenticate');
$routes->get('/logout', 'Login::logout');

// Dashboard & Protected Routes - Letakkan SETELAH routes auth
$routes->group('', ['filter' => 'auth'], function($routes) {
    $routes->get('/dashboard', 'Dashboard::index');
    $routes->get('/dashboard/pengelola', 'Dashboard::pengelola');
    $routes->post('/dashboard/savePengelola', 'Dashboard::savePengelola');
    $routes->get('/dashboard/riwayat', 'Dashboard::riwayat');
    $routes->get('/dashboard/riwayat/(:num)/edit', 'Dashboard::editRiwayat/$1');
    $routes->put('/dashboard/riwayat/(:num)/update', 'Dashboard::updateRiwayat/$1');
    $routes->post('/dashboard/riwayat/(:num)/update', 'Dashboard::updateRiwayat/$1');
    $routes->delete('/dashboard/delete/(:num)', 'Dashboard::deleteRiwayat/$1');
    
    // Profile Routes
    $routes->get('/profile', 'ProfileController::profile');
    $routes->post('/change-password', 'ChangePassword::update');
    $routes->post('/profile-photo/update', 'ProfileController::updateProfilePhoto');
});

// Public Routes
$routes->get('/', 'About::about');
$routes->get('/about', 'About::about');