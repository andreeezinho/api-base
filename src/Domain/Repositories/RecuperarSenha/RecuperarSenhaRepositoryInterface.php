<?php

namespace App\Domain\Repositories\RecuperarSenha;

interface RecuperarSenhaRepositoryInterface {

    public function create(array $data);

    public function delete(int $id);

    public function findBy(string $field, mixed $value);

}