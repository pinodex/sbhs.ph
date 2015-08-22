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

class PostsController {

    public function index(Request $request, Application $app) {
        $vars['page_title'] = 'Posts';

        $description = 'Read the latest posts on San Bartolome High School.';

        $app['helper']->addMetaTag('description', $description);
        $app['helper']->addOgTag('description', $description);
        
        return $app['twig']->render('@site/posts/index.html', $vars);
    }

    public function read(Request $request, Application $app, $id, $slug) {
        if (!$post = $app['posts']->getById($id)) {
            return $app['helper']->notFoundPage();
        }

        $postSlug = $app['helper']->makeSlug($post->title);

        if ($slug != $postSlug) {
            return $app->redirect($app['url_generator']->generate('site.posts.read', [
                'id' => $id,
                'slug' => $postSlug
            ]));
        }

        $vars['page_title'] = $post->title;
        $vars['author'] = $app['accounts']->getAccount($post->author);
        $vars['post'] = $post;

        $app['helper']->addMetaTag('description', $post->description ?: $app['posts']->truncateText($post->content));
        $app['helper']->addOgTag('description', $post->description ?: $app['posts']->truncateText($post->content));

        if ($vars['author']) {
            $app['helper']->addMetaTag('author', $vars['author']->name);
        }

        foreach ($app['posts']->getAllImageUrl($post->content) as $image) {
            $app['helper']->addOgTag('image', $image);
        }
        
        return $app['twig']->render('@site/posts/read.html', $vars);
    }
    
}