<?php

namespace App\Config;

use App\Domain\Repositories\User\UserRepositoryInterface;
use App\Infra\Persistence\User\UserRepository;

class DependencyProvider {

    private $container;

    public function __construct(Container $container){
        $this->container = $container;
    }

    public function register(){

        $this->container
            ->set(
                UserRepositoryInterface::class,
                new UserRepository()
            );

    }

}