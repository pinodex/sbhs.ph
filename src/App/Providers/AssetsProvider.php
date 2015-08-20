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

class AssetsProvider implements ServiceProviderInterface {

    protected $app, $map, $addons;

    public function register(Application $app) {
        $this->app = $app;
        $this->map = $app['assets.map'][$app['debug'] ? 'dev' : 'prod'];
        $this->addons = array(
            'css' => array(),
            'js' => array()
        );

        $app['assets'] = $this;
    }

    public function boot(Application $app) {

    }

    public function addCss($path) {
        $this->addons['css'][] = $path;
    }

    public function addJs($path) {
        $this->addons['js'][] = $path;
    }

    public function get($path) {
        return $this->app['assets.base'] . '/' . ltrim($path, '/');
    }

    public function getSiteCss() {
        $output = array();

        foreach ($this->map['site']['css'] as $value) {
            $output[] = $this->app['assets.base'] . '/' . ltrim($value, '/');
        }

        foreach ($this->addons['css'] as $value) {
            $output[] = $this->app['assets.base'] . '/' . ltrim($value, '/');
        }

        return $output;
    }

    public function getSiteJs() {
        $output = array();

        foreach ($this->map['site']['js'] as $value) {
            $output[] = $this->app['assets.base'] . '/' . ltrim($value, '/');
        }

        foreach ($this->addons['js'] as $value) {
            $output[] = $this->app['assets.base'] . '/' . ltrim($value, '/');
        }

        return $output;
    }

    public function getDashboardCss() {
        $output = array();

        foreach ($this->map['dashboard']['css'] as $value) {
            $output[] = $this->app['assets.base'] . '/' . ltrim($value, '/');
        }

        foreach ($this->addons['css'] as $value) {
            $output[] = $this->app['assets.base'] . '/' . ltrim($value, '/');
        }

        return $output;
    }

    public function getDashboardJs() {
        $output = array();

        foreach ($this->map['dashboard']['js'] as $value) {
            $output[] = $this->app['assets.base'] . '/' . ltrim($value, '/');
        }

        foreach ($this->addons['js'] as $value) {
            $output[] = $this->app['assets.base'] . '/' . ltrim($value, '/');
        }

        return $output;
    }

}