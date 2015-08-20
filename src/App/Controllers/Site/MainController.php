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

class MainController {

    public function index(Request $request, Application $app) {
        $vars['page_title'] = 'Home';
        $vars['banners'] = $app['banners']->getBanners();

        $app['assets']->addCss('/plugins/bootstrap-carousel/css/bootstrap.css');
        $app['assets']->addJs('/plugins/bootstrap-carousel/js/bootstrap.min.js');
        
        return $app['twig']->render('@site/index.html', $vars);
    }
    
}