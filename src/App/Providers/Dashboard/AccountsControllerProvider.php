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

class AccountsControllerProvider implements ControllerProviderInterface {

    public function connect(Application $app) {
        $controller = $app['controllers_factory'];

        $controller->get('/', 'App\Controllers\Dashboard\AccountsController::index')
            ->bind('dashboard.accounts');

        $controller->match('/create', 'App\Controllers\Dashboard\AccountsController::create')
            ->bind('dashboard.accounts.create');

        $controller->match('/edit/{id}', 'App\Controllers\Dashboard\AccountsController::edit')
            ->bind('dashboard.accounts.edit');

        $controller->match('/delete/{id}', 'App\Controllers\Dashboard\AccountsController::delete')
            ->bind('dashboard.accounts.delete');

        return $controller;
    }

}