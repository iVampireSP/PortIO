<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Support\WHMCS;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;


class TrafficController extends Controller
{

    public function price()
    {
        return $this->success([
            'price_per_gb' => config('settings.price_per_gb')
        ]);
    }

    public function providers()
    {
        $config = config('whmcs');

        // 获取 config 的所有的 key
        $providers = array_keys($config);

        return $this->success($providers);
    }

    public function payments(Request $request, $provider)
    {
        try {
            $whmcs = new WHMCS($provider);
        } catch (\Exception $e) {
            return $this->error($e->getMessage());
        }

        $payments = $whmcs->getPayments();

        return $this->success($payments);
    }

    public function index(Request $request)
    {
        $user = auth()->user();

        $traffic = $user->traffic ?? 0;

        $day = now()->day;
        $last_sign_at = Cache::get('traffic_sign:' . $day . '-' . $user->id, null);

        return $this->success([
            'traffic' => $traffic,
            'is_signed' => $last_sign_at
        ]);
    }

    public function charge(Request $request, string $provider)
    {
        $request->validate([
            'payment' => 'required',
            'traffic' => 'required|integer|min:1'
        ]);

        $price = bcmul(config('settings.price_per_gb'), $request->input('traffic'), 2);


        try {
            $whmcs = new WHMCS($provider);
        } catch (\Exception $e) {
            return $this->error('提供商不存在');
        }

        if (!$whmcs->hasPayment($request->input('payment'))) {
            return $this->notFound('支付方式不存在');
        }

        $user = $request->user();

        try {
            $result = $whmcs->api_addTraffic($user->email, $request->input('payment'), $request->input('traffic'), $price);

            return $this->success($result);
        } catch (\Exception $e) {
            return $this->error($e->getMessage());
        }


    }


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
