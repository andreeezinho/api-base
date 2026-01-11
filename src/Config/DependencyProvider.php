<?php

namespace App\Config;

class DependencyProvider {

    private $container;

    public function __construct(Container $container){
        $this->container = $container;
    }

    public function register(){

        // $this->container
        //     ->set(
        //         IExemplo::class,
        //         new ExemploRepository()
        //     );

    }

}