<?php

namespace App\Jobs;

use App\Http\Controllers\Admin\ServerController;
use App\Models\Server;
use App\Support\Frp;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Cache;

class ServerCheckJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $id;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($id)
    {
        $this->id = $id;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $frpServer = Server::find($this->id);
        if (!is_null($frpServer)) {
            // $frp = new FrpController($this->id);
            $s = new ServerController();
            $s->scanTunnel($frpServer->id);
            $frpController = new Frp($frpServer);
            $meta = $frpController->serverInfo();

            if (!$meta) {
                $meta = [
                    'status' => 'failed',
                ];
                echo '服务器不可用: ' . $frpServer->name . ' failed' . PHP_EOL;
            } else {
                echo 'ServerCheckJob: ' . $frpServer->name . PHP_EOL;
            }

            $data = $frpServer->toArray();
            $data['meta'] = $meta;

            Cache::put('serverinfo_' . $frpServer->id, $data, 300);
        }
    }
}
