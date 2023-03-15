<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tunnel extends Model
{
    protected $fillable = [
        'name',
        'protocol',
        'custom_domain',
        'local_address',
        'remote_port',
        'client_token',
        'sk',
        'status',
        'server_id',
        'user_id',
    ];

    public function server()
    {
        return $this->belongsTo(Server::class);

    }
}
