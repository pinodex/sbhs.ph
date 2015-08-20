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

class HelperProvider implements ServiceProviderInterface {

    protected $app, $metaTags = array(), $ogTags = array();

    public function register(Application $app) {
        $this->app = $app;

        $app['helper'] = $this;
    }

    public function boot(Application $app) {

    }

    public function addMetaTag($name, $content) {
        $this->metaTags[] = array(
            'name' => $name,
            'content' => $content
        );
    }

    public function addOgTag($name, $content) {
        $this->ogTags[] = array(
            'name' => $name,
            'content' => $content
        );
    }

    public function getMetaTags() {
        return $this->metaTags;
    }

    public function getOgTags() {
        return $this->ogTags;
    }
    
    public function redirectToLogin($hasNext = true) {
        $params = array();

        if ($hasNext) {
            $params['next'] = $this->app['request']->getPathInfo();
        }

        return $this->app->redirect($this->app['url_generator']->generate('dashboard.auth.login', $params));
    }

    public function notFoundPage() {
        return $this->app->abort(404);
    }

    public function restResponse($data, $message = null, $code = 200) {
        $response = array(
            'code' => $code,
            'status' => 'unknown',
            'message' => $message,
            'data' => $data
        );

        $code_prefix = substr($code, 0, 1);

        if ($code_prefix == 2) {
            $response['status'] = 'success';
        }

        if ($code_prefix == 3) {
            $response['status'] = 'redirect';
        }

        if ($code_prefix == 4) {
            $response['status'] = 'error';
        }

        if ($code_prefix == 5) {
            $response['status'] = 'error';
        }

        return $this->app->json($response);
    }

    public function makeSlug($string) {
        $string = preg_replace('/((\w+\W*){5}(\w+))(.*)/', '${1}', $string);
        $string = preg_replace('~[^\\pL\d]+~u', '-', $string);
        $string = trim($string, '-');
        $string = iconv('utf-8', 'us-ascii//TRANSLIT', $string);
        $string = strtolower($string);
        $string = preg_replace('~[^-\w]+~', '', $string);

        return $string;
    }

    public function partition($list, $p) {
        $listlen = count($list);
        $partlen = floor($listlen / $p);
        $partrem = $listlen % $p;
        $partition = array();
        $mark = 0;

        for ($px = 0; $px < $p; $px++) {
            $incr = ($px < $partrem) ? $partlen + 1 : $partlen;
            $partition[$px] = array_slice($list, $mark, $incr);
            $mark += $incr;
        }

        return $partition;
    }

    public function md5($string) {
        return hash('md5', $string);
    }

}