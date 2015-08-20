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

class EventsControllerProvider implements ControllerProviderInterface {

    public function connect(Application $app) {
        $controller = $app['controllers_factory'];

        $controller->get('/', 'App\Controllers\Dashboard\EventsController::index')
            ->bind('dashboard.events');

        $controller->match('/create', 'App\Controllers\Dashboard\EventsController::create')
            ->bind('dashboard.events.create');

        $controller->match('/edit/{id}', 'App\Controllers\Dashboard\EventsController::edit')
            ->bind('dashboard.events.edit');

        $controller->match('/delete/{id}', 'App\Controllers\Dashboard\EventsController::delete')
            ->bind('dashboard.events.delete');

        return $controller;
    }

}