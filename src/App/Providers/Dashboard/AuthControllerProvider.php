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

class AuthControllerProvider implements ControllerProviderInterface {

    public function connect(Application $app) {
        $controller = $app['controllers_factory'];

        $controller->match('/login', 'App\Controllers\Dashboard\AuthController::login')
            ->bind('dashboard.auth.login');

        $controller->match('/logout:{token}', 'App\Controllers\Dashboard\AuthController::logout')
            ->bind('dashboard.auth.logout');

        return $controller;
    }

}