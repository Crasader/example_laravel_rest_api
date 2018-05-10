<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Wrappers\ApiResponseHelper;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class AuthController extends Controller
{
    /**
     * @var ApiResponseHelper
     */
    private $response;

    public function __construct()
    {
        $this->response = ApiResponseHelper::getInstance();
    }

    /**
     * Get a JWT via given credentials.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function login(Request $request): JsonResponse
    {
        $credentials = $request->only(['email', 'password']);
        $token = auth()->attempt($credentials);

        if (!$token) {
            $message = 'Bad credentials.';
            return $this->response->error($message, null, Response::HTTP_UNAUTHORIZED);
        }

        return $this->respondWithToken($token);
    }

    /**
     * Log the user out (Invalidate the token).
     *
     * @return JsonResponse
     */
    public function logout(): JsonResponse
    {
        auth()->logout();

        return $this->response->success('Successfully logged out');
    }

    /**
     * Get the token array structure.
     *
     * @param  string $token
     *
     * @return JsonResponse
     */
    protected function respondWithToken($token): JsonResponse
    {
        $data = [
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60,
        ];

        return $this->response->success('', $data);
    }
}