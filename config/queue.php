<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Default Queue Connection Name
    |--------------------------------------------------------------------------
    |
    | Laravel's queue API supports an assortment of back-ends via a single
    | API, giving you convenient access to each back-end using the same
    | syntax for every one. Here you may define a default connection.
    |
    */

    'default' => env('QUEUE_CONNECTION', 'sync'),

    /*
    |--------------------------------------------------------------------------
    | Queue Connections
    |--------------------------------------------------------------------------
    |
    | Here you may configure the connection information for each server that
    | is used by your application. A default configuration has been added
    | for each back-end shipped with Laravel. You are free to add more.
    |
    | Drivers: "sync", "database", "beanstalkd", "sqs", "redis", "null"
    |
    */

    'connections' => [

        'sync' => [
            'driver' => 'sync',
        ],

        'database' => [
            'driver' => 'database',
            'table' => 'jobs',
            'queue' => 'default',
            'retry_after' => 90,
            'after_commit' => false,
        ],

        'areas' => [
            'driver' => 'database',
            'table' => 'jobs',
            'queue' => 'areas',
            'retry_after' => 90,
            'after_commit' => false,
        ],

        'assuntos' => [
            'driver' => 'database',
            'table' => 'jobs',
            'queue' => 'assuntos',
            'retry_after' => 90,
            'after_commit' => false,
        ],

        'bancas' => [
            'driver' => 'database',
            'table' => 'jobs',
            'queue' => 'bancas',
            'retry_after' => 90,
            'after_commit' => false,
        ],

        'cargos' => [
            'driver' => 'database',
            'table' => 'jobs',
            'queue' => 'cargos',
            'retry_after' => 90,
            'after_commit' => false,
        ],

        'comentarios' => [
            'driver' => 'redis',
            'connection' => 'default',
            'queue' => 'comentarios',
            'retry_after' => 120,
            'block_for' => null,
        ],
        'editais' => [
            'driver' => 'database',
            'table' => 'jobs',
            'queue' => 'editais',
            'retry_after' => 60,
            'after_commit' => false,
        ],
        'escolaridades' => [
            'driver' => 'redis',
            'connection' => 'default',
            'queue' => 'escolaridades',
            'retry_after' => 180,
            'block_for' => null,
        ],
        'materias' => [
            'driver' => 'database',
            'table' => 'jobs',
            'queue' => 'materias',
            'retry_after' => 120,
            'after_commit' => false,
        ],
        'orgaos' => [
            'driver' => 'redis',
            'connection' => 'default',
            'queue' => 'orgaos',
            'retry_after' => 90,
            'block_for' => null,
        ],
        'profissoes' => [
            'driver' => 'database',
            'table' => 'jobs',
            'queue' => 'profissoes',
            'retry_after' => 60,
            'after_commit' => false,
        ],
        'questoes' => [
            'driver' => 'redis',
            'connection' => 'default',
            'queue' => 'questoes',
            'retry_after' => 120,
            'block_for' => null,
        ],
        'provas' => [
            'driver' => 'database',
            'table' => 'jobs',
            'queue' => 'provas',
            'retry_after' => 90,
            'after_commit' => false,
        ],
    
        'beanstalkd' => [
            'driver' => 'beanstalkd',
            'host' => 'localhost',
            'queue' => 'default',
            'retry_after' => 90,
            'block_for' => 0,
            'after_commit' => false,
        ],

        'sqs' => [
            'driver' => 'sqs',
            'key' => env('AWS_ACCESS_KEY_ID'),
            'secret' => env('AWS_SECRET_ACCESS_KEY'),
            'prefix' => env('SQS_PREFIX', 'https://sqs.us-east-1.amazonaws.com/your-account-id'),
            'queue' => env('SQS_QUEUE', 'default'),
            'suffix' => env('SQS_SUFFIX'),
            'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
            'after_commit' => false,
        ],

        'redis' => [
            'driver' => 'redis',
            'connection' => 'default',
            'queue' => env('REDIS_QUEUE', 'default'),
            'retry_after' => 90,
            'block_for' => null,
            'after_commit' => false,
        ],

    ],

    /*
    |--------------------------------------------------------------------------
    | Failed Queue Jobs
    |--------------------------------------------------------------------------
    |
    | These options configure the behavior of failed queue job logging so you
    | can control which database and table are used to store the jobs that
    | have failed. You may change them to any database / table you wish.
    |
    */

    'failed' => [
        'driver' => env('QUEUE_FAILED_DRIVER', 'database-uuids'),
        'database' => env('DB_CONNECTION', 'mysql'),
        'table' => 'failed_jobs',
    ],

];
