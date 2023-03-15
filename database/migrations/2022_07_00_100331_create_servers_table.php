<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('servers', function (Blueprint $table) {
            $table->id();

            $table->string('name')->index();

            $table->string('server_address')->index();
            $table->string('server_port')->index();
            $table->unsignedSmallInteger('dashboard_port');
            $table->string('dashboard_user')->nullable();
            $table->string('dashboard_password')->nullable();

            $table->string('token')->nullable()->index();

            $table->boolean('allow_http')->index()->default(true);
            $table->boolean('allow_https')->index()->default(true);
            $table->boolean('allow_tcp')->index()->default(true);
            $table->boolean('allow_udp')->index()->default(true);
            $table->boolean('allow_stcp')->index()->default(true);
            $table->boolean('allow_sudp')->index()->default(true);
            $table->boolean('allow_xtcp')->index()->default(true);

            // bandwidth_limit
            $table->unsignedBigInteger('bandwidth_limit')->default(0)->index();

            $table->unsignedSmallInteger('min_port')->default(10000)->index();
            $table->unsignedSmallInteger('max_port')->default(60000)->index();

            $table->unsignedInteger('max_tunnels')->default(100)->index();
            $table->unsignedInteger('tunnels')->default(0)->index();

            $table->string('status')->default('maintenance');

            // is_china_mainland
            $table->boolean('is_china_mainland')->default(false)->index();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('servers');
    }
};
