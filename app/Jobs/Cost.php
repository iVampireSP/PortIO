<?php

namespace App\Jobs;

use App\Models\Server;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class Cost implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        Server::with('tunnels')->where('status', 'up')->chunk(100, function ($servers) {
            foreach ($servers as $server) {

                foreach ($server->tunnels as $host) {
                    $host->load('user');

                    Log::debug('------------');
                    Log::debug('主机: ' . $host->name);
                    Log::debug('属于用户: ' . $host->user->name);

                    $cache_key = 'frpTunnel_data_' . $host->client_token;
                    $tunnel_data = Cache::get($cache_key, null);

                    if (!is_null($tunnel_data)) {
                        $traffic = ($tunnel_data['today_traffic_in'] ?? 0) + ($tunnel_data['today_traffic_out'] ?? 0);

                        Log::debug('本次使用的流量: ' . round($traffic / 1024 / 1024 / 1024, 2) ?? 0);

                        $day = date('d');

                        $traffic_key = 'traffic_day_' . $day . '_used_' . $host->id;

                        $used_traffic = Cache::get($traffic_key, 0);
                        if ($used_traffic !== $traffic) {
                            // 保存 2 天
                            Cache::put($traffic_key, $traffic, 86400);

                            $used_traffic_gb = round($used_traffic / 1024 / 1024 / 1024, 2);

                            Log::debug('上次使用的流量 GB: ' . $used_traffic_gb);

                            $used_traffic = $traffic - $used_traffic;

                            Log::debug('流量差值: ' . round($used_traffic / 1024 / 1024 / 1024, 2) . ' GB');
                        }

                        $left_traffic = 0;


                        $used_traffic = abs($used_traffic);

                        Log::debug('实际用量:' . $used_traffic / 1024 / 1024 / 1024);

                        if ($used_traffic > 0 && $left_traffic == 0) {
                            Log::debug('此时 used_traffic: ' . $used_traffic);

                            // 要计费的流量
                            $traffic = round($used_traffic / (1024 * 1024 * 1024), 2) ?? 0;

                            $traffic = abs($traffic);

                            $gb = round($traffic, 2);

                            Log::debug('此时 traffic: ' . $traffic);

                            // lock for update
                            $host->user->balance -= $traffic * $host->user->cost;
                        }
                    }
                }
            }
        });
    }
}
