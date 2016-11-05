<?php
// Application middleware

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

$app->add(function (Request $request, Response $response, $next) {
  $datetime = gmdate("D, d M Y H:i:s").' GMT';
  $response = $next($request, $response);
  if ($response->getStatusCode() !== 302 && $response->getStatusCode() !== 202) {
    $response = $response->withAddedHeader('Last-Modified', $datetime)
      ->withAddedHeader('X-Frame-Options', 'SAMEORIGIN')
      ->withAddedHeader('Access-Control-Allow-Origin', '*')
      ->withAddedHeader('Access-Control-Allow-Methods', 'GET, POST, DELETE, PUT, OPTIONS')
      ->withAddedHeader(
        'Access-Control-Allow-Headers',
        'X-Requested-With, X-HTTP-Method-Override, Content-Type, Cache-Control, Accept, Origin, Accept, Key, Authorization'
      );
  }
  return $response;
});

