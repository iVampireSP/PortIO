<hr/>
<div>
    <a href="{{ $url }}" class="list-group-item list-group-item-action">
        {{-- <div class="d-flex w-100 justify-content-between">
        <h5 class="mb-1 text-success">{{ $server->name }}</h5>
        <small class="text-muted">{{ $server->updated_at->diffForHumans() }}</small>
    </div> --}}
        {{-- <p class="mb-1"></p> --}}
        <h4>{{ $server->name }}</h4>
        {{ $server->updated_at->diffForHumans() }}
    </a>

    <p>
        @if ($server->status == "down")
            <span class="text-danger">服务器状态 down</span>
        @else
            <span class="text-success">服务器状态 up</span>
        @endif
    </p>

    <small class="text-muted">
        服务器地址: {{ $server->server_address }}, 支持的协议:
        {{ $server->allow_http ? 'HTTP' : ' ' }}
        {{ $server->allow_https ? 'HTTPS' : ' ' }}
        {{ $server->allow_tcp ? 'TCP' : ' ' }}
        {{ $server->allow_udp ? 'UDP' : ' ' }}
        {{ $server->allow_STCP ? 'STCP' : ' ' }}。
        服务器位于
        @if ($server->is_china_mainland)
            <span class="text-success">中国大陆</span>
        @else
            <span class="text-danger">境外</span>
        @endif
    </small>
</div>
<hr/>
