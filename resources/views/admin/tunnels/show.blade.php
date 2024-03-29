<x-app-layout>

    @php($cache = Cache::get('frpTunnel_data_' . $tunnel->client_token, []))

    <p>隧道状态:&nbsp;
        @if ($cache['status'] ?? false === 'online')
            <span class="text-success">在线</span>
        @else
            <span class="text-danger">离线</span>
        @endif
    </p>

    <p>连接数: {{ $cache['cur_conns'] ?? 0 }}</p>
    <p>下载流量: {{ unitConversion($cache['today_traffic_in'] ?? 0) }}</p>
    <p>上载流量: {{ unitConversion($cache['today_traffic_out'] ?? 0) }}</p>

    <hr/>
    <p>如果填写锁定原因，隧道将会立即下线，并且客户端无法登录。</p>
    <form action="{{ route('admin.tunnels.update', $tunnel) }}" method="POST">
        @csrf
        @method('PATCH')
        <div class="input-group">
            <input type="text" name="locked_reason" @if($tunnel->locked_reason) value="{{$tunnel->locked_reason}}"
                   @endif placeholder="留空解除" class="form-control"/>
            <button type="submit" class="btn btn-primary">确定</button>
        </div>
    </form>


    {{-- @if ($host->protocol == 'http' || $host->protocol == 'https')
        <h3>网页截图</h3>
        <img src="" />
    @endif --}}

    <h4 class="mt-3 mb-3">配置文件</h4>
    <textarea id="config" cols="80" rows="20" readonly class="form-control"></textarea>
    <script>
        let tunnel_config = {!! $tunnel !!}
        // let put_config()
        function put_config() {
            let local_addr = tunnel_config.local_address.split(':')
            let config = `[common]
server_addr = ${tunnel_config.server.server_address}
server_port = ${tunnel_config.server.server_port}
token = ${tunnel_config.server.token}

# ${tunnel_config.name} 于服务器 ${tunnel_config.server.name}
[${tunnel_config.client_token}]
type = ${tunnel_config.protocol}
local_ip = ${local_addr[0]}
local_port = ${local_addr[1]}
`;

            if (tunnel_config.protocol == 'tcp' || tunnel_config.protocol == 'udp') {
                config += `remote_port = ${tunnel_config.remote_port}

`;
            } else if (tunnel_config.protocol == 'http' || tunnel_config.protocol == 'https') {
                config += `custom_domains = ${tunnel_config.custom_domain}
`;
            } else if (tunnel_config.protocol == 'stcp') {
                let random = Math.floor(Math.random() * 50);
                config += `sk = ${tunnel_config.sk}

#------ Visitor config file --------
[common]
server_addr = ${tunnel_config.server.server_address}
server_port = ${tunnel_config.server.server_port}
user = client
token = ${tunnel_config.server.token}

[client_visitor_${random}]
type = stcp
role = visitor
server_name = ${tunnel_config.client_token}
sk = ${tunnel_config.sk}
bind_addr = 127.0.0.1
bind_port = ${local_addr[1]}

#------ Visitor config file --------
`
            }
            document.getElementById('config').value = config;
        };

        put_config();
    </script>

</x-app-layout>
