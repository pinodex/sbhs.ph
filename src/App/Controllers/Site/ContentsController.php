<?php

/**
 * San Bartolome High School Website
 *
 * @author   Raphael Marco <pinodex@outlook.ph>
 * @link     http://pinodex.github.io
 */

namespace App\Controllers\Site;

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;

class ContentsController {

    public function uploads(Request $request, Application $app, $file) {
        return $app->redirect($app['uploads.base'] . '/' . $file);
    }
    
}