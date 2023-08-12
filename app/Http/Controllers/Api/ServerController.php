<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Server;
use App\Support\Frp;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ServerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $servers = Server::all();
        $servers->toJson();

        foreach ($servers as $server) {
            try {
                $serverInfo = (object) (new Frp($server))->serverInfo();
                $server["traffic_in"] = $serverInfo->total_traffic_in;
                $server["traffic_out"] = $serverInfo->total_traffic_out;
                $server["connections"] = $serverInfo->cur_conns;
            } catch (Exception) {
            }
        }

        return $this->success($servers);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this->abortIfNotAdmin();

        $request->validate($this->rules());

        $server = (new Server)->create([
            'name' => $request->input('name'),
            'server_address' => $request->input('server_address'),
            'server_port' => $request->input('server_port'),
            'token' => $request->input('token'),
            'dashboard_port' => $request->input('dashboard_port'),
            'dashboard_user' => $request->input('dashboard_user'),
            'dashboard_password' => $request->input('dashboard_password'),
            'allow_http' => $request->boolean('allow_http'),
            'allow_https' => $request->boolean('allow_https'),
            'allow_tcp' => $request->boolean('allow_tcp'),
            'allow_udp' => $request->boolean('allow_udp'),
            'allow_stcp' => $request->boolean('allow_stcp'),
            'allow_sudp' => $request->boolean('allow_sudp'),
            'allow_xtcp' => $request->boolean('allow_xtcp'),
            'min_port' => $request->input('min_port'),
            'max_port' => $request->input('max_port'),
            'max_tunnels' => $request->input('max_tunnels'),
            'is_china_mainland' => $request->boolean('is_china_mainland'),
        ]);

        return $this->created($server);
    }

    public function abortIfNotAdmin()
    {
        if (!auth('sanctum')->user()?->isAdmin()) {
            abort(403, 'You are not allowed to access this resource.');
        }
    }

    public function rules($id = null)
    {
        return [
            'name' => 'sometimes|max:20',
            'server_address' => [
                'sometimes',
                Rule::unique('servers')->ignore($id),
            ],
            'server_port' => 'sometimes|integer|max:65535|min:1',
            'token' => 'sometimes|max:50',
            'dashboard_port' => 'sometimes|integer|max:65535|min:1',
            'dashboard_user' => 'sometimes|max:20',
            'dashboard_password' => 'sometimes|max:30',
            'allow_http' => 'nullable|boolean',
            'allow_https' => 'nullable|boolean',
            'allow_tcp' => 'nullable|boolean',
            'allow_udp' => 'nullable|boolean',
            'allow_stcp' => 'nullable|boolean',
            'allow_sudp' => 'nullable|boolean',
            'allow_xtcp' => 'nullable|boolean',
            'min_port' => 'sometimes|integer|max:65535|min:1',
            'max_port' => 'sometimes|integer|max:65535|min:1',
            'max_tunnels' => 'sometimes|integer|max:65535|min:1',
        ];
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, Server $server)
    {
        if ($request->user('sanctum')->isAdmin()) {
            $server->makeVisible($server->hidden);
        }

        return $this->success($server);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Server $server)
    {
        $this->abortIfNotAdmin();
        $request->validate($this->rules($server->id));

        $server->update($request->all());

        return $this->updated();

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Server $server)
    {
        $this->abortIfNotAdmin();

        $server->delete();
    }
}
