<?php

namespace App\Exceptions;

use App\Wrappers\ApiResponseHelper;
use Exception;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\Response;
use Illuminate\Validation\ValidationException;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
     */
    protected $dontFlash = [
        'password',
        'password_confirmation',
    ];

    /**
     * Report or log an exception.
     *
     * This is a great spot to send exceptions to Sentry, Bugsnag, etc.
     *
     * @param  \Exception $exception
     * @return void
     * @throws Exception
     */
    public function report(Exception $exception)
    {
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Exception  $exception
     * @return \Illuminate\Http\JsonResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function render($request, Exception $exception)
    {
        if ($exception instanceof ModelNotFoundException) {
            $message = 'Resource not found.';
            return ApiResponseHelper::getInstance()->error(
                $message,
                null,
                Response::HTTP_NOT_FOUND
            );
        }

        if ($exception instanceof ValidationException) {
            $message = 'The given data is invalid.';
            return ApiResponseHelper::getInstance()->error(
                $message,
                $exception->errors(),
                Response::HTTP_UNPROCESSABLE_ENTITY
            );
        }

        return parent::render($request, $exception);
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @param AuthenticationException $exception
     * @return \Illuminate\Http\JsonResponse
     */
    public function unauthenticated($request, AuthenticationException $exception)
    {
        return ApiResponseHelper::getInstance()->error(
            $exception->getMessage(),
            null,
            Response::HTTP_UNAUTHORIZED
        );
    }
}
