<x-app-layout>

    {{-- <nav>
        <div class="nav nav-tabs" id="nav-tab" role="tablist">
            <button class="nav-link active" aria-selected="true" data-bs-toggle="tab" data-bs-target="#nav-info"
                type="button" role="tab">基础信息</button>

            <button class="nav-link" data-bs-toggle="tab" data-bs-target="#nav-settings" type="button"
                role="tab">服务器设置</button>

            <button class="nav-link" data-bs-toggle="tab" data-bs-target="#nav-frps" type="button" role="tab">Frps
                配置文件</button>
        </div>
    </nav> --}}

    {{--    <div class="tab-content" id="nav-tabContent">--}}
    {{--        <div class="tab-pane fade" id="nav-settings" role="tabpanel">--}}
    <form action="{{ route('admin.servers.update', $server->id) }}" method="post">
        @csrf
        @method('PUT')
        <div class="row">
            <div class="col">
                <h4>服务器</h4>

                <div class="mb-3">
                    <label for="serverName" class="form-label">服务器名称</label>
                    <input type="text" required class="form-control" id="serverName" placeholder="输入服务器名称，它将会被搜索到"
                           name="name" value="{{ $server->name }}">
                </div>

                <div class="mb-3">
                    <label for="serverKey" class="form-label">鉴权 Key</label>
                    <input type="text" required value="{{ $server->key }}" class="form-control"
                           id="serverKey" name="key">
                </div>

                <h4>Frps 信息</h4>
                <div class="mb-3">
                    <label for="serverAddr" class="form-label">Frps 地址</label>
                    <input type="text" required class="form-control" id="serverAddr" name="server_address"
                           value="{{ $server->server_address }}">
                </div>

                <div class="mb-3">
                    <label for="serverPort" class="form-label">Frps 端口</label>
                    <input type="text" required class="form-control" id="serverPort" name="server_port"
                           value="{{ $server->server_port }}">
                </div>

                <div class="mb-3">
                    <label for="serverToken" class="form-label">Frps 令牌</label>
                    <input type="text" required class="form-control" id="serverToken" name="token"
                           value="{{ $server->token }}">
                </div>
            </div>

            <div class="col">

                <h4>Frps Dashboard 配置</h4>

                <div class="mb-3">
                    <label for="dashboardPort" class="form-label">端口</label>
                    <input type="text" required class="form-control" id="dashboardPort" name="dashboard_port"
                           value="{{ $server->dashboard_port }}">
                </div>

                <div class="mb-3">
                    <label for="dashboardUser" class="form-label">登录用户名</label>
                    <input type="text" required class="form-control" id="dashboardUser" name="dashboard_user"
                           value="{{ $server->dashboard_user }}">
                </div>

                <div class="mb-3">
                    <label for="dashboardPwd" class="form-label">密码</label>
                    <input type="text" required class="form-control" id="dashboardPwd" name="dashboard_password"
                           value="{{ $server->dashboard_password }}">
                </div>

                <h4>端口范围限制</h4>
                <div class="input-group input-group-sm mb-3">
                    <input type="text" required class="form-control" placeholder="最小端口, 比如: 10000" name="min_port"
                           value="{{ $server->min_port }}">
                    <input type="text" required class="form-control" placeholder="最大端口, 比如: 65535" name="max_port"
                           value="{{ $server->max_port }}">
                </div>

                <h4>最多隧道数量</h4>
                <div class="input-group input-group-sm mb-3">
                    <input type="text" required class="form-control" placeholder="最多隧道数量, 比如: 1024个隧道"
                           name="max_tunnels" value="{{ $server->max_tunnels }}">
                </div>
            </div>

            <div class="col">
                <h4>隧道协议限制</h4>
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="allow_http" value="1"
                           @if ($server->allow_http) checked @endif id="allow_http">
                    <label class="form-check-label" for="allow_http">
                        允许 HTTP
                    </label>
                </div>

                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="allow_https" value="1"
                           @if ($server->allow_https) checked @endif id="allow_https">
                    <label class="form-check-label" for="allow_https">
                        允许 HTTPS
                    </label>
                </div>

                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="allow_tcp" value="1"
                           @if ($server->allow_tcp) checked @endif id="allow_tcp">
                    <label class="form-check-label" for="allow_tcp">
                        允许 TCP
                    </label>
                </div>

                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="allow_udp" value="1"
                           @if ($server->allow_udp) checked @endif id="allow_udp">
                    <label class="form-check-label" for="allow_udp">
                        允许 UDP
                    </label>
                </div>

                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="allow_stcp" value="1"
                           @if ($server->allow_stcp) checked @endif
                           id="allow_stcp">
                    <label class="form-check-label" for="allow_stcp">
                        允许 STCP
                    </label>
                </div>

                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="allow_xtcp" value="1"
                           @if ($server->allow_xtcp) checked @endif
                           id="allow_xtcp">
                    <label class="form-check-label" for="allow_xtcp">
                        允许 XTCP
                    </label>
                </div>

                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="allow_sudp" value="1"
                           id="allow_sudp" @if ($server->allow_sudp) checked @endif>
                    <label class="form-check-label" for="allow_sudp">
                        允许 SUDP
                    </label>
                </div>

                <div class="form-check mt-3">
                    <input class="form-check-input" type="checkbox" name="is_china_mainland" value="1"
                           id="is_china_mainland">
                    <label class="form-check-label" for="is_china_mainland">
                        服务器是否位于中国大陆
                    </label>
                </div>

                <div class="d-grid mt-3">
                    <button type="submit" class="btn btn-primary mb-3">保存更改</button>
                </div>
            </div>
        </div>
    </form>

    <form action="{{ route('admin.servers.destroy', $server->id) }}" method="post">
        @csrf
        @method('DELETE')
        <button type="submit"
                class="btn btn-danger mb-3"
                onclick="confirm('确定删除这个服务器吗？删除后将无法恢复，与此关联的隧道将一并删除。') ? true : event.preventDefault()"
        >
            删除服务器
        </button>
    </form>

    <table class="mt-3 table table-hover text-center table-bordered">
        <tbody>
        <tr>
            <td>Frps 版本</td>
            <td>{{ $serverInfo->version ?? 0 }}</td>
        </tr>
        <tr>
            <td>绑定端口</td>
            <td>{{ $serverInfo->bind_port ?? 0 }}</td>
        </tr>
        @if ($serverInfo->bind_udp_port ?? 0)
            <tr>
                <td>UDP 端口</td>
                <td>{{ $serverInfo->bind_udp_port ?? 0 }}</td>
            </tr>
        @endif

        <tr>
            <td>HTTP 端口</td>
            <td>{{ $serverInfo->vhost_http_port ?? 0 }}</td>
        </tr>

        <tr>
            <td>HTTPS 端口</td>
            <td>{{ $serverInfo->vhost_https_port ?? 0 }}</td>
        </tr>

        <tr>
            <td>KCP 端口</td>
            <td>{{ $serverInfo->kcp_bind_port ?? 0 }}</td>
        </tr>

        @if (!empty($serverInfo->subdomain_host))
            <tr>
                <td>子域名</td>
                <td>{{ $serverInfo->subdomain_host ?? 0 }}</td>
            </tr>
        @endif

        <tr>
            <td>Max PoolCount</td>
            <td>{{ $serverInfo->max_pool_count ?? 0 }}</td>
        </tr>

        <tr>
            <td>Max Ports Peer Client</td>
            <td>{{ $serverInfo->max_ports_per_client ?? 0 }}</td>
        </tr>

        <tr>
            <td>Heartbeat timeout</td>
            <td>{{ $serverInfo->heart_beat_timeout ?? 0 }}</td>
        </tr>

        <tr>
            <td>自启动以来总入流量</td>
            <td>{{ unitConversion($serverInfo->total_traffic_in ?? 0) }}</td>
        </tr>

        <tr>
            <td>自启动以来总出流量</td>
            <td>{{ unitConversion($serverInfo->total_traffic_out ?? 0) }}</td>
        </tr>

        <tr>
            <td>客户端数量</td>
            <td>{{ $serverInfo->client_counts ?? 0 }}</td>
        </tr>

        <tr>
            <td>当前连接数量</td>
            <td>{{ $serverInfo->cur_conns ?? 0 }}</td>
        </tr>

        </tbody>
    </table>

    @if ($server->status == 'down')
        <h4 class="text-danger">无法连接到服务器</h4>
        <form action="{{ route('admin.servers.update', $server->id) }}" method="POST">
            @csrf
            @method('PUT')
            <input type="hidden" name="status" value="up"/>
            <button type="submit" class="btn btn-danger mb-2">强制标记为在线</button>
        </form>
    @else
        <span class="text-success">正常</span>

        <form action="{{ route('admin.servers.update', $server->id) }}" method="POST">
            @csrf
            @method('PUT')
            <input type="hidden" name="status" value="down"/>
            <button type="submit" class="btn btn-danger mb-2">标记为离线</button>
        </form>
    @endif


    @if ($server->status == 'maintenance')
        <span class="text-danger">维护中</span>

        <form action="{{ route('admin.servers.update', $server->id) }}" method="POST">
            @csrf
            @method('PUT')
            <input type="hidden" name="status" value="down"/>
            <button type="submit" class="btn btn-warning mb-2">取消维护</button>
        </form>
    @else
        <form action="{{ route('admin.servers.update', $server->id) }}" method="POST">
            @csrf
            @method('PUT')
            <input type="hidden" name="status" value="maintenance"/>
            <button type="submit" class="btn btn-warning mb-2">开始维护</button>
        </form>
    @endif
    <div class="alert alert-primary">
        将这些内容放入: frps.ini
    </div>
    <textarea readonly class="form-control mb-3" rows="20" cols="80">[common]
bind_port = {{ $server->server_port }}
bind_udp_port = {{ $server->server_port }}
        @if ($server->server_port + 1 > 65535)
            kcp_bind_port = {{ $server->server_port - 1 }}
        @else
            kcp_bind_port = {{ $server->server_port + 1 }}
        @endif
token = {{ $server->token }}
        @if ($server->allow_http)
            vhost_http_port = 80
        @endif
        @if ($server->allow_https)
            vhost_https_port = 443
        @endif
dashboard_port = {{ $server->dashboard_port }}
dashboard_user = {{ $server->dashboard_user }}
dashboard_pwd = {{ $server->dashboard_password }}

[plugin.port-manager]
addr = {{ route('api.tunnel.handler', '') }}/
path = {{ $server->key }}
ops = NewProxy

    </textarea>
</x-app-layout>
