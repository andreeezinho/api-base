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

    public function login(string $email, string $senha){
        $sql = "SELECT * FROM 
                {$this->model->getTable()}
            WHERE
                email = :email
            AND
                ativo = 1
        ";

        $stmt = $this->conn->prepare($sql);

        $stmt->execute([
            'email' => $email
        ]);

        $stmt->setFetchMode(\PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE, self::CLASS_NAME);
        $user = $stmt->fetch();

        if($user && password_verify($senha, $user->senha)){
            unset($user->id);
            unset($user->senha);
            return $user;
        }

        return null;
    }

    public function all(array $params = []){
        return $this->findAll($params);
    }

    public function create(array $data){
        if(empty($data)){
            return null;
        }

        $user = $this->model->create($data);
            
        if(empty($user->senha)){
            $user->senha = 'senha123';
        }

        if(empty($user->icone)){
            $user->icone = 'default.png';
        }

        $user->senha = password_hash($user->senha, PASSWORD_BCRYPT);

        try {
            $create = $this->save($user);

            if(!$create){
                return null;
            }

            return $this->findByUuid($user->uuid);

        } catch (\Throwable $th) {
            return null;
        }
    }

    public function update(array $data, int $id){
        if(empty($data)){
            return null;
        }

        $data = $this->model->create($data);

        $user = $this->findById($id);

        if(is_null($user)){
            return null;
        }

        try {
            $update = $this->edit($data, $user);

            if(!$update){
                return null;
            }

            return $this->findById($id);;
            
        } catch (\Throwable $th) {
            return $th;
        }
    }

    public function updateIcone(int $id, array $icone, string $dir){}

    public function updateSenha(array $data, int $id){}

    public function delete(int $id){
        if(is_null($this->findById($id))){
            return false;
        }

        try {
            return $this->destroy($id);
        } catch (\PDOException $e) {
            return null;
        }
    }

    public function findUserByEmail(string $email){} 

}