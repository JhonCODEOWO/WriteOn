<?php

namespace App\Http\Controllers\Api;

use App\Dtos\ResponseDto;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\AuthLoginRequest;
use App\Http\Requests\ResetPasswordRequest;
use App\Mail\ResetPassword;
use App\Models\User;
use App\Services\UserService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
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
        
        return response()->json([
            "token" => $token->plainTextToken,
        ]);
    }

    public function logout(Request $req){

        $req->user()->currentAccessToken()->delete();
        return true;
    }

    public function currentUser(Request $req){
        return response()->json([
            "name" => $req->user()->name,
            "id" => $req->user()->id
        ]);
    }
    
    /**
     *  Send a email to the email user account with the token to reset a password.
     *
     * @param  mixed $request The body request of the action
     * @return \Illuminate\Http\JsonResponse A json response with the state of operation.
     */
    public function sendRecoverEmail(Request $request){
        $validated = $request->validate([
            'email' => 'required|exists:users,email|email'
        ]);

        $user = User::where('email', $validated['email'])->firstOrFail();

        $token = Password::createToken($user); //Generate reset token

        try {
            Mail::to($user)->send(new ResetPassword($token, $validated['email']));

            return response()->json(
                (new ResponseDto(true, 'Email sent correctly'))->toArray()
            );
        } catch (Exception $e) {
            Log::error('Error to send the recover email ' . $e->getMessage());
            return response()->json(
                (new ResponseDto(false, "Can't sent the email " . $e->getMessage()))->toArray()
            , 500);
        }
    }
    
    /**
     *  Reset a user password by reset token.
     *
     * @param  mixed $request
     * @return \Illuminate\Http\JsonResponse A json response with the state of operation
     */
    public function resetPassword(ResetPasswordRequest $request){
        $validated = $request->validated();

        $status = Password::reset(
            $validated,
            function(User $user, string $password) {
                $user->forceFill([
                    "password" => Hash::make($password)
                ])->setRememberToken(Str::random(60));

                $user->save();
            }
        );

        if($status === Password::INVALID_TOKEN) return response()->json(
            (new ResponseDto(
                false,
                'The token has expired try again with another fresh token',
                )
            )->toArray(),
            422
        );

        return response()->json(
            (new ResponseDto(true, 'Password changed correctly'))->toArray()
        );
    }
}
