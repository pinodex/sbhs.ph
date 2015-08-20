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

class EventsController {

    public function index(Request $request, Application $app) {
        if (!$app['accounts']->getCurrentAccount()) {
            return $app['helper']->redirectToLogin();
        }

        $vars['page_title'] = 'Events';
        $vars['events'] = $app['helper']->partition($app['events']->getAllEvents()->toArray(), 3);

        return $app['twig']->render('@dashboard/events/index.html', $vars);
    }

    public function create(Request $request, Application $app) {
        if (!$app['accounts']->getCurrentAccount()) {
            return $app['helper']->redirectToLogin();
        }

        $vars['page_title'] = 'Create Event';

        $form = $app['form.factory']->createNamedBuilder('event', 'form');
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
        $form->add('datetime', 'datetime', array(
            'date_widget' => 'single_text',
            'time_widget' => 'single_text',
            'label' => 'Date and time',
            'required' => true
        ));

        $form = $form->getForm();
        $form->handleRequest($request);

        if ($form->isValid()) {
            $data = $form->getData();

            $galleryId = $app['events']->create($data);
            $app['flashbag']->add('message', 'success::Event created');

            return $app->redirect($app['url_generator']->generate('dashboard.events'));
        }

        $vars['event_form'] = $form->createView();

        return $app['twig']->render('@dashboard/events/create.html', $vars);
    }

    public function edit(Request $request, Application $app, $id) {
        if (!$app['accounts']->getCurrentAccount()) {
            return $app['helper']->redirectToLogin();
        }

        if (!$event = $app['events']->getById($id)) {
            $app['flashbag']->add('message', 'warning::Event does not exist');
            return $app->redirect($app['url_generator']->generate('dashboard.events'));
        }

        $vars['page_title'] = 'Edit ' . $event->title;

        $form = $app['form.factory']->createNamedBuilder('event', 'form');
        $form->add('title', 'text', array(
            'constraints' => array(
                new Assert\NotBlank(),
                new Assert\Length(array('min' => 5))
            ),
            'attr' => array(
                'autofocus' => true
            ),
            'data' => $event->title
        ));
        $form->add('description', 'textarea', array(
            'constraints' => array(
                new Assert\NotBlank(),
                new Assert\Length(array('min' => 5))
            ),
            'data' => $event->description
        ));
        $form->add('datetime', 'datetime', array(
            'date_widget' => 'single_text',
            'time_widget' => 'single_text',
            'label' => 'Date and time',
            'required' => true,
            'data' => new \DateTime($event->datetime)
        ));

        $form = $form->getForm();
        $form->handleRequest($request);

        if ($form->isValid()) {
            $data = $form->getData();

            $galleryId = $app['events']->edit($id, $data);
            $app['flashbag']->add('message', 'success::Event changes saved');

            return $app->redirect($app['url_generator']->generate('dashboard.events'));
        }

        $vars['event'] = $event;
        $vars['event_form'] = $form->createView();

        return $app['twig']->render('@dashboard/events/edit.html', $vars);
    }

    public function delete(Request $request, Application $app, $id) {
        if (!$app['accounts']->getCurrentAccount()) {
            return $app['helper']->redirectToLogin();
        }

        if (!$event = $app['events']->getById($id)) {
            $app['flashbag']->add('message', 'warning::Banner does not exist');
            return $app->redirect($app['url_generator']->generate('dashboard.banners'));
        }

        $vars['page_title'] = 'Delete Event';

        if ($request->getMethod() == 'POST') {
            $event->delete();

            $app['flashbag']->add('message', 'success::Event deleted');
            return $app->redirect($app['url_generator']->generate('dashboard.events'));
        }

        $vars['event'] = $event;
        
        return $app['twig']->render('@dashboard/events/delete.html', $vars);
    }
    
}