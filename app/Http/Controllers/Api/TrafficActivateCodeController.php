<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\TrafficActivateCode;
use App\Models\User;
use Illuminate\Http\Request;

class TrafficActivateCodeController extends Controller
{
    public function useActivateCode(Request $request)
    {
        $request->validate([
            'code' => 'required|string'
        ]);
        $code = $request->post('code');
        $trafficActivateCode = TrafficActivateCode::where('code', $code)->first();
        if ($trafficActivateCode == null) {
            return $this->error('激活码错误');
        }
        if ($trafficActivateCode->used_at != null) {
            return $this->error('激活码已被使用');
        }
        $trafficActivateCode->user_id = $request->user()->id;
        $trafficActivateCode->used_at = now();
        $trafficActivateCode->update();
        $user = User::find($request->user()->id);
        $user->traffic += $trafficActivateCode->traffic;
        $user->update();
        return $this->success('激活成功');
    }
}
