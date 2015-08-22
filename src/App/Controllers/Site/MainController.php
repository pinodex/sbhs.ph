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
use Symfony\Component\HttpFoundation\Response;

class MainController {

    public function index(Request $request, Application $app) {
        $vars['page_title'] = 'Home';
        $vars['banners'] = $app['banners']->getBanners();

        $app['assets']->addCss('/plugins/bootstrap-carousel/css/bootstrap.css');
        $app['assets']->addJs('/plugins/bootstrap-carousel/js/bootstrap.min.js');

        $description = 'Official website of San Bartolome High School. Browse the latest updates inside and outside the school.';

        $app['helper']->addMetaTag('description', $description);
        $app['helper']->addOgTag('description', $description);
        
        return $app['twig']->render('@site/index.html', $vars);
    }

    public function rss(Request $request, Application $app) {
        $vars['posts'] = $app['posts']->getPublishedPosts();
        $vars['galleries'] = $app['gallery']->getGalleries();

        return new Response($app['twig']->render('@site/rss.xml', $vars), 200, 
            array('Content-Type' => 'application/xml')
        );
    }
    
}