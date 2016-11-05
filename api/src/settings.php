<?php

return [
  'settings' => [
    'displayErrorDetails' => true, // set to false in production
    'addContentLengthHeader' => false, // Allow the web server to send the content-length header
    'determineRouteBeforeAppMiddleware' => true,

    // Database
    'db' => [
      'driver' => 'mysql',
      'host' => '127.0.0.1',
      'database' => '',
      'username' => '',
      'password' => '',
      'charset'   => 'utf8mb4',
      'collation' => 'utf8mb4_unicode_ci',
      'prefix'    => ''
    ],

    'token' => [
      'lifetime' => 86400,
      'crypt' => ''
    ],

    // Monolog settings
    'logger' => [
      'name' => 'Wedding-API',
      'path' => __DIR__ . '/../logs/app.log',
      'level' => \Monolog\Logger::DEBUG
    ]
  ]
];

