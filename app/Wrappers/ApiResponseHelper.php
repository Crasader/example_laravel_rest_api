<?php

namespace App\Wrappers;

use Illuminate\Http\JsonResponse;

class ApiResponseHelper
{
    /**
     * @var ApiResponseHelper
     */
    private static $instance = null;

    /**
     * @return ApiResponseHelper
     */
    public static function getInstance() : ApiResponseHelper
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    /**
     * @param  string $message
     * @param  string|array|null $data
     * @param  int $status
     * @return JsonResponse
     */
    public function success(string $message, $data = null, int $status = 200) : JsonResponse
    {
        return response()->json([
            'data' => $data,
            'message' => $message,
        ], $status);
    }

    /**
     * @param  string $message
     * @param array|null $errors
     * @param  int $status
     * @return JsonResponse
     */
    public function error(string $message, ?array $errors = null, int $status = 404) : JsonResponse
    {
        return response()->json([
            'errors' => $errors,
            'message' => $message,
        ], $status);
    }

    private function __construct() {}
}
