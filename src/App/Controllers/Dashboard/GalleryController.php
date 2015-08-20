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
use Symfony\Component\Validator\Constraints as Assert;
use App\Components\ImageUpload;

class GalleryController {

    public function index(Request $request, Application $app) {
        if (!$app['accounts']->getCurrentAccount()) {
            return $app['helper']->redirectToLogin();
        }

        $vars['page_title'] = 'Gallery';
        $vars['galleries'] = $app['gallery']->getGalleries();
        
        return $app['twig']->render('@dashboard/gallery/index.html', $vars);
    }

    public function create(Request $request, Application $app) {
        if (!$app['accounts']->getCurrentAccount()) {
            return $app['helper']->redirectToLogin();
        }

        $vars['page_title'] = 'Create Gallery';

        $form = $app['form.factory']->createNamedBuilder('gallery', 'form');
        $form->add('name', 'text', array(
            'constraints' => array(
                new Assert\NotBlank(),
                new Assert\Length(array('min' => 5))
            ),
            'attr' => array(
                'autofocus' => true
            )
        ));
        $form->add('description', 'textarea', array(
            'constraints' => array(
                new Assert\NotBlank(),
                new Assert\Length(array('min' => 5))
            )
        ));

        $form = $form->getForm();
        $form->handleRequest($request);

        if ($form->isValid()) {
            $data = $form->getData();

            $galleryId = $app['gallery']->create($data);
            $app['flashbag']->add('message', 'success::Gallery created');

            return $app->redirect($app['url_generator']->generate('dashboard.gallery.view', [
                'id' => $galleryId
            ]));
        }

        $vars['gallery_form'] = $form->createView();
        
        return $app['twig']->render('@dashboard/gallery/create.html', $vars);
    }

    public function view(Request $request, Application $app, $id) {
        if (!$app['accounts']->getCurrentAccount()) {
            return $app['helper']->redirectToLogin();
        }

        if (!$gallery = $app['gallery']->getById($id)) {
            $app['flashbag']->add('message', 'warning::Gallery does not exist');
            return $app->redirect($app['url_generator']->generate('dashboard.gallery'));
        }

        $vars['page_title'] = $gallery->name . ' - Gallery';
        $vars['gallery'] = $gallery;
        $vars['photos'] = $app['photos']->getByGallery($gallery->id);
        
        return $app['twig']->render('@dashboard/gallery/view.html', $vars);
    }

    public function edit(Request $request, Application $app, $id) {
        if (!$app['accounts']->getCurrentAccount()) {
            return $app['helper']->redirectToLogin();
        }

        if (!$gallery = $app['gallery']->getById($id)) {
            $app['flashbag']->add('message', 'warning::Gallery does not exist');
            return $app->redirect($app['url_generator']->generate('dashboard.gallery'));
        }

        $vars['page_title'] = $gallery->name . ' - Edit Gallery';

        $form = $app['form.factory']->createNamedBuilder('gallery', 'form');
        $form->add('name', 'text', array(
            'constraints' => array(
                new Assert\NotBlank(),
                new Assert\Length(array('min' => 5))
            ),
            'attr' => array(
                'autofocus' => true
            ),
            'data' => $gallery->name
        ));
        $form->add('description', 'textarea', array(
            'constraints' => array(
                new Assert\NotBlank(),
                new Assert\Length(array('min' => 5))
            ),
            'data' => $gallery->description
        ));

        $form = $form->getForm();
        $form->handleRequest($request);

        if ($form->isValid()) {
            $data = $form->getData();

            $app['gallery']->edit($id, $data);
            $app['flashbag']->add('message', 'success::Gallery changes saved');

            return $app->redirect($app['url_generator']->generate('dashboard.gallery.view', [
                'id' => $id
            ]));
        }

        $vars['gallery_form'] = $form->createView();
        
        return $app['twig']->render('@dashboard/gallery/create.html', $vars);
    }

    public function delete(Request $request, Application $app, $id) {
        if (!$app['accounts']->getCurrentAccount()) {
            return $app['helper']->redirectToLogin();
        }

        $vars['page_title'] = 'Delete Gallery';
        
        if (!$gallery = $app['gallery']->getById($id)) {
            $app['flashbag']->add('message', 'warning::Gallery not found');
            return $app->redirect($app['url_generator']->generate('dashboard.gallery'));
        }

        $vars['gallery'] = $gallery->getAttributes();

        if ($request->getMethod() == 'POST') {
            $app['photos']->deleteByGallery($id);
            $gallery->delete();

            $app['flashbag']->add('message', 'success::Gallery deleted');
            return $app->redirect($app['url_generator']->generate('dashboard.gallery'));
        }
        
        return $app['twig']->render('@dashboard/gallery/delete.html', $vars);
    }

    public function deletePhoto(Request $request, Application $app, $galleryId, $photoId) {
        if (!$app['accounts']->getCurrentAccount()) {
            return $app['helper']->redirectToLogin();
        }

        $vars['page_title'] = 'Delete Gallery';
        
        if (!$app['gallery']->getById($galleryId)) {
            return $app->abort(404);
        }

        if (!$photo = $app['photos']->getByid($photoId)) {
            return $app->abort(404);
        }

        $photo->delete();
        return $app->abort(204);
    }

    public function upload(Request $request, Application $app) {
        if (!$app['accounts']->getCurrentAccount()) {
            return $app->abort(403);
        }

        if (!$gallery = $app['gallery']->getById($request->request->get('gallery'))) {
            return $app->abort(404, 'Gallery not found');
        }

        $image = new ImageUpload\File($_FILES['image']);

        $outputFileName = $image->createOutput();
        $thumbFileName = $image->createThumb();

        $photoId = $app['photos']->create(array(
            'file' => $outputFileName,
            'thumb' => $thumbFileName,
            'gallery' => $gallery->id
        ));

        return $photoId;
    }
    
}