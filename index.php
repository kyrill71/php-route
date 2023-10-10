<?php

use Kyrill\PhpRoute\Controller\TestController;
use Kyrill\PhpRoute\Router;
require 'vendor/autoload.php';
$router = new Router();
$router->addRoute('GET', '/test/{id:([0-9]+)}', [TestController::class, 'index']);
$router->addRoute('DELETE', '/test/{id:([0-9]+)}/delete', [TestController::class, 'index']);
$router->addRoute('GET', '/test', [TestController::class, 'show']);
$router->resolveRoute();