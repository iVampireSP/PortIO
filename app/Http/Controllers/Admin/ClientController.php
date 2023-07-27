<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Models\Client;
use Illuminate\View\View;

class ClientController extends Controller
{
    /**
     * Display a listing of the clients.
     *
     * @return View
     */
    public function index(): View
    {
        $clients = Client::all();
        $count = $clients->count();
        return view('admin.clients.index', ['clients' => $clients, 'count' => $count]);
    }

    public function create(Request $request): View
    {
        return view('admin.clients.create');
    }

    public function edit(Client $client): View
    {
        return view('admin.clients.edit', ['client' => $client]);
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => 'required|max:80',
            'arch' => 'required|max:30',
            'url' => 'required',
            'author' => 'required|max:30',
        ]);
        $request_data = $request->toArray();
        Client::create($request_data);
        return redirect()->route('admin.clients.index')->with('success', '创建成功');
    }

    public function update(Request $request, Client $client)
    {
        $request->validate([
            'name' => 'required|max:80',
            'arch' => 'required|max:30',
            'url' => 'required',
            'author' => 'required|max:30',
        ]);

        $data = $request->all();

        $client->update($data);

        return redirect()->route('admin.clients.index')->with('success', '客户端更新成功');
    }

    public function destroy(Client $client)
    {
        $client->delete();

        return redirect()->route('admin.clients.index')->with('success', '客户端删除成功');
    }
}
