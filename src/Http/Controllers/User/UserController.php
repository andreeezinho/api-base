<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Request\Request;
use App\Config\Auth;
use App\Infra\Services\JWT\JWT;
use App\Domain\Repositories\User\UserRepositoryInterface;
use App\Http\Transformer\User\UserTransformer;
use App\Infra\Services\File\FileService;

class UserController extends Controller{

    protected $userRepository;
    protected $fileService;

    public function __construct(UserRepositoryInterface $userRepository, FileService $fileService){
        parent::__construct();
        $this->userRepository = $userRepository;
        $this->fileService = $fileService;
    }

    public function login(Request $request){
        $data = $request->all();

        $validate = $this->validate($data, [
            'email' => 'required|email',
            'senha' => 'required|string|min:8'
        ]);

        if(is_null($validate)){
            return $this->respJson([
                'message' => 'Dados inválidos',
                'errors' => $this->getErrors()
            ], 422);
        }

        $user = $this->userRepository->login(
            $validate['email'], 
            $validate['senha']
        );

        if(is_null($user)){
            return $this->respJson([
                'message' => 'Usuário ou senha inválido'  
            ]);
        }

        $user = UserTransformer::transform($user);

        $token = JWT::generateToken((array)$user, 30600);

        return $this->respJson([
            'message' => 'Sucesos ao logar',
            'data' => $token
        ]);
    }

    public function profile(Request $request){
        $userData = $request->getHeaders('Authorization');

        $userValidate = JWT::validateToken($userData);

        if(is_null($userValidate)){
            return $this->respJson([
                'message' => 'Usuário não autenticado'
            ], 401);
        }

        $user = $this->userRepository->findByUuid($userValidate['uuid']);

        if(is_null($user)){
            return $this->respJson([
                'message' => 'Usuário não encontrado'
            ], 404);
        }

        $user = UserTransformer::transform($user);

        return $this->respJson([
            'data' => $user
        ]);
    }

    public function index(Request $request){
        $params = $request->getQueryParams();

        $users = $this->userRepository->all($params);

        return $this->respJson([
            'users' => $users
        ]);
    }

    public function store(Request $request){
        $data = $request->all();

        $validate = $this->validate($data, [
            'usuario' => 'required|string|max:20',
            'nome' => 'required|string|max:255',
            'email' => 'required|email',
            'cpf' => 'required|string|max:14',
            'telefone' => 'required|string|max:15',
            'senha' => 'required|string|min:8',
            'ativo' => 'max:1'
        ]);

        if(is_null($validate)){
            return $this->respJson([
                'message' => 'Dados inválidos',
                'errors' => $this->getErrors()
            ], 422);
        }

        $user = $this->userRepository->create($data);

        if(is_null($user)){
            return $this->respJson([
                'message' => 'Erro ao cadastrar usuário'
            ], 500);
        }

        return $this->respJson([
            'message' => 'Cadastro realizado com sucesso',
            'data' => UserTransformer::transform($user)
        ], 201);
    }

    public function update(Request $request, string $uuid){
        $user = $this->userRepository->findByUuid($uuid);

        if(is_null($user)){
            return $this->respJson([
                'message' => 'Usuário não encontrado'
            ], 422);
        }

        $data = $request->all();

        $validate = $this->validate($data, [
            'usuario' => 'required|string|max:20',
            'nome' => 'required|string|max:255',
            'email' => 'required|email',
            'cpf' => 'required|string|max:14',
            'telefone' => 'required|string|max:15',
            'ativo' => 'max:1'
        ]);

        if(is_null($validate)){
            return $this->respJson([
                'message' => 'Dados inválidos',
                'errors' => $this->getErrors()
            ], 422);
        }

        $update = $this->userRepository->update($data, $user->id);

        if(is_null($update)){
            return $this->respJson([
                'message' => 'Erro ao editar o usuário'
            ], 500);
        }
        
        return $this->respJson([
            'message' => 'Sucesso ao atualizar o usuário',
            'data' => UserTransformer::transform($update)
        ], 201);
    }

    public function updateIcon(Request $request, $uuid){
        $user = $this->userRepository->findByUuid($uuid);

        if(is_null($user)){
            return $this->respJson([
                'message' => 'Usuário não encontrado'
            ], 422);
        }

        $data = $request->getFileParams();

        $validate = $this->validate($data, [
            'icone' => 'required'
        ]);

        if(is_null($validate)){
            return $this->respJson([
                'message' => 'Arquivo é obrigatório para continuar',
                'errors' => $this->getErrors()
            ], 422);
        }

        $saveIcon = $this->fileService->uploadFile($data['icone'], '/img/users');
        
        if(is_null($saveIcon)){
            return $this->respJson([
                'message' => 'Não foi possível salvar o arquivo'
            ], 500);
        }

        $update = $this->userRepository->updateIcone(['icone' => $saveIcon['hash_name']], $user->id);

        if(is_null($update)){
            return $this->respJson([
                'message' => 'Erro ao atualizar o icone'
            ], 500);
        }
        
        return $this->respJson([
            'message' => 'Sucesso ao atualizar icone',
            'data' => $saveIcon['hash_name']
        ], 201);
    }

    public function destroy(Request $request, $uuid){
        $user = $this->userRepository->findByUuid($uuid);

        if(is_null($user)){
            return $this->respJson([
                'message' => 'Usuário não encontrado'
            ], 422);
        }

        $delete = $this->userRepository->delete($user->id);

        if(!$delete){
            return $this->respJson([
                'message' => 'Erro ao excluir o usuário'
            ], 500);
        }

        return $this->respJson([
            'message' => 'Sucesso ao excluir usuário'
        ], 201);
    }

}