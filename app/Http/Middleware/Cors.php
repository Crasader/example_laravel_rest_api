<?php

namespace App\Http\Middleware;

use Closure;

class Cors
{
    const ACCESS_CONTROL_PREFIX = 'Access-Control-Allow-';
    const ALLOW_ORIGIN_HEADER = self::ACCESS_CONTROL_PREFIX . 'Origin';
    const ALLOW_METHODS_HEADER = self::ACCESS_CONTROL_PREFIX . 'Methods';
    const ALLOW_HEADERS_HEADER = self::ACCESS_CONTROL_PREFIX . 'Headers';
    const ALLOWED_METHODS = 'OPTIONS, GET, PUT, POST, DELETE';
    const ALLOWED_HEADERS = 'Accept, Authorization, Content-Type';
    const SERVER_HOST_INDEX = 'HTTP_HOST';
    const ORIGIN_HOST_INDEX = 'HTTP_ORIGIN';

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
