<?php

/**
 * San Bartolome High School Website
 *
 * @author   Raphael Marco <pinodex@outlook.ph>
 * @link     http://pinodex.github.io
 */

/*
 * This is used to workaround the conflict between static files
 * and routing on PHP 5.4+ built-in server.
 */
if (php_sapi_name() === 'cli-server' && is_file(__DIR__ . preg_replace('#(\?.*)$#', '', $_SERVER['REQUEST_URI']))) {
    return false;
}

define('ROOT', dirname(__DIR__));
define('PUB', ROOT . '/public');
define('SRC', ROOT . '/src');
define('APP', SRC . '/App');

require_once ROOT . '/vendor/autoload.php';

$app = require SRC . '/app.php';
$app->run();