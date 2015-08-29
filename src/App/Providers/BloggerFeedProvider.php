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
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use Psr\Http\Message\ResponseInterface;
use App\Models\Cache;

class BloggerFeedProvider implements ServiceProviderInterface {

    protected $app, $currentAccount;

    public function register(Application $app) {
        $this->app = $app;
        $app['blogger_feed'] = $this;
    }

    public function boot(Application $app) {

    }

    public function get($url, $num = 1) {
        $cacheKey = hash('md5', $url);

        if ($cache = Cache::find($cacheKey)) {
            if (time() - strtotime($cache->created) < 3600) {
                return $this->getEntries($cache->value);
            }
        }

        $client = new Client();
        $response = $client->get('http://ajax.googleapis.com/ajax/services/feed/load', [
            'query' => array(
                'v' => '1.0',
                'num' => $num,
                'q' => $url
            )
        ]);

        if ($response->getStatusCode() == 200) {
            $cache = Cache::firstOrNew([
                'id' => $cacheKey
            ]);

            $cache->value = $response->getBody();
            $cache->created = date('Y-m-d H:i:s');
            $cache->save();
        }

        return $this->getEntries($response->getBody());
    }

    private function getEntries($response) {
        if (!$response = @json_decode($response)) {
            return array();
        }

        if ($response->responseStatus != 200) {
            return array();
        }

        return $response->responseData->feed->entries;
    }

}