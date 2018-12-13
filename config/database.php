<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Default Database Connection Name
    |--------------------------------------------------------------------------
    |
    | Here you may specify which of the database connections below you wish
    | to use as your default connection for all database work. Of course
    | you may use many connections at once using the Database library.
    |
    */

    'default' => env('DB_CONNECTION', 'mysql'),

    /*
    |--------------------------------------------------------------------------
    | Database Connections
    |--------------------------------------------------------------------------
    |
    | Here are each of the database connections setup for your application.
    | Of course, examples of configuring each database platform that is
    | supported by Laravel is shown below to make development simple.
    |
    |
    | All database work in Laravel is done through the PHP PDO facilities
    | so make sure you have the driver for your particular database of
    | choice installed on your machine before you begin development.
    |
    */

    'connections' => [
        'mysql' => [
            'driver' => 'mysql',
            'read' => [
                'host' => env('DB_READ_HOST', 'localhost'),
            ],
            'write' => [
                'host' => env('DB_WRITE_HOST', 'localhost')
            ],
            'port' => env('DB_PORT', '3306'),
            'database' => env('DB_DATABASE', 'forge'),
            'username' => env('DB_USERNAME', 'forge'),
            'password' => env('DB_PASSWORD', ''),
            'unix_socket' => env('DB_SOCKET', ''),
            'charset' => 'utf8',
            'collation' => 'utf8_unicode_ci',
            'prefix' => '',
            'strict' => true,
            'engine' => null,
        ],
        'NEWCMS' => [
            'driver'    => 'mysql',
            'read' => [
                'host' => env('DB_SLAVE_HOST', 'localhost'),
            ],
            'write' => [
                'host' => env('DB_MASTER_HOST', 'localhost')
            ],
            'port'      => 3306,
            'database'  => env('DB_NEWCMS_DATABASE', ''),
            'username'  => env('DB_NEWCMS_USERNAME', ''),
            'password'  => env('DB_NEWCMS_PASSWORD', ''),
            'charset'   => 'utf8',
            'collation' => 'utf8_unicode_ci',
            'prefix'    => env('DB_PREFIX', ''),
            'timezone'  => env('DB_TIMEZONE', '+00:00'),
            'strict'    => env('DB_STRICT_MODE', false),
        ],
        'test' => [
            'driver'    => 'mysql',
            'read' => [
                'host' => env('DB_SLAVE_HOST', 'localhost'),
            ],
            'write' => [
                'host' => env('DB_MASTER_HOST', 'localhost')
            ],
            'port'      => 3306,
            'database'  => env('DB_TEST_DATABASE', ''),
            'username'  => env('DB_TEST_USERNAME', ''),
            'password'  => env('DB_TEST_PASSWORD', ''),
            'charset'   => 'utf8',
            'collation' => 'utf8_unicode_ci',
            'prefix'    => env('DB_PREFIX', ''),
            'timezone'  => env('DB_TIMEZONE', '+00:00'),
            'strict'    => env('DB_STRICT_MODE', false),
        ],
        // 新增阿里云 MNS。
        'mns'   => [
            'driver'   => 'mns',
            'key'      => env('MNS_ACCESS_KEY', 'access-key'),
            'secret'   => env('MNS_SECRET_KEY', 'secret-key'),
            // 外网连接必须启用 https。
            'endpoint' => 'your-endpoint',
            'queue'    => env('MNS_DEFAULT_QUEUE', 'default-queue-name'),
        ],
        'mongodb_ykh' => [
            'driver'   => 'mongodb',
            'dsn' => env('MONGO_YKH_DSN'),
            'database' => env('MONGO_YKH_DATABASE'),
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Migration Repository Table
    |--------------------------------------------------------------------------
    |
    | This table keeps track of all the migrations that have already run for
    | your application. Using this information, we can determine which of
    | the migrations on disk haven't actually been run in the database.
    |
    */

    'migrations' => 'migrations',

    /*
    |--------------------------------------------------------------------------
    | Redis Databases
    |--------------------------------------------------------------------------
    |
    | Redis is an open source, fast, and advanced key-value store that also
    | provides a richer set of commands than a typical key-value systems
    | such as APC or Memcached. Laravel makes it easy to dig right in.
    |
    */
   
    'redis' => [
        'client' => 'phpredis',
        'default' => [
            'host' => env('REDIS_HOST', '192.168.16.22'),
            'password' => env('REDIS_PASSWORD', 'foobared@1601'),
            'port' => env('REDIS_PORT', 6379),
            'database' => 0,
            'persistent' =>true
        ]
    ],

];
