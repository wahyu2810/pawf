<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

// =======================
// HALAMAN USER (POST)
// =======================

// Homepage → tampilkan semua post
$routes->get('/', 'Post::index');

// Detail post berdasarkan slug
$routes->get('/post/(:segment)', 'Post::detail/$1');


// =======================
// HALAMAN ADMIN (CRUD)
// =======================

// Dashboard admin
$routes->group('', ['filter' => 'login'], function($routes){
$routes->get('/admin', 'Admin::index');

// Create
$routes->get('/admin/create', 'Admin::create');
$routes->post('/admin/store', 'Admin::store');

// Edit
$routes->get('/admin/edit/(:num)', 'Admin::edit/$1');
$routes->post('/admin/update/(:num)', 'Admin::update/$1');

// Delete
$routes->get('/admin/delete/(:num)', 'Admin::delete/$1');
});