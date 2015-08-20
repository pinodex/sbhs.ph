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

class GalleryControllerProvider implements ControllerProviderInterface {

    public function connect(Application $app) {
        $controller = $app['controllers_factory'];

        $controller->get('/', 'App\Controllers\Site\GalleryController::index')
            ->bind('site.gallery');

        $controller->get('/{id}-{slug}', 'App\Controllers\Site\GalleryController::view')
            ->bind('site.gallery.view');

        $controller->get('/{id}-{slug}/{photoId}', 'App\Controllers\Site\GalleryController::viewPhoto')
            ->bind('site.gallery.view.photo');

        return $controller;
    }

}