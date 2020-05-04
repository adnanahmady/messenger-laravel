<?php

namespace App\Http\Controllers\Auth;

use App\Custom\Body;
use App\Http\Controllers\Controller;
use App\User;
use Firebase\JWT\JWT;
use Illuminate\Support\Facades\Hash;

class OtpController extends Controller
{
    public function verify()
    {
        $user = User::where(request(['otp']))->first();
        if (!$user) {
            return response()->json(Body::set(__('user not found')), 404);
        }
        $token = JWT::encode([
            'email' => $user->email,
            'name' => $user->name,
            'iss' => strtotime('now + 20 minutes')
        ], base64_encode('forTokensOnly'.$user->email));

        $user->verify($token);

        return response()
            ->json(Body::set(compact('token'))
            ->message(__('welcome to AMassenger')));
    }
}
