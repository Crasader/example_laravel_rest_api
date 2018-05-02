<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use App\Helpers\ApiResponseHelper;

class AuthController extends Controller
{
    public function auth(Request $request)
    {
        $credentials = $request->only('email', 'password');

        try {
            if (!$token = JWTAuth::attempt($credentials)) {
                return ApiResponseHelper::getInstance()
                    ->error('Invalid credentials.', null, 401);
            }
        } catch (JWTException $e) {
            return ApiResponseHelper::getInstance()
                ->error('Could not create token.', null, 500);
        }

        return ApiResponseHelper::getInstance()->success('', compact('token'));
    }
}