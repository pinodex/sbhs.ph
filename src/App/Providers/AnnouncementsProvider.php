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
use App\Models\Announcements;

class AnnouncementsProvider implements ServiceProviderInterface {

    protected $app;

    public function register(Application $app) {
        $this->app = $app;

        $app['announcements'] = $this;
    }

    public function boot(Application $app) {

    }

    public function getAnnouncements() {
        return Announcements::orderBy('id', 'DESC')->get();
    }

    public function getById($id) {
        return Announcements::find($id);
    }

    public function create($data) {
        $announcement = new Announcements($data);
        
        $announcement->created = date('Y-m-d H:i:s');
        $announcement->author = $this->app['accounts']->getCurrentAccountId();

        $announcement->save();

        return $announcement->id;
    }

    public function edit($id, $data) {
        $announcement = Announcements::find($id);
        
        $announcement->fill($data);
        $announcement->save();
    }

}