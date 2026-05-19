<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

// =========================
// PUBLIC ROUTES
// =========================

// halaman utama / feed
$routes->get('/', 'Post::index');

// =========================
// POST FEATURE
// =========================

// halaman buat post
$routes->get(
    'post/create-page',
    'Post::createPage',
    ['filter' => 'login']
);

// simpan post
$routes->post(
    'post/create',
    'Post::create',
    ['filter' => 'login']
);

// detail post
$routes->get(
    'post/detail/(:num)',
    'Post::detail/$1'
);

// =========================
// DRAFT FEATURE
// =========================

// halaman draft user
$routes->get(
    'post/drafts',
    'Post::drafts',
    ['filter' => 'login']
);

// halaman edit draft
$routes->get(
    'post/edit/(:num)',
    'Post::edit/$1',
    ['filter' => 'login']
);

// update draft
$routes->post(
    'post/update/(:num)',
    'Post::update/$1',
    ['filter' => 'login']
);

// publish draft
$routes->get(
    'post/publish/(:num)',
    'Post::publish/$1',
    ['filter' => 'login']
);

// unpublish post (published -> draft)
$routes->get(
    'post/unpublish/(:num)',
    'Post::unpublish/$1',
    ['filter' => 'login']
);

// hapus draft/post
$routes->get(
    'post/delete/(:num)',
    'Post::delete/$1',
    ['filter' => 'login']
);

// =========================
// LIKE (AJAX)
// =========================

$routes->get(
    'post/like/(:num)',
    'Post::like/$1',
    ['filter' => 'login']
);

// =========================
// COMMENT
// =========================

// tambah komentar
$routes->post(
    'post/comment/(:num)',
    'Post::comment/$1',
    ['filter' => 'login']
);

// hapus komentar
$routes->get(
    'post/comment/delete/(:num)',
    'Post::deleteComment/$1',
    ['filter' => 'login']
);

// =========================
// INFINITE SCROLL
// =========================

$routes->get(
    'post/load-more/(:num)',
    'Post::loadMore/$1'
);

// =========================
// ADMIN ROUTES (PROTECTED)
// =========================

$routes->group('admin', ['filter' => 'admin'], function($routes){

    // dashboard admin
    $routes->get('/', 'Admin::index');

    // kelola post
    $routes->get('posts', 'Admin::posts');

    // hapus post
    $routes->get(
        'delete-post/(:num)',
        'Admin::deletePost/$1'
    );

});