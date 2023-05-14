<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;

class TrafficController extends Controller
{
    public function free()
    {
        $user = auth()->user();

        $day = now()->day;
        $last_sign_at = Cache::get('traffic_sign:' . $day . '-' . $user->id, null);

        return $this->success([
            'traffic' => $user->traffic ?? 0,
            'is_signed' => $last_sign_at
        ]);
    }

    public function sign()
    {
        $user = auth()->user();

        $day = now()->day;
        $last_sign_at = Cache::get('traffic_sign:' . $day . '-' . $user->id, null);

        // 如果 24 小时内已经签到过了，就不再签到。
        if ($last_sign_at) {
            return $this->error('您已经签到过了，请明天再来吧。');
        }

        // 随机 config('settings.sign.min') 到 config('settings.sign.max')
        $traffic = rand(config('settings.sign.min'), config('settings.sign.max'));

        if ($traffic !== -1) {
            $user->traffic += $traffic;
            $user->save();
        }

        Cache::put('traffic_sign:' . $day . '-' . $user->id, true);

        return $this->success([
            'traffic' => $traffic,
            'last_sign_at' => now()->toDateTimeString(),
        ]);
    }
}
