<?php

namespace App\Support;

use App\Models\Server;
use Exception;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

class Frp
{
    public string|int $id;

    protected Server $server;

    public function __construct(Server $server)
    {
        $this->server = $server;
        $this->id = $server->id;
    }

    public function serverInfo()
    {
        return $this->cache('serverinfo', '/serverinfo');
    }

    protected function cache($key, $path = null)
    {
        $cache_key = 'frpTunnel_' . $this->id . '_' . $key;
        if (Cache::has($cache_key)) {
            return Cache::get($cache_key);
        } else {
            if ($path == null) {
                return null;
            } else {
                $data = $this->get($path);
                if (!$data) {
                    // request failed
                    Cache::put($cache_key, [], 10);
                } else {
                    Cache::put($cache_key, $data, 60);
                }

                return $data;
            }
        }
    }

    protected function get($url)
    {
        $addr = 'http://' . $this->server->server_address . ':' . $this->server->dashboard_port . '/api' . $url;
        try {
            $resp = Http::timeout(3)->withBasicAuth($this->server->dashboard_user, $this->server->dashboard_password)->get($addr)->json() ?? [];

            // if under maintenance

            if ($this->server->status !== 'maintenance') {
                if ($this->server->status !== 'up') {
                    $this->server->status = 'up';
                }
            }
        } catch (Exception) {
            $this->server->status = 'down';
            $resp = false;
        } finally {
            $this->server->save();
        }

        return $resp;
    }

    public function tcpTunnels()
    {
        return $this->cache('tcpTunnels', '/proxy/tcp');
    }

    public function udpTunnels()
    {
        return $this->cache('udpTunnels', '/proxy/udp');
    }

    public function httpTunnels()
    {
        return $this->cache('httpTunnels', '/proxy/http');
    }

    public function httpsTunnels()
    {
        return $this->cache('httpsTunnels', '/proxy/https');
    }

    public function stcpTunnels()
    {
        return $this->cache('stcpTunnels', '/proxy/stcp');
    }

    public function xtcpTunnels()
    {
        return $this->cache('stcpTunnels', '/proxy/xtcp');
    }

    public function traffic($name)
    {
        return $this->cache('traffic_' . $name, '/traffic/' . $name);
    }

    public function close($run_id)
    {
        return $this->get('/client/close/' . $run_id);
    }
}
