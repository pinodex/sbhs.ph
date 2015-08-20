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

class AboutController {

    public function index(Request $request, Application $app) {
        return $app->redirect($app['url_generator']->generate('site.about.history'));
    }

    public function history(Request $request, Application $app) {
        $vars['page_title'] = 'History';
        
        return $app['twig']->render('@site/about/history.html', $vars);
    }

    public function missionAndVision(Request $request, Application $app) {
        $vars['page_title'] = 'Mission and Vision';
        
        return $app['twig']->render('@site/about/mission-and-vision.html', $vars);
    }

    public function stats(Request $request, Application $app) {
        $vars['page_title'] = 'Stats';
        
        return $app['twig']->render('@site/about/stats.html', $vars);
    }

    public function authors(Request $request, Application $app) {
        $vars['page_title'] = 'Authors';
        $vars['authors'] = $app['accounts']->getAccounts()->toArray();
        
        return $app['twig']->render('@site/about/authors.html', $vars);
    }

    public function authorView(Request $request, Application $app, $id, $slug) {
        if (!$author = $app['accounts']->getById($id)) {
            return $app->abort(404);
        }

        $authorSlug = $app['helper']->makeSlug($author->name);

        if ($slug != $authorSlug) {
            return $app->redirect($app['url_generator']->generate('site.about.authors.view', [
                'id' => $id,
                'slug' => $authorSlug
            ]));
        }

        $vars['page_title'] = $author->name . ' - Authors';

        $vars['posts'] = $app['posts']->getByAuthor($id)->toArray();
        $vars['author'] = $author->toArray();
        
        return $app['twig']->render('@site/about/authors_view.html', $vars);
    }
    
}