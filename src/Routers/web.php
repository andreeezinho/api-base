<?php

use App\Config\Router;
use App\Config\Auth;
use App\Config\Container;
use App\Config\DependencyProvider;

use App\Http\Controllers\teste;

$router = new Router();
$auth = new Auth();
$container = new Container();
$dependencyProvider = new DependencyProvider($container);
$dependencyProvider->register();

$teste = $container->get(teste::class);

$router->create("GET", "/testando", [$teste, 'index']);

return $router;