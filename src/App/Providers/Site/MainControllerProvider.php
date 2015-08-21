<?php

/**
 * San Bartolome High School Website
 *
 * @author   Raphael Marco <pinodex@outlook.ph>
 * @link     http://pinodex.github.io
 */

namespace App\Providers\Site;

use Silex\Application;
use Silex\ControllerProviderInterface;

class MainControllerProvider implements ControllerProviderInterface {

    public function connect(Application $app) {
        $controller = $app['controllers_factory'];

        $controller->get('/', 'App\Controllers\Site\MainController::index')
            ->bind('site.index');

        $controller->get('/rss.xml', 'App\Controllers\Site\MainController::rss')
            ->bind('site.rss');

        return $controller;
    }

}