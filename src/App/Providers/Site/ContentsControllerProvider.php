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

class ContentsControllerProvider implements ControllerProviderInterface {

    public function connect(Application $app) {
        $controller = $app['controllers_factory'];

        $controller->get('/uploads/{file}', 'App\Controllers\Site\ContentsController::uploads');

        return $controller;
    }

}