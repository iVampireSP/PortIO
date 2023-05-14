<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;

class ApiToken
{
    public function handle(Request $request, Closure $next)
    {
        // add json header
        $request->headers->set('Accept', 'application/json');

        // bearer token
        if (! $request->hasHeader('Authorization')) {
            return $this->unauthorized();
        }

        $token = $request->bearerToken();

        $config_token = config('app.api_token');

        if ($config_token == null) {
            return $this->unauthorized();
        }

        if ($token !== $config_token) {
            return $this->unauthorized();
        }

        if ($request->user_id) {
            $user = User::where('id', $request->user_id)->first();
            // if user null
            if (! $user) {
                $http = Http::remote('remote')->asForm();
                $user = $http->get('/users/'.$request->user_id)->json();

                $user = User::create([
                    'id' => $user['id'],
                    'name' => $user['name'],
                    'email' => $user['email'],
                ]);
            }

            Auth::guard('user')->login($user);
        }

        return $next($request);
    }

    public function unauthorized()
    {
        return response()->json([
            'message' => 'Unauthorized.',
        ], 401);
    }
}
