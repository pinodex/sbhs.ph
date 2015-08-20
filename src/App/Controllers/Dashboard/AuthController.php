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

class AuthController {

    public function login(Request $request, Application $app) {
        if ($app['accounts']->getCurrentAccount()) {
            return $app->redirect($app['url_generator']->generate('dashboard.index'));
        }

        $vars['page_title'] = 'Login';

        $form = $app['form.factory']->createNamedBuilder('login', 'form');
        $form->add('email', 'email', array(
            'attr' => array(
                'autofocus' => true
            ),
            'data' => $app['session']->get('lastEmailAttempt')
        ));
        $form->add('password', 'password');
        
        $form = $form->getForm();
        $form->handleRequest($request);

        if ($form->isValid()) {
            $data = $form->getData();

            $app['session']->set('lastEmailAttempt', $data['email']);
            
            if ($app['accounts']->verifyCredentials($data)) {
                $app['session']->remove('lastEmailAttempt');
                $app['accounts']->loginSession();

                if ($next = $request->query->get('next')) {
                    return $app->redirect($next);
                }

                return $app->redirect($app['url_generator']->generate('dashboard.index'));
            }

            $app['flashbag']->add('message', 'alert::Invalid email and/or password.');

            if ($next = $request->query->get('next')) {
                return $app->redirect($app['url_generator']->generate('dashboard.auth.login', [
                    'next' => $next
                ]));
            }

            return $app->redirect($app['url_generator']->generate('dashboard.auth.login'));
        }

        $vars['login_form'] = $form->createView();
        
        return $app['twig']->render('@dashboard/auth/login.html', $vars);
    }

    public function logout(Request $request, Application $app) {
        if (!$app['accounts']->getCurrentAccount()) {
            return $app->redirect($app['url_generator']->generate('dashboard.auth.login'));
        }

        $app['accounts']->logoutSession();
        $app['flashbag']->add('message', 'You have been logged out');

        return $app->redirect($app['url_generator']->generate('dashboard.auth.login'));
    }
    
}