<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function user(Request $request)
    {
        return $this->success($request->user('sanctum'));
    }

    public function create(Request $request)
    {
        $name = date('Y-m-d H:i:s');
        $token = $request->user()->createToken($name);

        return $this->success([
            'token' => $token->plainTextToken,
        ]);
    }

    public function deleteAll(Request $request)
    {
        $request->user()->tokens()->delete();

        return $this->success();
    }
}
