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

class PostsControllerProvider implements ControllerProviderInterface {

    public function connect(Application $app) {
        $controller = $app['controllers_factory'];

        $controller->get('/', 'App\Controllers\Site\PostsController::index')
            ->bind('site.posts');

        $controller->get('/{id}-{slug}', 'App\Controllers\Site\PostsController::read')
            ->bind('site.posts.read');

        return $controller;
    }

}