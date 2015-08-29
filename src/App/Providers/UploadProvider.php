<?php

/**
 * San Bartolome High School Website
 *
 * @author   Raphael Marco <pinodex@outlook.ph>
 * @link     http://pinodex.github.io
 */

namespace App\Providers;

use Silex\Application;
use Silex\ServiceProviderInterface;

class UploadProvider implements ServiceProviderInterface {

    protected $app, $file, $fileExt, $processedImage;

    public function register(Application $app) {
        $this->app = $app;

        $app['upload'] = $this;
    }

    public function boot(Application $app) {

    }

    public function get($path) {
        return $this->app['uploads.base'] . '/' . ltrim($path, '/');
    }

}