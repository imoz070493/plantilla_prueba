<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;

class APILoginController extends Controller
{
    public function login()
    {
        // get email and password from request
        $credentials = request(['username', 'password']);

        // try to auth and get the token using api authentication
        if (!$token = auth('api')->attempt($credentials)) {
            // if the credentials are wrong we send an unauthorized error in json format
            return response()->json('Acceso denegado', 401);
        }
        return response()->json([
            'code' => 20000,
            'data' => [

                'token' => $token,
                'type' => 'bearer', // you can ommit this
                'expires' => auth('api')->factory()->getTTL() * 60, // time to expiration
            ],
        ]);
    }
    public function logout()
    {
        auth('api')->logout();
        return response()->json([
            "code" => 20000,
            "data" => 'success',
        ]);
    }

}