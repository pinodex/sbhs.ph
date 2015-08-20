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

class MainControllerProvider implements ControllerProviderInterface {

    public function connect(Application $app) {
        $controller = $app['controllers_factory'];

        $controller->get('/', 'App\Controllers\Dashboard\MainController::index')
            ->bind('dashboard.index');

        $controller->match('/settings', 'App\Controllers\Dashboard\MainController::settings')
            ->bind('dashboard.settings');

        return $controller;
    }

}