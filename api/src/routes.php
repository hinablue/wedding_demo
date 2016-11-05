<?php
// Routes
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

$app->map(['OPTIONS','GET','POST'], '/messages', 'Wedding\Controllers\MessagesController:dispatch');
$app->map(['OPTIONS','POST'], '/fileuploader', 'Wedding\Controllers\FilesController:dispatch');
