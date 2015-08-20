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
use App\Constraints as CustomAssert;
use App\Models\Accounts;

class MainController {

    public function index(Request $request, Application $app) {
        if (!$app['accounts']->getCurrentAccount()) {
            return $app['helper']->redirectToLogin(false);
        }

        $vars['page_title'] = 'Home';
        
        return $app['twig']->render('@dashboard/index.html', $vars);
    }

    public function settings(Request $request, Application $app) {
        if (!$account = $app['accounts']->getCurrentAccount()) {
            return $app['helper']->redirectToLogin();
        }

        $vars['page_title'] = 'Account Settings';

        $form = $app['form.factory']->createNamedBuilder('event', 'form');
        $form->add('name', 'text', array(
            'constraints' => array(
                new Assert\NotBlank(),
                new Assert\Length(array('min' => 5))
            ),
            'attr' => array(
                'autofocus' => true
            ),
            'data' => $account->name
        ));
        $form->add('username', 'text', array(
            'constraints' => array(
                new Assert\NotBlank(),
                new Assert\Length(array('min' => 2)),
                new CustomAssert\RecordExistence(array(
                    'validate' => 'exists',
                    'model' => new Accounts(),
                    'row' => 'username',
                    'comparator' => '=',
                    'exclude' => $account->username,
                    'message' => 'Username already exists',
                )),
                new Assert\Regex(array(
                    'pattern' => '/^[A-Za-z0-9_-]+$/',
                    'match'   => true,
                    'message' => 'Your name can only contain a letters, numbers, dashes, and undercores',
                ))
            ),
            'data' => $account->username
        ));
        $form->add('current_password', 'password', array(
            'required' => false,
            'label' => 'Current Password (leave empty if not changing)',
            'constraints' => array(
                new Assert\Length(array('min' => 8)),
                new CustomAssert\PasswordMatch(array(
                    'to' => $account->password
                ))
            )
        ));
        $form->add('password', 'repeated', array(
            'type' => 'password',
            'invalid_message' => 'The password must match.',
            'required' => true,
            'first_options'  => array('label' => 'Password (leave empty if not changing)'),
            'second_options' => array('label' => 'Repeat Password (leave empty if not changing)'),
            'required' => false,
            'constraints' => array(
                new Assert\Length(array('min' => 8))
            )
        ));
        $form->add('email', 'email', array(
            'constraints' => array(
                new Assert\Email(),
                new CustomAssert\RecordExistence(array(
                    'validate' => 'exists',
                    'model' => new Accounts(),
                    'row' => 'email',
                    'exclude' => $account->email,
                    'message' => 'Email already exists'
                ))
            ),
            'data' => $account->email
        ));
        $form->add('about', 'textarea', array(
            'data' => $account->about,
            'required' => false
        ));

        $form = $form->getForm();
        $form->handleRequest($request);

        if ($form->isValid()) {
            $data = $form->getData();

            $app['accounts']->edit($account->id, $data);
            $app['flashbag']->add('message', 'success::Account changes saved');

            return $app->redirect($app['url_generator']->generate('dashboard.settings'));
        }

        $vars['account'] = $account;
        $vars['settings_form'] = $form->createView();
        
        return $app['twig']->render('@dashboard/settings.html', $vars);
    }
    
}