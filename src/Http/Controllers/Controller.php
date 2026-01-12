<?php

namespace App\Http\Controllers;

use App\Http\Request\Response;

class Controller {

    public function __construct(){}

    public function respJson(array $data = [], int $status = 200){
        Response::respJson($data, $status);
    }

}