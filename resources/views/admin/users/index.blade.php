<x-app-layout>
    <h3>已经发现的客户</h3>

    <div class="alert alert-primary mt-3" role="alert">
        总计: {{ $count }}
    </div>


    <form name="filter">
        <div class="input-group">
            <input type="text" name="id" value="{{ Request::get('id') }}" class="form-control" placeholder="用户 ID"/>
            <input type="text" name="name" value="{{ Request::get('name') }}" class="form-control" placeholder="名称"/>
            <input type="email" name="email" value="{{ Request::get('email') }}" class="form-control"
                   placeholder="邮箱"/>
            <button type="submit" class="btn btn-primary">筛选</button>
        </div>
    </form>


    <table class="mt-3 table table-hover text-center table-bordered" style="vertical-align: middle">
        {{-- 表头 --}}
        <thead>
        <tr>
            <th>ID</th>
            <th>名称</th>
            <th>邮箱</th>
            <th>剩余流量</th>
            {{-- <th>发现时间</th>
            <th>更新时间</th> --}}
            {{-- <th>操作</th> --}}
        </tr>
        </thead>

        {{-- 表内容 --}}
        <tbody>
        @foreach ($users as $user)
            <tr>
                <td>{{ $user->id }}</td>
                <td>{{ $user->name }}</td>
                <td>{{ $user->email }}</td>
                <td>
                    <div class="input-group">
                        <input type="text"
                               value="{{ $user->traffic ?? 0 }}"
                               onchange="updateTraffic({{ $user->id }}, this)"
                               class="form-control"/>
                        <span class="input-group-text">GB</span>
                    </div>
                </td>
                {{-- <td>{{ $user->created_at }}</td>
                <td>{{ $user->updated_at }}</td> --}}
                {{-- <td>
                    <a href="{{ route('user.show', $user) }}">查看</a>
                    <a href="{{ route('user.edit', $user) }}">编辑</a>
                    <form action="{{ route('user.destroy', $user) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit">删除</button>
                    </form>
                </td> --}}
            </tr>
        @endforeach
        </tbody>
    </table>


    {{ $users->links() }}

    <script>
        function updateTraffic(userId, input) {
            const url = '/admin/users/' + userId
            // xml http request
            const xhr = new XMLHttpRequest();
            xhr.open('PATCH', url);
            xhr.setRequestHeader('Content-Type', 'application/json');

            // csrf
            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            xhr.setRequestHeader('X-CSRF-TOKEN', csrfToken);

            // not follow redirect
            xhr.responseType = 'json';

            // add ajax header
            xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');


            xhr.send(JSON.stringify({
                traffic: input.value
            }));

            xhr.onload = function () {
                if (xhr.status != 200) {
                    alert(`Error ${xhr.status}: ${xhr.statusText}`);
                }
            };
        }
    </script>
</x-app-layout>
