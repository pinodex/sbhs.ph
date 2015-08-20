<?php

/**
 * San Bartolome High School Website
 *
 * @author   Raphael Marco <pinodex@outlook.ph>
 * @link     http://pinodex.github.io
 */

namespace App\Controllers\Dashboard;

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Csrf\CsrfToken;
use App\Components\ImageUpload;

class PostsController {

    public function index(Request $request, Application $app) {
        if (!$app['accounts']->getCurrentAccount()) {
            return $app['helper']->redirectToLogin();
        }

        $vars['page_title'] = 'Posts';
        $vars['posts'] = $app['posts']->getPosts();
        
        return $app['twig']->render('@dashboard/posts/index.html', $vars);
    }

    public function write(Request $request, Application $app, $id = null) {
        if (!$app['accounts']->getCurrentAccount()) {
            return $app['helper']->redirectToLogin();
        }

        if ($request->getMethod() == 'POST') {
            $error = false;

            if (!$app['form.csrf_provider']->isTokenValid(new CsrfToken('dashboard.posts.write', $request->request->get('_csrf_token')))) {
                $app->abort(403, 'Invalid CSRF token');
            }

            if (empty(trim($request->request->get('title')))) {
                $app->abort(400, 'Invalid post title');
            }

            if (!in_array($request->request->get('post_type'), ['publish', 'draft'])) {
                $app->abort(400, 'Invalid post type');
            }

            if (!$request->request->get('content')) {
                $app->abort(400, 'Invalid post content');
            }

            $postId = $app['posts']->write($id, $request->request->all());

            if ($request->request->get('post_type') == 'publish') {
                $app['flashbag']->add('message', 'success::Post successfully saved and published');
            }

            if ($request->request->get('post_type') == 'draft') {
                $app['flashbag']->add('message', 'success::Post successfully saved as draft');
            }

            return $app['helper']->restResponse(array(
                'redirect' => $app['url_generator']->generate('dashboard.posts.edit', array(
                    'id' => $postId
                ))
            ));
        }

        $vars['page_title'] = 'New Post';

        if ($id) {
            if (!$post = $app['posts']->getById($id)) {
                $app['flashbag']->add('message', 'warning::Post not found');
                return $app->redirect($app['url_generator']->generate('dashboard.posts'));
            }

            $vars['page_title'] = 'Edit Post';
            $vars['post'] = $post->getAttributes();
        }

        $app['assets']->addCss('/plugins/fontawesome/css/font-awesome.min.css');
        $app['assets']->addCss('/plugins/summernote/summernote-bs3.css');
        $app['assets']->addJs('/plugins/bootstrap/js/bootstrap.min.js');
        $app['assets']->addJs('/plugins/summernote/summernote.min.js');
        
        return $app['twig']->render('@dashboard/posts/write.html', $vars);
    }

    public function delete(Request $request, Application $app, $id) {
        if (!$app['accounts']->getCurrentAccount()) {
            return $app['helper']->redirectToLogin();
        }

        $vars['page_title'] = 'Delete Post';
        
        if (!$post = $app['posts']->getById($id)) {
            $app['flashbag']->add('message', 'warning::Post not found');
            return $app->redirect($app['url_generator']->generate('dashboard.posts'));
        }

        $vars['post'] = $post->getAttributes();

        if ($request->getMethod() == 'POST') {
            $post->delete();

            $app['flashbag']->add('message', 'success::Post deleted');
            return $app->redirect($app['url_generator']->generate('dashboard.posts'));
        }
        
        return $app['twig']->render('@dashboard/posts/delete.html', $vars);
    }

    public function upload(Request $request, Application $app) {
        if (!$app['accounts']->getCurrentAccount()) {
            return $app->abort(403);
        }

        $image = new ImageUpload\File($_FILES['image']);
        return $app['upload']->get($image->createOutput());
    }
    
}