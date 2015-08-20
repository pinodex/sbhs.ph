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

class AnnouncementsController {

    public function index(Request $request, Application $app) {
        if (!$app['accounts']->getCurrentAccount()) {
            return $app['helper']->redirectToLogin();
        }

        $vars['page_title'] = 'Announcements';
        $vars['announcements'] = $app['announcements']->getAnnouncements()->toArray();
        
        return $app['twig']->render('@dashboard/announcements/index.html', $vars);
    }

    public function create(Request $request, Application $app) {
        if (!$app['accounts']->getCurrentAccount()) {
            return $app['helper']->redirectToLogin();
        }

        $vars['page_title'] = 'Create Announcement';

        $form = $app['form.factory']->createNamedBuilder('event', 'form');
        $form->add('content', 'textarea', array(
            'constraints' => array(
                new Assert\NotBlank(),
                new Assert\Length(array('min' => 8))
            ),
            'attr' => array(
                'autofocus' => true
            )
        ));

        $form = $form->getForm();
        $form->handleRequest($request);

        if ($form->isValid()) {
            $data = $form->getData();

            $app['announcements']->create($data);
            $app['flashbag']->add('message', 'success::Account changes saved');

            return $app->redirect($app['url_generator']->generate('dashboard.announcements'));
        }

        $vars['announcement_form'] = $form->createView();
        
        return $app['twig']->render('@dashboard/announcements/create.html', $vars);
    }

    public function edit(Request $request, Application $app, $id) {
        if (!$app['accounts']->getCurrentAccount()) {
            return $app['helper']->redirectToLogin();
        }

        if (!$announcement = $app['announcements']->getById($id)) {
            $app['flashbag']->add('message', 'warning::Announcement does not exist');
            return $app->redirect($app['url_generator']->generate('dashboard.announcements'));
        }

        $vars['page_title'] = 'Edit Announcement';

        $form = $app['form.factory']->createNamedBuilder('event', 'form');
        $form->add('content', 'textarea', array(
            'constraints' => array(
                new Assert\NotBlank(),
                new Assert\Length(array('min' => 8))
            ),
            'attr' => array(
                'autofocus' => true
            ),
            'data' => $announcement->content
        ));

        $form = $form->getForm();
        $form->handleRequest($request);

        if ($form->isValid()) {
            $data = $form->getData();

            $app['announcements']->edit($id, $data);
            $app['flashbag']->add('message', 'success::Announcement changes saved');

            return $app->redirect($app['url_generator']->generate('dashboard.announcements'));
        }

        $vars['announcement'] = $announcement;
        $vars['announcement_form'] = $form->createView();
        
        return $app['twig']->render('@dashboard/announcements/edit.html', $vars);
    }

    public function delete(Request $request, Application $app, $id) {
        if (!$app['accounts']->getCurrentAccount()) {
            return $app['helper']->redirectToLogin();
        }

        if (!$announcement = $app['announcements']->getById($id)) {
            $app['flashbag']->add('message', 'warning::Announcement does not exist');
            return $app->redirect($app['url_generator']->generate('dashboard.announcements'));
        }

        $vars['page_title'] = 'Delete Announcement';

        if ($request->getMethod() == 'POST') {
            $announcement->delete();

            $app['flashbag']->add('message', 'success::Announcement deleted');
            return $app->redirect($app['url_generator']->generate('dashboard.announcements'));
        }

        $vars['announcement'] = $announcement;
        
        return $app['twig']->render('@dashboard/announcements/delete.html', $vars);
    }
    
}