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

class AboutControllerProvider implements ControllerProviderInterface {

    public function connect(Application $app) {
        $controller = $app['controllers_factory'];

        $controller->get('/', 'App\Controllers\Site\AboutController::index')
            ->bind('site.about');

        $controller->get('/history', 'App\Controllers\Site\AboutController::history')
            ->bind('site.about.history');

        $controller->get('/mission-and-vision', 'App\Controllers\Site\AboutController::missionAndVision')
            ->bind('site.about.mav');

        $controller->get('/stats', 'App\Controllers\Site\AboutController::stats')
            ->bind('site.about.stats');

        $controller->get('/authors', 'App\Controllers\Site\AboutController::authors')
            ->bind('site.about.authors');

        $controller->get('/authors/{id}-{slug}', 'App\Controllers\Site\AboutController::authorView')
            ->bind('site.about.authors.view');

        return $controller;
    }

}