<?php

// Configure error reporting
error_reporting(E_ERROR | E_WARNING | E_PARSE);

// Configure session
ini_set('session.cookie_httponly', 1);
session_cache_limiter(false);
session_start();

// Autoload
require ROOT . '/vendor/autoload.php';

// Configure Slim & Twig
$app = new \Slim\Slim([
    'debug' => true,
    'view' => new \Slim\Views\Twig(),
    'templates.path' => ROOT . '/app/views',
    'log.writer' => new \Slim\Extras\Log\DateTimeFileWriter([
        'path' => ROOT . '/tmp/logs',
        'name_format' => 'Y-m-d',
        'message_format' => '%label% - %date% - %message%'
    ]),
    'log.level' => \Slim\Log::WARN,
    'log.enabled' => true
]);
$view = $app->view();
$view->parserOptions = [
    'debug' => true,
    'cache' => ROOT . '/tmp/cache',
    'auto_reload' => true
];
$view->parserExtensions = [
    new Twig_Extension_Debug()
];

// Set custom error handlers
$app->error(function (Exception $e) use ($app) {
    if (($e instanceof RecordNotFoundException) || ($e instanceof FieldValidationException)) {
        $app->flash('error', $e->getMessage());
        $app->redirect('/categories');
    }
    else {
        $log = $app->getLog();
        $log->error($e);
        $app->render('error.html.twig', [
            'error' => 'A website error has occurred.
            The website administrator has been notified of the issue.
            Sorry for the temporary inconvenience.'
        ]);
    }
});
$app->notFound(function () use ($app) {
    $app->render('error.html.twig', ['error' => '404 Not Found']);
});

// Add DB to DI container
$app->container->singleton('db', function () {
    return new DB(
        '192.168.33.10',
        'root',
        'root',
        'gallery'
    );
});

// Configure routes
require ROOT . '/app/routes/routes.php';

// Run
$app->run();