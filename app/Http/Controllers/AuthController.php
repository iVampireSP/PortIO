<?php

namespace App\Http\Controllers;

use App\Models\User;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    public function redirect(Request $request): RedirectResponse
    {
        $request->session()->put('state', $state = Str::random(40));

        $query = http_build_query([
            'client_id' => config('oauth.client_id'),
            'redirect_uri' => config('oauth.callback_uri'),
            'response_type' => 'code',
            'scope' => 'user realname',
            'state' => $state,
        ]);

        return redirect()->to(config('oauth.oauth_auth_url') . '?' . $query);
    }

    public function callback(Request $request): RedirectResponse
    {
        // $state = $request->session()->pull('state');

        // if (strlen($state) > 0 && $state === $request->state) {
        //     abort(403, 'Invalid state');
        // }

        $http = new Client;

        try {
            $authorize = $http->post(config('oauth.oauth_token_url'), [
                'form_params' => [
                    'grant_type' => 'authorization_code',
                    'client_id' => config('oauth.client_id'),
                    'client_secret' => config('oauth.client_secret'),
                    'redirect_uri' => config('oauth.callback_uri'),
                    'code' => $request->input('code'),
                ],
                'verify' => false,
            ])->getBody();
        } catch (GuzzleException $e) {
        }
        $authorize = json_decode($authorize);

        try {
            $oauth_user = $http->get(config('oauth.oauth_user_url'), [
                'headers' => [
                    'Accept' => 'application/json',
                    'Authorization' => 'Bearer ' . $authorize->access_token,
                ],
                'verify' => false
            ])->getBody();
        } catch (GuzzleException $e) {
        }
        $oauth_user = json_decode($oauth_user);

        $user_sql = (new User)->where('email', $oauth_user->email);
        $user = $user_sql->first();

        // $api_token = null;
        if (is_null($user)) {
            $name = $oauth_user->name;
            $email = $oauth_user->email;
            $password = Hash::make(Str::random(40));
            $user = (new User)->create(compact('name', 'email', 'password'));
            $request->session()->put('auth.password_confirmed_at', time());
        } else {
            if ($user->name != $oauth_user->name) {
                (new User)->where('email', $oauth_user->email)->update([
                    'name' => $oauth_user->name
                ]);
            }
        }

        if (!is_null($oauth_user->real_name_verified_at)) {
            $user_sql->update([
                'realnamed' => true
            ]);
        }

        Auth::guard('web')->loginUsingId($user->id, true);

        return redirect()->route('index');
    }


    public function confirm_password(Request $request): JsonResponse|RedirectResponse
    {
        $request->validate($this->password_rules());

        $request->session()->put('auth.password_confirmed_at', time());

        return $request->wantsJson()
            ? new JsonResponse([], 204)
            : redirect()->intended();
    }

    protected function password_rules(): array
    {
        return [
            'password' => 'required|password',
        ];
    }

    public function logout(): RedirectResponse
    {
        Auth::guard('web')->logout();
        return redirect()->route('index');
    }
}
