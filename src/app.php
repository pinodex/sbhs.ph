<?php

/**
 * San Bartolome High School Website
 *
 * @author   Raphael Marco <pinodex@outlook.ph>
 * @link     http://pinodex.github.io
 */

use Symfony\Component\Debug\ErrorHandler;
use Symfony\Component\Debug\ExceptionHandler;

/*
 * This file bootstraps the application.
 * Components are initialized here.
 */

ErrorHandler::register();

$app = new Silex\Application();

require SRC . '/config.php';
$app['assets.map'] = require SRC . '/assets.php';

ExceptionHandler::register($app['debug']);
date_default_timezone_set($app['timezone']);

$app->register(new Silex\Provider\TwigServiceProvider());
$app->register(new Silex\Provider\FormServiceProvider());
$app->register(new Silex\Provider\UrlGeneratorServiceProvider());
$app->register(new Silex\Provider\SessionServiceProvider());

if ($app['profiler.enable']) {
    $app->register(new Silex\Provider\HttpFragmentServiceProvider());
    $app->register(new Silex\Provider\ServiceControllerServiceProvider());
    $app->register(new Silex\Provider\WebProfilerServiceProvider());
}

$app->register(new BitolaCo\Silex\CapsuleServiceProvider(), array(
    'capsule.connection' => $app['db.config']
));

/*
 * Initialize capsule for database connection
 */
$app['capsule'];

//$app->register(new Silex\Provider\TranslationServiceProvider());

$app->register(new Silex\Provider\ValidatorServiceProvider());

$app['session.storage.handler'] = $app->share(function () use ($app) {
    return new Symfony\Component\HttpFoundation\Session\Storage\Handler\PdoSessionHandler(
        $app['capsule']->connection()->getPdo(),
        $app['session.db_options'],
        $app['session.storage.options']
    );
});

$app->register(new App\Providers\AssetsProvider());
$app->register(new App\Providers\HelperProvider());
$app->register(new App\Providers\UploadProvider());
$app->register(new App\Providers\AccountsProvider());
$app->register(new App\Providers\PostsProvider());
$app->register(new App\Providers\AnnouncementsProvider());
$app->register(new App\Providers\GalleryProvider());
$app->register(new App\Providers\PhotosProvider());
$app->register(new App\Providers\EventsProvider());
$app->register(new App\Providers\BannersProvider());

$app['flashbag'] = $app->share(function () use ($app) {
    return $app['session']->getFlashBag();
});

// Workaround for HHVM incompatibility.
$app['twig']->addFilter('trans*', new \Twig_Filter_Function(function($string, $string2) {
    return $string2; 
}));

$app['twig.loader.filesystem']->addPath(SRC . '/views/site/', 'site');
$app['twig.loader.filesystem']->addPath(SRC . '/views/dashboard/', 'dashboard');

$app['session']->start();

require SRC . '/routes.php';
return $app;