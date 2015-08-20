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

class EventsController {

    public function index(Request $request, Application $app) {
        $vars['page_title'] = 'Event Calendar';

        $app['assets']->addJs('/js/moment.js');
        $app['assets']->addJs('/js/underscore-min.js');
        $app['assets']->addJs('/js/clndr.min.js');

        $events = array();

        foreach ($app['events']->getAllEvents() as $event) {
            $events[] = array(
                'date' => date('Y-m-d', strtotime($event->datetime)),
                'hrf' => date('M d, Y h:i a', strtotime($event->datetime)),
                'title' => $event->title,
                'description' => $event->description
            );
        }

        $vars['events'] = $events;
        
        return $app['twig']->render('@site/events/index.html', $vars);
    }
    
}