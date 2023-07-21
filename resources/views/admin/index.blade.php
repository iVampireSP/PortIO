<x-app-layout>
    @if (count($servers) > 0)
        <h3>不在线或维护中的服务器</h3>

        @foreach ($servers as $server)
            <x-Server-View :server="$server" :url="route('admin.servers.edit', $server->id)"/>
        @endforeach
    @else
        <div class="text-center">
            <svg xmlns="http://www.w3.org/2000/svg" width="64" height="64" fill="currentColor"
                 class="bi bi-check-circle" viewBox="0 0 16 16">
                <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z"/>
                <path
                    d="M10.97 4.97a.235.235 0 0 0-.02.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-1.071-1.05z"/>
            </svg>
            <h4 class="mt-3">服务器一切正常</h4>
        </div>
    @endif

</x-app-layout>
