<x-app-layout>
    @if (count($servers) > 0)
        <h1>不在线或维护中的服务器</h1>

        @foreach ($servers as $server)
            <x-Server-View :server="$server" :url="route('admin.servers.edit', $server->id)" />
        @endforeach
    @endif

</x-app-layout>
