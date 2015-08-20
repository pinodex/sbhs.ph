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

class BannersControllerProvider implements ControllerProviderInterface {

    public function connect(Application $app) {
        $controller = $app['controllers_factory'];

        $controller->get('/', 'App\Controllers\Dashboard\BannersController::index')
            ->bind('dashboard.banners');

        $controller->match('/create', 'App\Controllers\Dashboard\BannersController::create')
            ->bind('dashboard.banners.create');

        $controller->match('/edit/{id}', 'App\Controllers\Dashboard\BannersController::edit')
            ->bind('dashboard.banners.edit');

        $controller->match('/delete/{id}', 'App\Controllers\Dashboard\BannersController::delete')
            ->bind('dashboard.banners.delete');

        return $controller;
    }

}