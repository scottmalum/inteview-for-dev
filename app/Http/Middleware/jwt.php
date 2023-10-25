<?php

namespace App\Http\Middleware;

use Closure;
use JWTAuth;
use Exception;
use Illuminate\Support\Facades\Response;
use Tymon\JWTAuth\Http\Middleware\BaseMiddleware;

class Jwt extends BaseMiddleware
{

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        try {
            JWTAuth::parseToken()->authenticate();
        } catch (Exception $e) {
            if ($e instanceof \Tymon\JWTAuth\Exceptions\TokenInvalidException) {
                return $this->sendError('Token is Invalid');
            } else if ($e instanceof \Tymon\JWTAuth\Exceptions\TokenExpiredException) {
                return $this->sendError('Token is Expired');
            } else {
                return $this->sendError('Authorization Token not found');
            }
        }
        return $next($request);
    }

    private function sendError($error, $code = 401)
    {
        return Response::json([
            'success' => false,
            'message' => $error,
        ], $code);
    }
}
