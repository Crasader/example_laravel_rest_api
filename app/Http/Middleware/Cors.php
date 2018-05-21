<?php

namespace App\Http\Middleware;

use Closure;

class Cors
{
    private const ACCESS_CONTROL_PREFIX = 'Access-Control-Allow-';
    private const ALLOW_ORIGIN_HEADER = self::ACCESS_CONTROL_PREFIX . 'Origin';
    private const ALLOW_METHODS_HEADER = self::ACCESS_CONTROL_PREFIX . 'Methods';
    private const ALLOW_HEADERS_HEADER = self::ACCESS_CONTROL_PREFIX . 'Headers';
    private const ALLOWED_METHODS = 'OPTIONS, GET, PUT, POST, DELETE';
    private const ALLOWED_HEADERS = 'Accept, Authorization, Content-Type';
    private const SERVER_HOST_INDEX = 'HTTP_HOST';
    private const ORIGIN_HOST_INDEX = 'HTTP_ORIGIN';

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $defaultOrigin = $request->server(self::SERVER_HOST_INDEX);
        $origin = $request->server(self::ORIGIN_HOST_INDEX, $defaultOrigin);

        return $next($request)
            ->header(self::ALLOW_ORIGIN_HEADER, $origin)
            ->header(self::ALLOW_METHODS_HEADER, self::ALLOWED_METHODS)
            ->header(self::ALLOW_HEADERS_HEADER, self::ALLOWED_HEADERS)
        ;
    }
}
