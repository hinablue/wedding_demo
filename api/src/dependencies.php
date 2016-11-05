<?php
// DIC configuration
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

$container = $app->getContainer();

// monolog
$container['logger'] = function ($c) {
    $settings = $c->get('settings')['logger'];
    $logger = new Monolog\Logger($settings['name']);
    $stream = new Monolog\Handler\StreamHandler($settings['path'], $settings['level']);
    $format = '[%datetime%]'.PHP_EOL.'%channel%.%level_name%: %message% %context% %extra%'.PHP_EOL.PHP_EOL;
    $stream->setFormatter(new Monolog\Formatter\LineFormatter($format));
    $logger->pushProcessor(new Monolog\Processor\UidProcessor());
    $logger->pushProcessor(new Monolog\Processor\WebProcessor());
    $logger->pushHandler($stream);
    return $logger;
};

// Database
$container['db'] = function ($c) {
  $settings = $c->get('settings')['db'];
  $capsule = new \Illuminate\Database\Capsule\Manager;
  $capsule->addConnection($settings);

  $capsule->setAsGlobal();
  $capsule->bootEloquent();

  return $capsule;
};

$container['phpErrorHandler'] = function ($c) {
  return function (Request $request, Response $response, $args) use ($c) {
    $datetime = gmdate("D, d M Y H:i:s").' GMT';
    return $c['response']->withAddedHeader('Last-Modified', $datetime)
      ->withAddedHeader('X-Frame-Options', 'SAMEORIGIN')
      ->withAddedHeader('Access-Control-Allow-Origin', '*')
      ->withAddedHeader('Access-Control-Allow-Methods', 'GET, POST, DELETE, PUT, OPTIONS')
      ->withAddedHeader(
        'Access-Control-Allow-Headers',
        'X-Requested-With, X-HTTP-Method-Override, Content-Type, Cache-Control, Accept, Origin, Accept, Key, Authorization'
      )->withJson([
        'status' => 'err',
        'messages' => 'Error.'
      ], 500);
  };
};

$container['errorHandler'] = function ($c) {
  return function (Request $request, Response $response, $args) use ($c) {
    $datetime = gmdate("D, d M Y H:i:s").' GMT';
    return $c['response']->withAddedHeader('Last-Modified', $datetime)
      ->withAddedHeader('X-Frame-Options', 'SAMEORIGIN')
      ->withAddedHeader('Access-Control-Allow-Origin', '*')
      ->withAddedHeader('Access-Control-Allow-Methods', 'GET, POST, DELETE, PUT, OPTIONS')
      ->withAddedHeader(
        'Access-Control-Allow-Headers',
        'X-Requested-With, X-HTTP-Method-Override, Content-Type, Cache-Control, Accept, Origin, Accept, Key, Authorization'
      )->withJson([
        'status' => 'err',
        'messages' => 'Error.'
      ], 500);
  };
};

$container['notFoundHandler'] = function ($c) {
  return function (Request $request, Response $response) use ($c) {
    $datetime = gmdate("D, d M Y H:i:s").' GMT';
    return $c['response']->withAddedHeader('Last-Modified', $datetime)
      ->withAddedHeader('X-Frame-Options', 'SAMEORIGIN')
      ->withAddedHeader('Access-Control-Allow-Origin', '*')
      ->withAddedHeader('Access-Control-Allow-Methods', 'GET, POST, DELETE, PUT, OPTIONS')
      ->withAddedHeader(
        'Access-Control-Allow-Headers',
        'X-Requested-With, X-HTTP-Method-Override, Content-Type, Cache-Control, Accept, Origin, Accept, Key, Authorization'
      )->withJson([
      'status' => 'err',
      'messages' => 'Not Found.'
    ], 404);
  };
};

$container['notAllowedHandler'] = function ($c) {
  return function (Request $request, Response $response, $methods) use ($c) {
    $datetime = gmdate("D, d M Y H:i:s").' GMT';
    return $c['response']->withAddedHeader('Last-Modified', $datetime)
      ->withAddedHeader('X-Frame-Options', 'SAMEORIGIN')
      ->withAddedHeader('Access-Control-Allow-Origin', '*')
      ->withAddedHeader('Access-Control-Allow-Methods', 'GET, POST, DELETE, PUT, OPTIONS')
      ->withAddedHeader(
        'Access-Control-Allow-Headers',
        'X-Requested-With, X-HTTP-Method-Override, Content-Type, Cache-Control, Accept, Origin, Accept, Key, Authorization'
      )->withAddedHeader(
        'Allow',
        implode(', ', $methods)
      )->withJson([
        'status' => 'err',
        'messages' => 'Method not allow.'
      ], 405);
  };
};

$container[Wedding\Controllers\MessagesController::class] = function ($c) {
  $logger = $c->get('logger');
  try {
    return new Wedding\Controllers\MessagesController($c);
  } catch (\Throwable $t) {
    $logger->addInfo($t);
  }
};
$container[Wedding\Controllers\FilesController::class] = function ($c) {
  $logger = $c->get('logger');
  try {
    return new Wedding\Controllers\FilesController($c);
  } catch (\Throwable $t) {
    $logger->addInfo($t);
  }
};
