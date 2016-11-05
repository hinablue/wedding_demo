<?php

namespace Wedding\Controllers;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

use Slim\Container;
use \Firebase\JWT\JWT;

abstract class BaseController {
  protected $logger;
  protected $db;
  protected $settings;

  public function __construct(Container $c) {
      $this->logger = $c->get('logger');
      $this->db = $c->get('db');
      $this->settings = $c->get('settings');
  }
}
