<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');
$routes->get('/register', 'Register::index');
$routes->post('/register/save', 'Register::save');
$routes->get('/login', 'Login::index');
$routes->post('/login/authenticate', 'Login::authenticate'); // Perbaikan di sini
$routes->get('/dashboard', 'Dashboard::index');
$routes->get('/dashboard/pengelola', 'Dashboard::pengelola');
$routes->get('/about', 'About::about');
$routes->get('/profile', 'Profile::index');
$routes->post('change-password', 'ChangePassword::update');
$routes->post('profile-photo/update', 'ProfileController::updateProfilePhoto');