<?php

/**
 * San Bartolome High School Website
 *
 * @author   Raphael Marco <pinodex@outlook.ph>
 * @link     http://pinodex.github.io
 */

return array(

    'dev' => array(
        'site' => array(
            'css' => array(
                '/css/normalize.css',
                '/css/foundation.min.css',
                '/fonts/foundation-icons.css',
                '/css/layout.css'
            ),
            'js' => array(
                '/js/modernizr.js',
                '/js/jquery.min.js',
                '/js/foundation.min.js',
                '/js/webfont.js',
                '/js/app.js'
            )
        ),

        'dashboard' => array(
            'css' => array(
                '/css/normalize.css',
                '/css/foundation.min.css',
                '/fonts/foundation-icons.css',
                '/css/dashboard.css'
            ),
            'js' => array(
                '/js/modernizr.js',
                '/js/jquery.min.js',
                '/js/foundation.min.js',
                '/js/webfont.js',
                '/js/dashboard.js'
            )
        )
    ),

    'prod' => array(
        'site' => array(
            'css' => array(
                '/css/commons.min.css',
                '/css/layout.min.css'
            ),
            'js' => array(
                '/js/commons.min.js',
                '/js/app.min.js'
            )
        ),

        'dashboard' => array(
            'css' => array(
                '/css/commons.min.css',
                '/css/dashboard.min.css'
            ),
            'js' => array(
                '/js/commons.min.js',
                '/js/dashboard.min.js'
            )
        )
    )

);