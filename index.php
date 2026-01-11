<?php

    date_default_timezone_set('America/Sao_Paulo');

    require 'vendor/autoload.php';

    $dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
    $dotenv->load();

    $router = require 'src/Routers/web.php';

    $router->init();