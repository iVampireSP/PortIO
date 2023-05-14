<?php

namespace App\Http\Controllers\Admin;

use App\Models\Tunnel;
use Illuminate\View\View;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;

class TunnelController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return RedirectResponse|View
     */
    public function index(Request $request)
    {
        $hosts = Tunnel::with('user');

        // if has has_free_traffic
        if ($request->has_free_traffic == 1) {
            $hosts = $hosts->where('free_traffic', '>', 0);
        }

        foreach ($request->except(['has_free_traffic', 'page']) as $key => $value) {
            if (empty($value)) {
                continue;
            }

            if ($request->{$key}) {
                $hosts = $hosts->where($key, 'LIKE', '%'.$value.'%');
            }
        }

        $count = $hosts->count();

        $hosts = $hosts->simplePaginate(100);

        return view('admin.tunnels.index', ['hosts' => $hosts, 'count' => $count]);
    }

    /**
     * Display the specified resource.
     *
     * @param  Tunnel  $tunnel
     * @return View
     */
    public function show(Tunnel $tunnel)
    {
        $tunnel->load('server');

        return view('admin.tunnels.show', compact('tunnel'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  Tunnel  $tunnel
     * @return RedirectResponse
     */
    public function update(Request $request, Tunnel $tunnel)
    {
        $request->validate([
            'locked_reason' => 'nullable|string'
        ]);

        $tunnel->update($request->all());

        return back()->with('success', '完成。');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Tunnel  $host
     * @return RedirectResponse
     */
    public function destroy(Tunnel $host)
    {
        $host->delete();

        return back()->with('success', '已开始销毁。');
    }

    public function generateConfig(Tunnel $tunnel)
    {
        $tunnel->load('server');

        // 配置文件
        $config = [];

        $config['server'] = <<<EOF
[common]
server_addr = {$tunnel->server->server_address}
server_port = {$tunnel->server->server_port}
token = {$tunnel->server->token}
EOF;

        $local_addr = explode(':', $tunnel->local_address);
        $config['client'] = <<<EOF
[{$tunnel->client_token}]
type = {$tunnel->protocol}
local_ip = {$local_addr[0]}
local_port = {$local_addr[1]}
EOF;

        if ($tunnel->protocol == 'tcp' || $tunnel->protocol == 'udp') {
            $config['client'] .= PHP_EOL . 'remote_port = ' . $tunnel->remote_port;
        } elseif ($tunnel->protocol == 'http' || $tunnel->protocol == 'https') {
            $config['client'] .= PHP_EOL . 'custom_domains = ' . $tunnel->custom_domain . PHP_EOL;
        } elseif ($tunnel->server->allow_stcp || $tunnel->server->allow_xtcp || $tunnel->server->allow_sudp) {
            $uuid = Str::uuid();
            $config['client'] .= <<<EOF

sk = {$tunnel->sk}

# 以下的是对端配置文件，请不要复制或者使用！
# 如果你想让别人通过 XTCP|STCP|SUDP 连接到你的主机，请将以下配置文件发给你信任的人。如果你不信任他人, 请勿发送, 这样会导致不信任的人也能通过 XTCP 连接到你的主机。
# XTCP 连接不能保证稳定性, 并且也不会100%成功。


#------ 对端复制开始 --------
[common]
server_addr = {$tunnel->server->server_address}
server_port = {$tunnel->server->server_port}
user = visitor_{$uuid}
token = {$tunnel->server->token}
[lae_visitor_{$uuid}]
type = xtcp
role = visitor
server_name = lae_visitor_{$uuid}
sk = {$tunnel->sk}
bind_addr = {$local_addr[0]}
bind_port = {$local_addr[1]}
#------ 对端复制结束 --------
# 非常感谢您的支持。如果您觉得这个项目不错，请将我们的网站分享给您的朋友。谢谢。
EOF;
        }

        return $config;
    }
}
