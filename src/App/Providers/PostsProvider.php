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
use App\Models\Posts;

class PostsProvider implements ServiceProviderInterface {

    protected $app;

    public function register(Application $app) {
        $this->app = $app;
        $app['posts'] = $this;
    }

    public function boot(Application $app) {

    }

    public function getById($id) {
        return Posts::find($id);
    }

    public function getPosts($limit = null) {
        $posts = Posts::orderBy('id', 'DESC');

        if ($limit !== null) {
            $posts->take($limit);
        }

        return $posts->get()->toArray();
    }

    public function getPublishedPosts($limit = null) {
        $posts = Posts::whereNotNull('published')->orderBy('id', 'DESC');

        if ($limit !== null) {
            $posts->take($limit);
        }

        return $posts->get()->toArray();
    }

    public function getByAuthor($id) {
        return Posts::where('author', $id)->orderBy('id', 'DESC')->get();
    }

    public function getPublishedByAuthor($id) {
        return Posts::where('author', $id)->whereNotNull('published')->orderBy('id', 'DESC')->get();
    }

    public function getPublishedCount() {
        return Posts::whereNotNull('published')->count();
    }

    public function getDraftCount() {
        return Posts::whereNull('published')->count();
    }

    public function write($id, $data) {
        if ($id) {
            $post = Posts::find($id);
        } else {
            $post = new Posts();
        }

        if ($post === null) {
            $this->app->abort(500, 'Internal Server Error');
        }

        $post->title = $data['title'];
        $post->description = $data['description'];

        if (empty(trim($data['description']))) {
            $post->description = null;
        }

        $post->content = $data['content'];
        $post->author = $this->app['accounts']->getCurrentAccountId();
        $post->saved = date('Y-m-d H:i:s');

        if ($data['post_type'] == 'publish') {
            $post->published = date('Y-m-d H:i:s');
        }

        if ($id) {
            $post->last_edited = date('Y-m-d H:i:s');
        }
        
        $post->save();

        return $post->id;
    }

    public function truncateText($string) {
        $string = preg_replace('/((\w+\W*){20}(\w+))(.*)/', '${1}', strip_tags($string));
        $string = preg_replace('/&#?[a-z0-9]{2,8};/i', '', $string);

        if (str_word_count($string) > 20) {
            $string .= '...';
        }

        return $string;
    }

    public function getAllImageUrl($string) {
        $output = array();

        if (preg_match_all('/<img\s+.*?src=[\"\']?([^\"\' >]*)[\"\']?[^>]*>/i', $string, $matches, PREG_SET_ORDER)) {
            foreach ($matches as $match) {
                $output[] = $match[1];
            }
        }

        return $output;
    }

    public function getFirstImageUrl($string) {
        preg_match('/<img\s+.*?src=[\"\']?([^\"\' >]*)[\"\']?[^>]*>/i', $string, $matches);

        if ($matches) {
            return $matches[1];
        }
    }

}