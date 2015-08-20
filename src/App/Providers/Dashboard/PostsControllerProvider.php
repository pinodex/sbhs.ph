<?php

/**
 * San Bartolome High School Website
 *
 * @author   Raphael Marco <pinodex@outlook.ph>
 * @link     http://pinodex.github.io
 */

namespace App\Providers\Dashboard;

use Silex\Application;
use Silex\ControllerProviderInterface;

class PostsControllerProvider implements ControllerProviderInterface {

    public function connect(Application $app) {
        $controller = $app['controllers_factory'];

        $controller->get('/', 'App\Controllers\Dashboard\PostsController::index')
            ->bind('dashboard.posts');

        $controller->match('/create', 'App\Controllers\Dashboard\PostsController::write')
            ->bind('dashboard.posts.write');

        $controller->match('/edit/{id}', 'App\Controllers\Dashboard\PostsController::write')
            ->bind('dashboard.posts.edit');

        $controller->match('/delete/{id}', 'App\Controllers\Dashboard\PostsController::delete')
            ->bind('dashboard.posts.delete');

        $controller->post('/upload', 'App\Controllers\Dashboard\PostsController::upload')
            ->bind('dashboard.posts.upload');

        return $controller;
    }

}