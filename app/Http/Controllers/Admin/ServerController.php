<?php

namespace App\Http\Controllers\Admin;

use App\Support\Frp;
use App\Models\Server;
use Illuminate\View\View;
use App\Jobs\ServerCheckJob;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Cache;
use GuzzleHttp\Exception\RequestException;

class ServerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return View
     */
    public function index()
    {
        //
        $servers = Server::get();
        // $servers = Server::simplePaginate(10);

        return view('admin.servers.index', compact('servers'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return View
     */
    public function create()
    {
        return view('admin.servers.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate($this->rules());

        $request_data = $request->toArray();
        // $request_data['user_id'] = auth()->id();

        $request_data['allow_http'] = $request->allow_http ?? 0;
        $request_data['allow_https'] = $request->allow_https ?? 0;
        $request_data['allow_tcp'] = $request->allow_tcp ?? 0;
        $request_data['allow_udp'] = $request->allow_udp ?? 0;
        $request_data['allow_stcp'] = $request->allow_stcp ?? 0;
        $request_data['allow_xtcp'] = $request->allow_xtcp ?? 0;
        $request_data['allow_sudp'] = $request->allow_sudp ?? 0;
        $request_data['is_china_mainland'] = $request->is_china_mainland ?? 0;

        $server = Server::create($request_data);

        return redirect()->route('admin.servers.edit', $server);
    }

    /**
     * Display the specified resource.
     *
     * @param  Server  $server
     * @return RedirectResponse|View
     */
    public function show(Server $server)
    {
        try {
            $serverInfo = (object) (new Frp($server))->serverInfo();
        } catch (RequestException $e) {
            Log::error($e->getMessage());

            return redirect()->route('admin.servers.index')->with('error', '服务器连接失败。');
        }

        return view('admin.servers.show', compact('server'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  Server  $server
     * @return View
     */
    public function edit(Server $server)
    {
        $serverInfo = (object) (new Frp($server))->serverInfo();

        return view('admin.servers.edit', compact('server', 'serverInfo'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  Server  $server
     * @return RedirectResponse
     */
    public function update(Request $request, Server $server)
    {
        if (! $request->has('status')) {
            $request->merge(['allow_http' => $request->has('allow_http') ? true : false]);
            $request->merge(['allow_https' => $request->has('allow_https') ? true : false]);
            $request->merge(['allow_tcp' => $request->has('allow_tcp') ? true : false]);
            $request->merge(['allow_udp' => $request->has('allow_udp') ? true : false]);
            $request->merge(['allow_stcp' => $request->has('allow_stcp') ? true : false]);
            $request->merge(['allow_xtcp' => $request->has('allow_xtcp') ? true : false]);
            $request->merge(['allow_sudp' => $request->has('allow_sudp') ? true : false]);
            $request->merge(['is_china_mainland' => $request->has('is_china_mainland') ? true : false]);
        }

        $data = $request->all();

        $server->update($data);

        return redirect()->route('admin.servers.index')->with('success', '服务器成功更新。');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Server  $server
     * @return RedirectResponse
     */
    public function destroy(Server $server)
    {
        $server->delete();

        return redirect()->route('admin.servers.index')->with('success', '服务器成功删除。');
    }

    public function rules($id = null)
    {
        return [
            'name' => 'required|max:20',
            'server_address' => [
                'required',
                Rule::unique('servers')->ignore($id),
            ],
            'server_port' => 'required|integer|max:65535|min:1',
            'token' => 'required|max:50',
            'dashboard_port' => 'required|integer|max:65535|min:1',
            'dashboard_user' => 'required|max:20',
            'dashboard_password' => 'required|max:32',
            'allow_http' => 'boolean',
            'allow_https' => 'boolean',
            'allow_tcp' => 'boolean',
            'allow_udp' => 'boolean',
            'allow_stcp' => 'boolean',
            'allow_xtcp' => 'boolean',
            'allow_sudp' => 'boolean',
            'min_port' => 'required|integer|max:65535|min:1',
            'max_port' => 'required|integer|max:65535|min:1',
            'max_tunnels' => 'required|integer|max:65535|min:1',
        ];
    }

    public function checkServer($id = null)
    {
        if (is_null($id)) {
            // refresh all
            Server::chunk(100, function ($servers) {
                foreach ($servers as $server) {
                    dispatch(new ServerCheckJob($server->id));
                }
            });
        } else {
            if (Server::where('id', $id)->exists()) {
                dispatch(new ServerCheckJob($id));

                return true;
            } else {
                return false;
            }
        }
    }

    public function scanTunnel($server_id)
    {
        $server = Server::find($server_id);
        if (is_null($server)) {
            return false;
        }

        $frp = new Frp($server);

        if ($server->allow_http) {
            $proxies = $frp->httpTunnels()['proxies'] ?? ['proxies' => []];
            $this->cacheProxies($proxies);
        }

        if ($server->allow_https) {
            $proxies = $frp->httpsTunnels()['proxies'] ?? ['proxies' => []];
            $this->cacheProxies($proxies);
        }

        if ($server->allow_tcp) {
            $proxies = $frp->tcpTunnels()['proxies'] ?? ['proxies' => []];
            $this->cacheProxies($proxies);
        }

        if ($server->allow_udp) {
            $proxies = $frp->udpTunnels()['proxies'] ?? ['proxies' => []];
            $this->cacheProxies($proxies);
        }

        if ($server->allow_stcp) {
            $proxies = $frp->stcpTunnels()['proxies'] ?? ['proxies' => []];
            $this->cacheProxies($proxies);
        }

        if ($server->allow_xtcp) {
            $proxies = $frp->xtcpTunnels()['proxies'] ?? ['proxies' => []];
            $this->cacheProxies($proxies);
        }
    }

    private function cacheProxies($proxies)
    {
        foreach ($proxies as $proxy) {
            if (! isset($proxy['name'])) {
                continue;
            }

            $cache_key = 'frpTunnel_data_'.$proxy['name'];

            Cache::put($cache_key, $proxy, 86400);
        }
    }

    public function getTunnel($name)
    {
        $cache_key = 'frpTunnel_data_'.$name;

        return Cache::get($cache_key);
    }
}
