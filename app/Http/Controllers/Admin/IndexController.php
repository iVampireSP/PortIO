<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Server;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class IndexController extends Controller
{
    public function index(Request $request)
    {
        // if not login, redirect to login
        if (!Auth::guard('admin')->check()) {
            return view('admin.login');
        } else {
            $servers = Server::where('status', '!=', 'up')->get();

            return view('admin.index', compact('servers'));
        }
    }

    public function login(Request $request)
    {
        // attempt to login
        if (Auth::guard('admin')->attempt($request->only(['email', 'password']), $request->has('remember'))) {
            // if success, redirect to home
//            return redirect()->intended('admin.index');
            return redirect()->route('admin.index');
        } else {
            // if fail, redirect to login with error message
            return redirect()->back()->withErrors(['message' => '用户名或密码错误'])->withInput();
        }
    }

    public function logout()
    {
        // logout
        Auth::guard('admin')->logout();

        return redirect()->route('admin.login');
    }
}
