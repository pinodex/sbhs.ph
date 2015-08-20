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

class GalleryControllerProvider implements ControllerProviderInterface {

    public function connect(Application $app) {
        $controller = $app['controllers_factory'];

        $controller->get('/', 'App\Controllers\Dashboard\GalleryController::index')
            ->bind('dashboard.gallery');

        $controller->match('/create', 'App\Controllers\Dashboard\GalleryController::create')
            ->bind('dashboard.gallery.create');

        $controller->match('/view/{id}', 'App\Controllers\Dashboard\GalleryController::view')
            ->bind('dashboard.gallery.view');

        $controller->match('/edit/{id}', 'App\Controllers\Dashboard\GalleryController::edit')
            ->bind('dashboard.gallery.edit');

        $controller->match('/delete/{id}', 'App\Controllers\Dashboard\GalleryController::delete')
            ->bind('dashboard.gallery.delete');

        $controller->delete('/delete/{galleryId}/{photoId}', 'App\Controllers\Dashboard\GalleryController::deletePhoto')
            ->bind('dashboard.gallery.photo.delete');

        $controller->post('/upload', 'App\Controllers\Dashboard\GalleryController::upload')
            ->bind('dashboard.gallery.upload');

        return $controller;
    }

}