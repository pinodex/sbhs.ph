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
use App\Models\Banners;

class BannersProvider implements ServiceProviderInterface {

    protected $app;

    public function register(Application $app) {
        $this->app = $app;

        $app['banners'] = $this;
    }

    public function boot(Application $app) {

    }

    public function getBanners() {
        return Banners::orderBy('id', 'DESC')->get()->toArray();
    }

    public function getById($id) {
        return Banners::find($id);
    }

    public function create($data) {
        $banner = new Banners($data);
        
        $banner->created = date('Y-m-d H:i:s');
        $banner->author = $this->app['accounts']->getCurrentAccountId();

        $banner->save();

        return $banner->id;
    }

    public function edit($id, $data) {
        $banner = Banners::find($id);
        $banner->fill($data);

        $banner->save();

        return $banner->id;
    }

}