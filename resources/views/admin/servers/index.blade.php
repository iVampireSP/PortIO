<x-app-layout>

    <a href="{{ route('admin.servers.create') }}">添加 Frps 服务器</a>
    <div class="list-group mt-3">
        @foreach ($servers as $server)
            <x-Server-View :server="$server" :url="route('admin.servers.edit', $server->id)" />
        @endforeach
    </div>

</x-app-layout>
