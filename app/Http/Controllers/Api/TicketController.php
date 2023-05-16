<?php

namespace App\Http\Controllers\Api;

use App\Support\WHMCS;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class TicketController extends Controller
{
    public function submit(Request $request, string $provider) {
        $request->validate([
            'title' => 'required',
            'content' => 'required',
        ]);

        try {
            $whmcs = new WHMCS($provider);
        } catch (\Exception $e) {
            return $this->error('提供商不存在');
        }

        $user = $request->user();

        $result = $whmcs->api_openTicket($user->email, $request->input('title'), $request->input('content'));

        return $this->success($result);

    }
}
