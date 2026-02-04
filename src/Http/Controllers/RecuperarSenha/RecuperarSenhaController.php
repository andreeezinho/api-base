<?php

namespace App\Http\Controllers\RecuperarSenha;

use App\Http\Controllers\Controller;
use App\Http\Request\Request;
use App\Infra\Services\JWT\JWT;
use App\Domain\Repositories\RecuperarSenha\RecuperarSenhaRepositoryInterface;
use App\Http\Transformer\RecuperarSenha\RecuperarSenhaTransformer;
use App\Infra\Services\Log\LogService;
use App\Infra\Services\Email\EmailService;

class RecuperarSenhaController extends Controller {

    protected $recuperarSenhaRepository;
    protected $emailService;

    public function __construct(RecuperarSenhaRepositoryInterface $recuperarSenhaRepository, EmailService $emailService){
        parent::__construct();
        $this->recuperarSenhaRepository = $recuperarSenhaRepository;
        $this->emailService = $emailService;
    }

}