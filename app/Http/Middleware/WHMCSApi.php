<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;

class WHMCSApi
{
    public function handle(Request $request, Closure $next)
    {
        // add json header
        $request->headers->set('Accept', 'application/json');

        // bearer token
        if (!$request->hasHeader('Authorization')) {
            return $this->unauthorized('No Authorization header found.');
        }

        $tokens = $request->bearerToken();
        $tokens = explode('|', $tokens);

        if (count($tokens) !== 2) {
            return $this->unauthorized('Invalid Authorization header.');
        }

        $whmcs_id = $tokens[0];
        $token = $tokens[1];

        $whmcs = config('whmcs.' . $whmcs_id);

        if (is_null($whmcs)) {
            return $this->unauthorized('Invalid WHMCS ID.');
        }


        $config_token = config('whmcs.' . $whmcs_id . '.api_token');

        if ($config_token == null) {
            return $this->unauthorized('Token not allowed.');
        }

        if ($token !== $config_token) {
            return $this->unauthorized('Invalid token.');
        }

        if ($request->user_id) {
            $user = User::where('id', $request->user_id)->first();
            // if user null
            if (!$user) {
                $http = Http::remote('remote')->asForm();
                $user = $http->get('/users/' . $request->user_id)->json();

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

    public function unauthorized($message = 'Unauthorized.')
    {
        return response()->json([
            'message' => $message,
        ], 401);
    }
}
