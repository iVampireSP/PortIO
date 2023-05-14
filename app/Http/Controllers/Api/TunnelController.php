<?php

namespace App\Http\Controllers\Api;

use App\Models\Server;
use App\Models\Tunnel;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\TunnelRequest;
use App\Support\Frp;
use Illuminate\Support\Facades\Cache;

class TunnelController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        return $this->success(
            $request->user()->tunnels()->get()
        );
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'protocol' => 'required',
            'local_address' => 'required',
            'server_id' => 'required',
        ]);

        $data = $request->only([
            'name', 'protocol', 'local_address', 'server_id', 'remote_port', 'custom_domain',
        ]);

        if (!strpos($request->input('local_address'), ':')) {
            return $this->error('本地地址必须包含端口号。');
        }

        $local_ip_port = explode(':', $request->input('local_address'));

        // port must be a number
        if (!is_numeric($local_ip_port[1])) {
            return $this->error('端口号必须是数字。');
        }

        // port must be a number between 1 and 65535
        if ($local_ip_port[1] < 1 || $local_ip_port[1] > 65535) {
            return $this->error('本地地址端口号必须在 1 和 65535 之间。');
        }

        $server = Server::find($request->input('server_id'));

        if (is_null($server)) {
            return $this->error('找不到服务器。');
        }

        if (Tunnel::where('server_id', $server->id)->count() > $server->max_tunnels) {
            return $this->error('服务器无法开设更多隧道了。');
        }

        if ($request->input('protocol') == 'http' || $request->input('protocol') == 'https') {
            // if (!auth()->user()->verified_at) {
            //     return failed('必须要先实名认证才能创建 HTTP(S) 隧道。');
            // }

            if ($request->filled('remote_port')) {
                return $this->error('此协议不支持指定远程端口号。');
            }

            $data['remote_port'] = null;

            // 检测 域名格式 是否正确
            if (!preg_match('/^(?=^.{3,255}$)[a-zA-Z0-9][-a-zA-Z0-9]{0,62}(\.[a-zA-Z0-9][-a-zA-Z0-9]{0,62})+$/', $request->input('custom_domain'))) {
                return $this->error('域名格式不正确。');
            }

            if ($request->has('custom_domain')) {
                $custom_domain_search = Tunnel::where('server_id', $request->input('server_id'))->where('custom_domain', $request->input('custom_domain'))->where('protocol', $request->input('protocol'))->exists();
                if ($custom_domain_search) {
                    return $this->error('这个域名已经被使用了');
                }
            } else {
                return $this->error('必须提供域名。');
            }

            $data['custom_domain'] = Str::lower($request->input('custom_domain'));

            if (str_contains($request->input('custom_domain'), ',')) {
                return $this->error('一次请求只能添加一个域名。');
            }
        } elseif ($request->input('protocol') == 'tcp' || $request->input('protocol') == 'udp') {
            if ($request->filled('custom_domain')) {
                return $this->error('此协议不支持指定域名。');
            }

            $data['custom_domain'] = null;
            $request->validate([
                'remote_port' => "required|integer|max:$server->max_port|min:$server->min_port|bail",
            ]);

            if ($request->input('remote_port') == $server->server_port || $request->input('remote_port') == $server->dashboard_port) {
                return $this->error('无法使用这个远程端口。');
            }

            // 检查端口范围
            if ($request->input('remote_port') < $server->min_port || $request->input('remote_port') > $server->max_port) {
                return $this->error('远程端口号必须在 ' . $server->min_port . ' 和 ' . $server->max_port . ' 之间。');
            }

            $remote_port_search = Tunnel::where('server_id', $server->id)->where('remote_port', $request->input('remote_port'))->where('protocol', strtolower($request->protocol))->exists();
            if ($remote_port_search) {
                return $this->error('这个远程端口已经被使用了。');
            }
        } elseif ($request->input('protocol') === 'stcp' || $request->input('protocol') === 'xtcp') {
            $data['custom_domain'] = null;
            $data['remote_port'] = null;

            $request->validate(['sk' => 'required|alpha_dash|min:3|max:15']);

            $data['sk'] = $request->input('sk');
        } else {
            return $this->error('不支持的协议。');
        }

        $data['protocol'] = Str::lower($data['protocol']);

        $test_protocol = 'allow_' . $data['protocol'];

        if (!$server->$test_protocol) {
            return $this->error('服务器不允许这个协议。');
        }

        $tunnel = auth('sanctum')->user()->tunnels()->create($data);


        // 增加服务器的 tunnel 数量
        $server->increment('tunnels');

        return $this->created($tunnel);
    }

    /**
     * Display the specified resource.
     */
    public function show(TunnelRequest $tunnelRequest, Tunnel $tunnel)
    {
        unset($tunnelRequest);

        $tunnel->load('server');

        $tunnel['config'] = $tunnel->getConfig();

        $frp = new Frp($tunnel->server);
        $traffic = $frp->traffic($tunnel->client_token) ?? [];

        if (!$traffic) {
            $traffic = [];
        }


        $tunnel['traffic'] = $traffic;

        return $this->success($tunnel);
    }

    public function close(TunnelRequest $tunnelRequest, Tunnel $tunnel) {
        unset($tunnelRequest);
        $tunnel->close();
        return $this->noContent();
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Tunnel $tunnel)
    {
        $request->validate([
            'name' => 'sometimes|required|string|max:20',
            'local_address' => 'sometimes|required|string|max:255',
            'custom_domain' => 'sometimes|required|string|max:255',
        ]);

        $tunnel = $tunnel->update($request->only(['name', 'local_address', 'custom_domain']));

        return $this->updated($tunnel);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(TunnelRequest $request, Tunnel $tunnel)
    {
        unset($request);

        $tunnel->delete();

        return $this->deleted();
    }
}
