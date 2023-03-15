<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('active_tunnels', function (Blueprint $table) {
            $table->id();

            // 协议
            $table->char('protocol', 5)->index()->default('tcp');

            // 流量(全部 MB)
            $table->unsignedBigInteger('traffic')->default(0)->index();

            // 隧道名称
            $table->string('name')->index();

            // 记录正在运行的隧道
            $table->unsignedBigInteger('tunnel_id')->index()->nullable();
            $table->foreign('tunnel_id')->references('id')->on('tunnels')->cascadeOnDelete();

            // 用户 ID
            $table->unsignedBigInteger('user_id')->index()->nullable();
            $table->foreign('user_id')->references('id')->on('users')->cascadeOnDelete();

            // run id
            $table->string('run_id')->index()->nullable();

            // 上次心跳
            $table->timestamp('last_heartbeat_at')->nullable()->index();


            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('active_tunnels');
    }
};
