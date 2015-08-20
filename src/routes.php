<?php

/**
 * San Bartolome High School Website
 *
 * @author   Raphael Marco <pinodex@outlook.ph>
 * @link     http://pinodex.github.io
 */

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

$app->mount('/', new App\Providers\Site\MainControllerProvider());

$app->mount('/contents', new App\Providers\Site\ContentsControllerProvider());

$app->mount('/posts', new App\Providers\Site\PostsControllerProvider());

$app->mount('/posts', new App\Providers\Site\PostsControllerProvider());

$app->mount('/events', new App\Providers\Site\EventsControllerProvider());

$app->mount('/gallery', new App\Providers\Site\GalleryControllerProvider());

$app->mount('/about', new App\Providers\Site\AboutControllerProvider());

$app->mount('/dashboard', new App\Providers\Dashboard\MainControllerProvider());

$app->mount('/dashboard/auth', new App\Providers\Dashboard\AuthControllerProvider());

$app->mount('/dashboard/posts', new App\Providers\Dashboard\PostsControllerProvider());

$app->mount('/dashboard/announcements', new App\Providers\Dashboard\AnnouncementsControllerProvider());

$app->mount('/dashboard/gallery', new App\Providers\Dashboard\GalleryControllerProvider());

$app->mount('/dashboard/banners', new App\Providers\Dashboard\BannersControllerProvider());

$app->mount('/dashboard/events', new App\Providers\Dashboard\EventsControllerProvider());

$app->mount('/dashboard/accounts', new App\Providers\Dashboard\AccountsControllerProvider());

$app->before(function (Request $request) {
    // http://silex.sensiolabs.org/doc/cookbook/json_request_body.html
    if (0 === strpos($request->headers->get('Content-Type'), 'application/json')) {
        $data = json_decode($request->getContent(), true);
        $request->request->replace(is_array($data) ? $data : array());
    }
});

$app->error(function (\Exception $e, $code) use ($app) {
    if ($app['debug']) {
        return;
    }
    
    $content_type = $app['request_stack']->getCurrentRequest()->headers->get('Content-Type');

    if (strpos($content_type, 'application/json') === 0 || $app['request_stack']->getCurrentRequest()->isXmlHttpRequest()) {
        $error = array();
        $code_prefix = substr($code, 0, 1);

        $error['code'] = $code;
        $error['status'] = 'unknown';

        if ($code_prefix == 2) {
            $error['status'] = 'success';
        }

        if ($code_prefix == 3) {
            $error['status'] = 'redirect';
        }

        if ($code_prefix == 4) {
            $error['status'] = 'error';
        }

        if ($code_prefix == 5) {
            $error['status'] = 'error';
        }

        if ($message = $e->getMessage()) {
            $error['message'] = $message;
        }

        return $app->json($error, $code);
    }
});