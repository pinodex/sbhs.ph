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

class GalleryController {

    public function index(Request $request, Application $app) {
        $vars['page_title'] = 'Gallery';
        $vars['galleries'] = $app['gallery']->getGalleries();
        
        return $app['twig']->render('@site/gallery/index.html', $vars);
    }

    public function view(Request $request, Application $app, $id, $slug) {
        if (!$gallery = $app['gallery']->getById($id)) {
            return $app['helper']->notFoundPage();
        }

        $gallerySlug = $app['helper']->makeSlug($gallery->name);

        if ($slug != $gallerySlug) {
            return $app->redirect($app['url_generator']->generate('site.gallery.view', [
                'id' => $id,
                'slug' => $gallerySlug
            ]));
        }

        $vars['page_title'] = $gallery->name;
        $vars['author'] = $app['accounts']->getAccount($gallery->author);
        $vars['photos'] = $app['photos']->getByGallery($gallery->id)->toArray();
        $vars['gallery'] = $gallery;

        if ($gallery->description) {
            $app['helper']->addMetaTag('description', $gallery->description);
        }

        if ($vars['author']) {
            $app['helper']->addMetaTag('author', $vars['author']->name);
        }

        if ($vars['photos']) {
            $app['helper']->addOgTag('image', $app['upload']->get($vars['photos'][0]['file']));
        }
        
        return $app['twig']->render('@site/gallery/view.html', $vars);
    }

    public function viewPhoto(Request $request, Application $app, $id, $slug, $photoId) {
        if (!$gallery = $app['gallery']->getById($id)) {
            return $app['helper']->notFoundPage();
        }

        $gallerySlug = $app['helper']->makeSlug($gallery->name);

        if ($slug != $gallerySlug) {
            return $app->redirect($app['url_generator']->generate('site.gallery.view.photo', [
                'id' => $id,
                'slug' => $gallerySlug,
                'photoId' => $photoId
            ]));
        }

        if (!$photo = $photo = $app['photos']->getById($photoId)) {
            return $app->redirect($app['url_generator']->generate('site.gallery.view', [
                'id' => $id,
                'slug' => $gallerySlug
            ]));
        }

        $vars['page_title'] = $gallery->name;
        $vars['author'] = $app['accounts']->getAccount($gallery->author);
        $vars['photo'] = $photo;
        $vars['gallery'] = $gallery;

        if ($gallery->description) {
            $app['helper']->addMetaTag('description', $gallery->description);
        }

        if ($vars['author']) {
            $app['helper']->addMetaTag('author', $vars['author']->name);
        }
        
        $app['helper']->addOgTag('image', $app['upload']->get($photo->file));
        
        return $app['twig']->render('@site/gallery/single.html', $vars);
    }
    
}