<?php

namespace App\Config;

use App\Http\Request\Request;
use App\Http\Request\Response;
use App\Infra\Services\JWT\JWT;

use App\Infra\Persistence\User\UserRepository;

class Auth {

    protected $request;
    protected $userRepository;

    public function __construct(){
        $this->request = new Request();
        $this->userRepository = new UserRepository();
    }

    public function check(){
        $token = $this->request->getHeaders('Authorization');

        $validate = JWT::validateToken($token);

        if(is_null($validate)){
            return false;
        }

        return true;
    }

    public function user(){
        $token = $this->request->getHeaders('Authorization');

        $userValidate = JWT::validateToken($token);

        if(is_null($userValidate)){
            return Response::respJson(['error' => 'Usuário não autenticado'], 401);
        }

        //var_dump($userValidate);
        $user = $this->userRepository->findById((int)$userValidate['code']);

        if(is_null($userValidate)){
            return Response::respJson(['error' => 'Usuário não encontrado'], 404);
        }

        return Response::respJson(['data' => $user]);
    }

}