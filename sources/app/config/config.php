<?php

defined('BASE_PATH') || define('BASE_PATH', getenv('BASE_PATH') ?: realpath(dirname(__FILE__) . '/../..'));
defined('APP_PATH') || define('APP_PATH', BASE_PATH . '/app');

return new \Phalcon\Config([
    'database' => [
        'adapter'     => 'Mysql',
        'host'        => 'mini_erp_database',
        'username'    => 'mini_erp_user',
        'password'    => 'mini_erp_pass',
        'dbname'      => 'mini_erp',
        'charset'     => 'utf8',
    ],
    'application' => [
        'appDir'         => APP_PATH . '/',
        'controllersDir' => APP_PATH . '/controllers/',
        'modelsDir'      => APP_PATH . '/models/',
        'coreDir'        => APP_PATH . '/core/',
        'migrationsDir'  => APP_PATH . '/migrations/',
       // 'viewsDir'       => APP_PATH . '/views/',
        'cacheDir'       => BASE_PATH . '/cache/',
        'baseUri'        => '/api/',
        'log'        => [
            'path' => '/data/log/mini_erp.log'
        ]
    ]
]);
