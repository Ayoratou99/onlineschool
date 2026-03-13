<?php

return [

    'entities' => [

    ],


    'cache' => [
        'enabled' => env('STATISTIQUE_CACHE_ENABLED', true),
        'ttl'     => env('STATISTIQUE_CACHE_TTL', 300), // 5 minutes
        'prefix'  => 'stats',
    ],


    'operations' => [
        'count',
        'count_distinct',
        'sum',
        'avg',
        'min',
        'max',
    ],


    'period_intervals' => [
        'hour',
        'day',
        'week',
        'month',
        'year',
    ],
];
