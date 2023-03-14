<?php

namespace App\Http\Controllers;

use App\Models\User;
use GuzzleHttp\Client;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function redirect(Request $request)
    {
        $request->session()->put('state', $state = Str::random(40));

        $query = http_build_query([
            'client_id' => config('oauth.client_id'),
            'redirect_uri' => config('oauth.callback_uri'),
            'response_type' => 'code',
            'scope' => 'user',
            'state' => $state,
        ]);

        return redirect()->to(config('oauth.oauth_auth_url') . '?' . $query);
    }

    public function callback(Request $request)
    {
        $state = $request->session()->pull('state');

        // if (strlen($state) > 0 && $state === $request->state) {
        //     abort(403, 'Invalid state');
        // }

        $http = new Client;

        $authorize = $http->post(config('oauth.oauth_token_url'), [
            'form_params' => [
                'grant_type' => 'authorization_code',
                'client_id' => config('oauth.client_id'),
                'client_secret' => config('oauth.client_secret'),
                'redirect_uri' => config('oauth.callback_uri'),
                'code' => $request->code,
            ],
        ])->getBody();
        $authorize = json_decode($authorize);

        $oauth_user = $http->get(config('oauth.oauth_user_url'), [
            'headers' => [
                'Accept' => 'application/json',
                'Authorization' => 'Bearer ' . $authorize->access_token,
            ],
        ])->getBody();
        $oauth_user = json_decode($oauth_user);

        $user_sql = User::where('email', $oauth_user->email);
        $user = $user_sql->first();

        $api_token = null;
        if (is_null($user)) {
            $name = $oauth_user->name;
            $email = $oauth_user->email;
            $password = Hash::make(Str::random(40));
            $user = User::create(compact('name', 'email', 'password'));
            $request->session()->put('auth.password_confirmed_at', time());
        } else {
            if ($user->name != $oauth_user->name) {
                User::where('email', $oauth_user->email)->update([
                    'name' => $oauth_user->name
                ]);
            }
            $api_token = $user->api_token;
        }

        Auth::loginUsingId($user->id, true);

        return redirect()->route('index');
    }

    public function reset()
    {
        return view('password.reset');
    }

    public function confirm()
    {
        return view('password.confirm');
    }

    public function confirm_password(Request $request)
    {
        $request->validate($this->password_rules());

        $request->session()->put('auth.password_confirmed_at', time());

        return $request->wantsJson()
            ? new JsonResponse([], 204)
            : redirect()->intended();
    }

    protected function password_rules()
    {
        return [
            'password' => 'required|password',
        ];
    }

    public function logout()
    {
        Auth::logout();
        return redirect()->route('index');
    }
}
