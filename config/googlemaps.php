<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Google Maps Configuration
    |--------------------------------------------------------------------------
    |
    | This file contains the configuration for Google Maps integration.
    | You need to obtain a Google Maps JavaScript API key from:
    | https://developers.google.com/maps/documentation/javascript/get-api-key
    |
    */

    'api_key' => env('GOOGLE_MAPS_API_KEY', ''),

    /*
    |--------------------------------------------------------------------------
    | Default Map Settings
    |--------------------------------------------------------------------------
    |
    | These are the default settings for Google Maps display
    |
    */

    'default_zoom' => 15,
    'default_map_type' => 'roadmap', // roadmap, satellite, hybrid, terrain

    /*
    |--------------------------------------------------------------------------
    | Map Controls
    |--------------------------------------------------------------------------
    |
    | Configure which controls should be enabled on the map
    |
    */

    'controls' => [
        'map_type_control' => true,
        'street_view_control' => true,
        'fullscreen_control' => true,
        'zoom_control' => true,
    ],

];
