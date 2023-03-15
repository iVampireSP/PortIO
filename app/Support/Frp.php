<?php

namespace App\Support;

use App\Models\Server;
use Exception;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

class Frp
{
    public string|int $id;

    protected Server $frpServer;

    public function __construct($id)
    {
        $this->frpServer = (new Server)->find($id);
        $this->id = $id;
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
        $addr = 'http://' . $this->frpServer->server_address . ':' . $this->frpServer->dashboard_port . '/api' . $url;
        try {
            $resp = Http::timeout(3)->withBasicAuth($this->frpServer->dashboard_user, $this->frpServer->dashboard_password)->get($addr)->json() ?? [];

            // if under maintenance

            if ($this->frpServer->status !== 'maintenance') {
                if ($this->frpServer->status !== 'up') {
                    $this->frpServer->status = 'up';
                }
            }
        } catch (Exception) {
            $this->frpServer->status = 'down';
            $resp = false;
        } finally {
            $this->frpServer->save();
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
