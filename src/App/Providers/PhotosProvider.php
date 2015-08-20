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
use App\Models\Photos;

class PhotosProvider implements ServiceProviderInterface {

    protected $app;

    public function register(Application $app) {
        $this->app = $app;

        $app['photos'] = $this;
    }

    public function boot(Application $app) {

    }

    public function getById($id) {
        return Photos::find($id);
    }

    public function getByGallery($galleryId) {
        return Photos::where('gallery', $galleryId)->get();
    }

    public function getFirstByGallery($galleryId) {
        if ($photo = Photos::where('gallery', $galleryId)->first()) {
            return $photo->getAttributes();
        }
    }

    public function getCount() {
        return Photos::count();
    }

    public function deleteByGallery($galleryId) {
        return Photos::where('gallery', $galleryId)->delete();
    }

    public function create($data) {
        $photo = new Photos($data);

        $photo->uploaded = date('Y-m-d H:i:s');
        $photo->author = $this->app['accounts']->getCurrentAccountId();
        $photo->save();

        return $photo->id;
    }

}