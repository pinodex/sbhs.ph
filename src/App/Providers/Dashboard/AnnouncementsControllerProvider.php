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

class AnnouncementsControllerProvider implements ControllerProviderInterface {

    public function connect(Application $app) {
        $controller = $app['controllers_factory'];

        $controller->get('/', 'App\Controllers\Dashboard\AnnouncementsController::index')
            ->bind('dashboard.announcements');

        $controller->match('/create', 'App\Controllers\Dashboard\AnnouncementsController::create')
            ->bind('dashboard.announcements.create');

        $controller->match('/edit/{id}', 'App\Controllers\Dashboard\AnnouncementsController::edit')
            ->bind('dashboard.announcements.edit');

        $controller->match('/delete/{id}', 'App\Controllers\Dashboard\AnnouncementsController::delete')
            ->bind('dashboard.announcements.delete');

        return $controller;
    }

}