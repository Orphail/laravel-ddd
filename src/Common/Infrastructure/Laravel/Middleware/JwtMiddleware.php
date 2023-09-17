<?php

namespace Src\Common\Infrastructure\Laravel\Middleware;

use Closure;
use Exception;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Http\Middleware\BaseMiddleware;

class JwtMiddleware extends BaseMiddleware
{

    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        try {
            if (in_array($request->decodedPath(), ['auth/login', 'auth/refresh', 'login', 'refresh']) && $request->method() == 'POST') {
                return $next($request);
            }

            JWTAuth::parseToken()->authenticate();

        } catch (Exception $e) {
            if ($e instanceof TokenInvalidException){
                return response()->json([
                    "meta" => [ "success" => false,"errors" => ["Token Invalid"]]], Response::HTTP_UNAUTHORIZED );
            }else if ($e instanceof TokenExpiredException){
                return response()->json([
                    "meta" => [ "success" => false,"errors" => ["Token expired"]]], Response::HTTP_UNAUTHORIZED );
            }else if ($e instanceof \Tymon\JWTAuth\Exceptions\JWTException) {
                return response()->json([
                    "meta" => [ "success" => false,"errors" => ["Authorization Token Not Found"]]], Response::HTTP_UNAUTHORIZED );
            }
        }
        return $next($request);
    }
}
