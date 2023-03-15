<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class Admin
{
    /**
     * Handle an incoming request.
     *
     * @param Request                      $request
     * @param Closure(Request): (Response) $next
     *
     * @return Response
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!$request->user('sanctum')?->isAdmin()) {
            return response()->json([
                'message' => 'You are not authorized to access this resource.'
            ], 403);
        }

        return $next($request);
    }
}
