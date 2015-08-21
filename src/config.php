<?php

/**
 * San Bartolome High School Website
 *
 * @author   Raphael Marco <pinodex@outlook.ph>
 * @link     http://pinodex.github.io
 */

/*
 * This file contains the app configurations for Boardy.
 */

/*
 * Debug mode
 * Setting this to true displays error messages.
 */
$app['debug'] = true;

/*
 * Timezone
 */
$app['timezone'] = 'Asia/Manila';

/*
 * Uploads base
 */
$app['uploads.base'] = 'http://cdn.sbhs.ph/_storage/uploads';

/*
 * Asset base
 */
$app['assets.base'] = '/assets';

/*
 * Twig path
 * Templates location for Twig to look for.
 */
$app['twig.path'] = APP . '/views';

/*
 * Twig options
 */
$app['twig.options'] = array(
    'cache' => ROOT . '/cache/twig'
);

/*
 * Profiler configuration
 */
$app['profiler.enable'] = true;
$app['profiler.cache_dir'] = ROOT . '/cache/profiler';
$app['profiler.mount_prefix'] = '/_profiler';

/*
 * Database configuration
 */
$app['db.config'] = array(
    'driver'        => 'mysql',
    'host'            => 'localhost',
    'database'        => 'sbhs',
    'username'        => 'root',
    'password'        => '',
    'prefix'        => 'pinodex_',
    'charset'        => 'utf8',
    'collation'        => 'utf8_unicode_ci',
    'logging'        => false
);

/*
 * Session storage configuration
 */
$app['session.storage.options'] = array(
    'name' => 'session'
);

/*
 * Session database configuration
 */
$app['session.db_options'] = array(
    'db_table'            => $app['db.config']['prefix'] . 'sessions',
    'db_id_col'            => 'id',
    'db_data_col'        => 'data',
    'db_lifetime_col'    => 'lifetime',
    'db_time_col'        => 'time'
);