<x-app-layout>
    <h3 class="mb-3">创建客户端</h3>
    <form action="{{ route('admin.clients.store') }}" method="post">
        @csrf
        <label for="name" class="form-label">名称</label>
        <input type="text" name="name" id="name" placeholder="名称" class="form-control mb-3" required>

        <label for="name" class="form-label">架构</label>
        <input type="text" name="arch" id="arch" placeholder="架构" class="form-control mb-3" required>

        <label for="name" class="form-label">下载链接</label>
        <input type="text" name="url" id="url" placeholder="下载链接" class="form-control mb-3" required>

        <label for="name" class="form-label">作者</label>
        <input type="text" name="author" id="author" placeholder="作者" class="form-control mb-3" required>

        <button class="btn btn-primary" type="submit">创建</button>
    </form>
</x-app-layout>
