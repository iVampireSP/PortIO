<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Server extends Model
{
    protected $hidden = [
        'dashboard_password',
        'dashboard_user',
        'dashboard_port',
        'key'
    ];

    protected $fillable = [
        'name',
        'key',
        'server_address',
        'server_port',
        'token',
        'dashboard_port',
        'dashboard_user',
        'dashboard_password',
        'allow_http',
        'allow_https',
        'allow_tcp',
        'allow_udp',
        'allow_stcp',
        'allow_sudp',
        'allow_xtcp',
        'min_port',
        'max_port',
        'max_tunnels',
        'is_china_mainland',
        'status'
    ];

    // tunnels

    protected static function booted()
    {
        static::creating(function ($server) {
            // $server->key = Str::random(32);
        });
    }

    // on create

    public function tunnels(): HasMany
    {
        return $this->hasMany(Tunnel::class);
    }
}
