<?php

namespace App\Models;

use App\Support\Frp;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Cache;
use Illuminate\Database\Eloquent\Model;
use App\Http\Controllers\Admin\ServerController;
use App\Http\Controllers\Admin\TunnelController;
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
        'locked_reason',
        'run_id'
    ];

    protected $with = [
        'server',
    ];

    public function server(): BelongsTo
    {
        return $this->belongsTo(Server::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function getConfig()
    {
        return (new TunnelController)->generateConfig($this);
    }

    public function close()
    {
        if ($this->run_id) {
            $frp = new Frp($this->server);
            $closed = $frp->close($this->run_id);

            if ($closed) {
                $cache_key = 'frpTunnel_data_' . $this->client_token;
                Cache::forget($cache_key);

                $this->run_id = null;
                $this->saveQuietly();
            }

            return true;
        }

        return false;
    }


    protected static function boot()
    {
        parent::boot();

        static::creating(function (self $tunnel) {
            $tunnel->client_token = Str::random(18);
        });

        static::updated(function (self $tunnel) {
            if ($tunnel->locked_reason) {
                $tunnel->close();
            }
        });

        static::deleted(function (self $tunnel) {
            $tunnel->close();
        });
    }
}
