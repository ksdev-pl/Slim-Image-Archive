<?php

/**
 * Authentication & authorization middleware for routes
 *
 * Checks if User is signed in & has required privileges. Otherwise redirects to login page
 *
 * @param int $minRole  Minimum required User role
 *
 * @return callable
 */
function authForRole($minRole) {
    return function () use ($minRole) {
        $app = Slim\Slim::getInstance();
        $auth = new Auth();
        $signedIn = $auth->checkSession();
        if (!$signedIn) {
            $app->flash('error', 'Sign in required');
            $app->redirect('/signin');
        }
        else {
            $user = unserialize($_SESSION['User']);
            switch ($minRole) {
                case (User::ADMIN):
                    if (in_array($user['role'], [User::ADMIN])) {
                        return;
                    }
                    break;
                case (User::EXTENDED):
                    if (in_array($user['role'], [User::ADMIN, User::EXTENDED])) {
                        return;
                    }
                    break;
                case (User::NORMAL):
                    if (in_array($user['role'], [User::ADMIN, User::EXTENDED, User::NORMAL])) {
                        return;
                    }
                    break;
            }

            $app->flash('error', 'You are not authorized to view this page');
            $app->redirect('/signin');
        }
    };
};

// Routes

$app->get(
    '/',
    authForRole(User::NORMAL),
    function() use ($app) {
        $app->redirect('/categories');
    }
);
$app->get(
    '/categories',
    authForRole(User::NORMAL),
    'CategoryController:index'
);
$app->get(
    '/categories/create',
    authForRole(User::NORMAL),
    'CategoryController:create'
);
$app->post(
    '/categories',
    authForRole(User::NORMAL),
    'CategoryController:create'
);
$app->get(
    '/categories/:categoryId/delete',
    authForRole(User::EXTENDED),
    'CategoryController:delete'
);
$app->post(
    '/categories/:categoryId/delete',
    authForRole(User::EXTENDED),
    'CategoryController:delete'
);
$app->get(
    '/categories/:categoryId/update',
    authForRole(User::EXTENDED),
    'CategoryController:update'
);
$app->post(
    '/categories/:categoryId/update',
    authForRole(User::EXTENDED),
    'CategoryController:update'
);

$app->get(
    '/categories/:categoryId',
    authForRole(User::NORMAL),
    function($categoryId) use ($app) {
        $app->redirect('/categories/' . $categoryId . '/albums');
    }
);
$app->get(
    '/categories/:categoryId/albums',
    authForRole(User::NORMAL),
    'AlbumController:index'
);
$app->get(
    '/categories/:categoryId/albums/create',
    authForRole(User::NORMAL),
    'AlbumController:create'
);
$app->post(
    '/categories/:categoryId/albums',
    authForRole(User::NORMAL),
    'AlbumController:create'
);
$app->get(
    '/categories/:categoryId/albums/:albumId/delete',
    authForRole(User::EXTENDED),
    'AlbumController:delete'
);
$app->post(
    '/categories/:categoryId/albums/:albumId/delete',
    authForRole(User::EXTENDED),
    'AlbumController:delete'
);
$app->get(
    '/categories/:categoryId/albums/:albumId/update',
    authForRole(User::EXTENDED),
    'AlbumController:update'
);
$app->post(
    '/categories/:categoryId/albums/:albumId/update',
    authForRole(User::EXTENDED),
    'AlbumController:update'
);

$app->get(
    '/categories/:categoryId/albums/:albumId',
    authForRole(User::NORMAL),
    function($categoryId, $albumId) use ($app) {
        $app->redirect('/categories/' . $categoryId . '/albums/' . $albumId . '/images');
    }
);
$app->get(
    '/categories/:categoryId/albums/:albumId/images',
    authForRole(User::NORMAL),
    'ImageController:index'
);
$app->get(
    '/categories/:categoryId/albums/:albumId/images/create',
    authForRole(User::NORMAL),
    'ImageController:create'
);
$app->post(
    '/categories/:categoryId/albums/:albumId/images',
    authForRole(User::NORMAL),
    'ImageController:create'
);
$app->get(
    '/categories/:categoryId/albums/:albumId/images/:imageId/delete',
    authForRole(User::EXTENDED),
    'ImageController:delete'
);
$app->post(
    '/categories/:categoryId/albums/:albumId/images/:imageId/delete',
    authForRole(User::EXTENDED),
    'ImageController:delete'
);
$app->get(
    '/categories/:categoryId/albums/:albumId/images/:imageId/update',
    authForRole(User::EXTENDED),
    'ImageController:update'
);
$app->post(
    '/categories/:categoryId/albums/:albumId/images/:imageId/update',
    authForRole(User::EXTENDED),
    'ImageController:update'
);
$app->get(
    '/categories/:categoryId/albums/:albumId/images/:imageId',
    authForRole(User::NORMAL),
    'ImageController:show'
);

$app->get(
    '/users',
    authForRole(User::ADMIN),
    'UserController:index'
);
$app->get(
    '/users/create',
    authForRole(User::ADMIN),
    'UserController:create'
);
$app->post(
    '/users',
    authForRole(User::ADMIN),
    'UserController:create'
);
$app->get(
    '/users/:userId/delete',
    authForRole(User::ADMIN),
    'UserController:delete'
);
$app->post(
    '/users/:userId/delete',
    authForRole(User::ADMIN),
    'UserController:delete'
);
$app->get(
    '/users/:userId/update',
    authForRole(User::ADMIN),
    'UserController:update'
);
$app->post(
    '/users/:userId/update',
    authForRole(User::ADMIN),
    'UserController:update'
);

$app->get('/signin', 'UserController:signIn');
$app->post('/signin', 'UserController:signIn');
$app->get('/signout', 'UserController:signOut');