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

class BannersController {

    public function index(Request $request, Application $app) {
        if (!$app['accounts']->getCurrentAccount()) {
            return $app['helper']->redirectToLogin();
        }

        $vars['page_title'] = 'Banners';
        $vars['banners'] = $app['banners']->getBanners();
        
        return $app['twig']->render('@dashboard/banners/index.html', $vars);
    }

    public function create(Request $request, Application $app) {
        if (!$app['accounts']->getCurrentAccount()) {
            return $app['helper']->redirectToLogin();
        }

        $vars['page_title'] = 'Create Banner';

        $form = $app['form.factory']->createNamedBuilder('gallery', 'form');
        $form->add('title', 'text', array(
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
        $form->add('image', 'file');

        $form = $form->getForm();
        $form->handleRequest($request);

        if ($form->isValid()) {
            $data = $form->getData();
            $image = new ImageUpload\File($form['image']->getData());

            $data['image'] = $image->createBanner();

            $galleryId = $app['banners']->create($data);
            $app['flashbag']->add('message', 'success::Banner created');

            return $app->redirect($app['url_generator']->generate('dashboard.banners'));
        }

        $vars['banner_form'] = $form->createView();
        
        return $app['twig']->render('@dashboard/banners/create.html', $vars);
    }

    public function edit(Request $request, Application $app, $id) {
        if (!$app['accounts']->getCurrentAccount()) {
            return $app['helper']->redirectToLogin();
        }

        if (!$banner = $app['banners']->getById($id)) {
            $app['flashbag']->add('message', 'warning::Banner does not exist');
            return $app->redirect($app['url_generator']->generate('dashboard.banners'));
        }

        $vars['page_title'] = 'Edit Banner';

        $form = $app['form.factory']->createNamedBuilder('gallery', 'form');
        $form->add('title', 'text', array(
            'constraints' => array(
                new Assert\NotBlank(),
                new Assert\Length(array('min' => 5))
            ),
            'attr' => array(
                'autofocus' => true
            ),
            'data' => $banner->title
        ));
        $form->add('description', 'textarea', array(
            'constraints' => array(
                new Assert\NotBlank(),
                new Assert\Length(array('min' => 5))
            ),
            'data' => $banner->description
        ));
        $form->add('image', 'file', array(
            'label' => 'Image (leave untouched if not changing)',
            'required' => false
        ));

        $form = $form->getForm();
        $form->handleRequest($request);

        if ($form->isValid()) {
            $data = $form->getData();

            if ($data['image'] !== null) {
                $image = new ImageUpload\File($data['image']);
                $data['image'] = $image->createBanner();
            } else {
                unset($data['image']);
            }

            $galleryId = $app['banners']->edit($id, $data);
            $app['flashbag']->add('message', 'success::Banner changes saved');

            return $app->redirect($app['url_generator']->generate('dashboard.banners'));
        }

        $vars['banner'] = $banner;
        $vars['banner_form'] = $form->createView();
        
        return $app['twig']->render('@dashboard/banners/edit.html', $vars);
    }

    public function delete(Request $request, Application $app, $id) {
        if (!$app['accounts']->getCurrentAccount()) {
            return $app['helper']->redirectToLogin();
        }

        if (!$banner = $app['banners']->getById($id)) {
            $app['flashbag']->add('message', 'warning::Banner does not exist');
            return $app->redirect($app['url_generator']->generate('dashboard.banners'));
        }

        $vars['page_title'] = 'Delete Banner';

        if ($request->getMethod() == 'POST') {
            $banner->delete();

            $app['flashbag']->add('message', 'success::Banner deleted');
            return $app->redirect($app['url_generator']->generate('dashboard.banners'));
        }

        $vars['banner'] = $banner;
        
        return $app['twig']->render('@dashboard/banners/delete.html', $vars);
    }
    
}