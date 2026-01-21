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

// - Rotas

//usuarios
$router->create("POST", "/auth", [$userController, 'login']);
$router->create("GET", "/me", [$userController, 'profile'], $auth);
$router->create("GET", "/usuarios", [$userController, 'index'], $auth);
$router->create("POST", "/usuarios", [$userController, 'store'], $auth);
$router->create("PUT", "/usuarios/{uuid}", [$userController, 'update'], $auth);
$router->create("PATCH", "/usuarios/{uuid}/password", [$userController, 'updatePassword']);
$router->create("POST", "/usuarios/{uuid}/icon", [$userController, 'updateIcon']);
$router->create("DELETE", "/usuarios/{uuid}", [$userController, 'destroy'], $auth);

return $router;