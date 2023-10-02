<x-app-layout>
    <h3 class="mb-3">创建流量激活码</h3>
    <form action="{{ route('admin.codes.store') }}" method="post">
        @csrf
        <label for="name" class="form-label">数量</label>
        <input type="number" name="amount" id="amount" placeholder="数量" class="form-control mb-3" required>

        <label for="name" class="form-label">流量</label>
        <input type="number" name="traffic" id="traffic" placeholder="流量 (GB)" class="form-control mb-3" required>

        <button class="btn btn-primary" type="submit">创建</button>
    </form>
</x-app-layout>
