<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\AuthLoginRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class AuthController extends Controller
{
    public function auth(AuthLoginRequest $req){
        $pass = $req['password'];
        $email = $req['email'];
        //Verify if the user with email exists
        $user = User::where('email', $email)->first();

        if(!$user) return response()->json(["message" => "User with email $email cant be found"], 404);

        if(!Hash::check($pass, $user->password)) return response()->json(["message" => "The passwords doesn't match"], 401);

        $token = $user->createToken('web_auth');
        
        return $token->plainTextToken;
    }

    public function logout(Request $req){

        $req->user()->currentAccessToken()->delete();
        return true;
    }
}
