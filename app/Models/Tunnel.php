<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

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

    protected $with = [
        'server',
    ];

    public function server(): BelongsTo
    {
        return $this->belongsTo(Server::class);

    }
}
