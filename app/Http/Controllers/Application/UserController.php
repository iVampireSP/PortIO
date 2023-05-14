<?php

namespace App\Http\Controllers\Application;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        //
    }

    public function addTraffic(Request $request, User $user)
    {
        $request->validate([
            'traffic' => 'required|numeric|min:1'
        ]);

        $traffic = $request->input('traffic');

        Cache::lock('user_traffic_' . $user->id)->get(function () use ($user, $traffic) {
            $user->update([
                'traffic' => $user->traffic + $traffic
            ]);
        });

        return response()->json([
            'message' => 'success',
        ]);
    }
}
