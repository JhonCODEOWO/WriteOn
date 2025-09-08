<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\AuthLoginRequest;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function auth(AuthLoginRequest $req){
        
    }

    public function logout(Request $req){

        return true;
    }
}
