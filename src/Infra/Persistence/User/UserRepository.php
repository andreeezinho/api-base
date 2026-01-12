<?php

namespace App\Infra\Persistence\User;

use App\Config\Database;
use App\Domain\Models\User\User;
use App\Domain\Repositories\User\UserRepositoryInterface;
use App\Infra\Persistence\Traits\CrudTrait;
use App\Infra\Persistence\Traits\FindTrait;

class UserRepository implements UserRepositoryInterface {

    const CLASS_NAME = User::class;

    use CrudTrait;
    use FindTrait;

    protected $conn;
    protected $model;

    public function __construct() {
        $this->conn = Database::getInstance()->getConnection();
        $this->model = new User();
    }

    public function login(string $usuario, string $senha){}

    public function all(array $params = []){
        return $this->findAll($params);
    }

    public function create(array $data){}

    public function update(array $data, int $id){}

    public function updateIcone(int $id, array $icone, string $dir){}

    public function updateSenha(array $data, int $id){}

    public function delete(int $id){}

    public function findUserByEmail(string $email){} 

}