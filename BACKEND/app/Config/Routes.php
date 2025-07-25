<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');

$routes->group('customers', ['filter' => 'authFilter'], static function ($routes) {
    $routes->get('search', 'Customers::search');
    $routes->post('create', 'Customers::create');
    $routes->put('update', 'Customers::update');
    $routes->delete('delete', 'Customers::delete');
});

$routes->group('transactions', static function ($routes) {
    $routes->get('search', 'Transactions::search');
    $routes->post('create', 'Transactions::create');
});

$routes->group('auth', static function ($routes) {
    $routes->post('login', 'Auth::login');
    $routes->post('register', 'Auth::register');
});
