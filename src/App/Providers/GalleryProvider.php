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
use App\Models\Gallery;

class GalleryProvider implements ServiceProviderInterface {

    protected $app;

    public function register(Application $app) {
        $this->app = $app;

        $app['gallery'] = $this;
    }

    public function boot(Application $app) {

    }

    public function getGalleries($limit = null) {
        $galleries = Gallery::orderBy('id', 'DESC');

        if ($limit === null) {
            $galleries->take($limit);
        }

        return $galleries->get()->toArray();
    }

    public function getById($id) {
        return Gallery::find($id);
    }

    public function getCount() {
        return Gallery::count();
    }

    public function create($data) {
        $gallery = new Gallery($data);
        
        $gallery->created = date('Y-m-d H:i:s');
        $gallery->author = $this->app['accounts']->getCurrentAccountId();

        $gallery->save();

        return $gallery->id;
    }

    public function edit($id, $data) {
        $gallery = Gallery::find($id);
        
        $gallery->fill($data);
        $gallery->last_edited = date('Y-m-d H:i:s');

        $gallery->save();
    }

}