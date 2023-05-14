<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use Illuminate\View\View;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return View
     */
    public function index(Request $request)
    {
        $users = new User();
        $count = User::count();

        foreach ($request->except(['page']) as $key => $value) {
            if (empty($value)) {
                continue;
            }
            if ($request->{$key}) {
                $users = $users->where($key, 'LIKE', '%'.$value.'%');
            }
        }

        $count = $users->count();

        $users = $users->simplePaginate(100);

        return view('admin.users.index', ['users' => $users, 'count' => $count]);
    }

    public function update(Request $request, User $user)
    {
        $user->update($request->all());

        // if not ajax
        if ($request->ajax()) {
            return response()->json([
                'status' => 'success',
                'message' => '更新成功',
            ]);
        } else {
            return redirect()->route('users.index');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  User $user
     * @return RedirectResponse
     */
    public function destroy(User $user)
    {
        $user->delete();

        return back()->with('success', '删除成功');
    }
}
