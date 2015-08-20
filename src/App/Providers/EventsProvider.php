<?php

/**
 * San Bartolome High School Website
 *
 * @author   Raphael Marco <pinodex@outlook.ph>
 * @link     http://pinodex.github.io
 */

namespace App\Providers;

use Silex\Application;
use Silex\ServiceProviderInterface;
use App\Models\Events;

class EventsProvider implements ServiceProviderInterface {

    protected $app;

    public function register(Application $app) {
        $this->app = $app;
        $app['events'] = $this;
    }

    public function boot(Application $app) {

    }

    public function create($data) {
        $event = new Events($data);
        $event->author = $this->app['accounts']->getCurrentAccountId();
        $event->save();
    }

    public function edit($id, $data) {
        Events::find($id)->fill($data)->save();
    }

    public function getById($id) {
        return Events::find($id);
    }

    public function getAllEvents() {
        return Events::orderBy('datetime', 'DESC')->get();
    }

    public function getUpcomingEvents($limit = 5) {
        $events = Events::where('datetime', '>', date('Y-m-d H:i:s'))->orderBy('datetime', 'DESC');

        if ($limit !== null) {
            $events->take($limit);
        }

        return $events->get();
    }

    public function getCount() {
        return Events::count();
    }

}