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

class AccountsController {

    public function index(Request $request, Application $app) {
        if (!$app['accounts']->getCurrentAccount()) {
            return $app['helper']->redirectToLogin();
        }

        $vars['page_title'] = 'Accounts';
        $vars['accounts'] = $app['accounts']->getAccounts()->toArray();
        
        return $app['twig']->render('@dashboard/accounts/index.html', $vars);
    }

    public function create(Request $request, Application $app) {
        if (!$app['accounts']->getCurrentAccount()) {
            return $app['helper']->redirectToLogin();
        }

        $vars['page_title'] = 'Create Account';

        $form = $app['form.factory']->createNamedBuilder('event', 'form');
        $form->add('name', 'text', array(
            'constraints' => array(
                new Assert\NotBlank(),
                new Assert\Length(array('min' => 5))
            ),
            'attr' => array(
                'autofocus' => true
            )
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
                    'message' => 'Username already exists',
                )),
                new Assert\Regex(array(
                    'pattern' => '/^[A-Za-z0-9_-]+$/',
                    'match'   => true,
                    'message' => 'Your name can only contain a letters, numbers, dashes, and undercores',
                ))
            )   
        ));
        $form->add('password', 'repeated', array(
            'type' => 'password',
            'invalid_message' => 'The password must match.',
            'required' => true,
            'first_options'  => array('label' => 'Password'),
            'second_options' => array('label' => 'Repeat Password'),
            'constraints' => array(
                new Assert\NotBlank(),
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
                    'message' => 'Email already exists'
                ))
            )
        ));
        $form->add('about', 'textarea');
        $form->add('acctype', 'choice', array(
            'choices' => array(
                '2' => 'Author',
                '1' => 'Administrator'
            )
        ));

        $form = $form->getForm();
        $form->handleRequest($request);

        if ($form->isValid()) {
            $data = $form->getData();

            $app['accounts']->create($data);
            $app['flashbag']->add('message', 'success::Account created');

            return $app->redirect($app['url_generator']->generate('dashboard.accounts'));
        }

        $vars['account_form'] = $form->createView();
        
        return $app['twig']->render('@dashboard/accounts/create.html', $vars);
    }

    public function edit(Request $request, Application $app, $id) {
        if (!$currentAccount = $app['accounts']->getCurrentAccount()) {
            return $app['helper']->redirectToLogin();
        }

        if (!$account = $app['accounts']->getById($id)) {
            $app['flashbag']->add('message', 'warning::Account does not exist');
            return $app->redirect($app['url_generator']->generate('dashboard.accounts'));
        }

        if ($account->id == $currentAccount->id) {
            return $app->redirect($app['url_generator']->generate('dashboard.settings'));
        }

        $vars['page_title'] = 'Edit Account';

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
            'data' => $account->about
        ));
        $form->add('acctype', 'choice', array(
            'choices' => array(
                '2' => 'Author',
                '1' => 'Administrator'
            ),
            'data' => $account->acctype
        ));

        $form = $form->getForm();
        $form->handleRequest($request);

        if ($form->isValid()) {
            $data = $form->getData();

            $app['accounts']->edit($id, $data);
            $app['flashbag']->add('message', 'success::Account changes saved');

            return $app->redirect($app['url_generator']->generate('dashboard.accounts'));
        }

        $vars['account'] = $account;
        $vars['account_form'] = $form->createView();
        
        return $app['twig']->render('@dashboard/accounts/edit.html', $vars);
    }

    public function delete(Request $request, Application $app, $id) {
        if (!$currentAccount = $app['accounts']->getCurrentAccount()) {
            return $app['helper']->redirectToLogin();
        }

        if (!$account = $app['accounts']->getById($id)) {
            $app['flashbag']->add('message', 'warning::Account does not exist');
            return $app->redirect($app['url_generator']->generate('dashboard.accounts'));
        }

        $vars['page_title'] = 'Delete Account';

        if ($request->getMethod() == 'POST') {
            $account->delete();

            $app['flashbag']->add('message', 'success::Account deleted');
            return $app->redirect($app['url_generator']->generate('dashboard.accounts'));
        }

        $vars['account'] = $account;

        if ($account->id == $currentAccount->id) {
            return $app['twig']->render('@dashboard/accounts/suicide.html', $vars);
        }
        
        return $app['twig']->render('@dashboard/accounts/delete.html', $vars);
    }
    
}