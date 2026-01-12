<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Request\Request;
use App\Config\Auth;
use App\Domain\Repositories\User\UserRepositoryInterface;

class UserController extends Controller{

    protected $userRepository;

    public function __construct(UserRepositoryInterface $userRepository){
        parent::__construct();
        $this->userRepository = $userRepository;
    }

    public function index(Request $request){
        $params = $request->getQueryParams();

        $users = $this->userRepository->all($params);

        return $this->respJson([
            'users' => $users
        ]);
    }

}