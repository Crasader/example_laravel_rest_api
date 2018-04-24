<?php

namespace App\Helpers;

use Illuminate\Http\JsonResponse;

class ApiResponseHelper
{
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
     * @param  string|array $data
     * @param  int $status
     * @return JsonResponse
     */
    public function success($data, int $status = 200) : JsonResponse
    {
        return response()->json(['data' => $data], $status);
    }

    /**
     * @param  string $message
     * @param  array|null $data
     * @param  int $status
     * @return JsonResponse
     */
    public function error(string $message, ?array $data, int $status = 404) : JsonResponse
    {
        return response()->json(
            [
                'data' => $data,
                'message' => $message,
            ],
            $status
        );
    }

    private function __construct() {}
}
