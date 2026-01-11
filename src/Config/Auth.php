<?php

namespace App\Config;

use App\Request\Request;
use App\Config\JWT;

use App\Repositories\User\UserRepository;

class Auth {

    protected $request;
    protected $userRepository;

    public function __construct(){
        $this->request = new Request();
        $this->userRepository = new UserRepository();
    }

    public function login($user){
        if(is_null($user)){
            return false;
        }

        $token = JWT::generateToken((array)$user, 30600);

        return $token;
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
            return $this->request->respJson(['error' => 'Usuário não autenticado'], 401);
        }

        //var_dump($userValidate);
        $user = $this->userRepository->findById((int)$userValidate['code']);

        if(is_null($userValidate)){
            return $this->request->respJson(['error' => 'Usuário não encontrado'], 404);
        }

        return $this->request->respJson(['data' => $user]);
    }

}