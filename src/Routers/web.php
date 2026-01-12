<?php

use App\Config\Router;
use App\Config\Auth;
use App\Config\Container;
use App\Config\DependencyProvider;

use App\Http\Controllers\User\UserController;

$router = new Router();
$auth = new Auth();
$container = new Container();
$dependencyProvider = new DependencyProvider($container);
$dependencyProvider->register();

$userController = $container->get(UserController::class);

$router->create("GET", "/usuarios", [$userController, 'index']);

return $router;