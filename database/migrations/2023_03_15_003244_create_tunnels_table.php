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
        Schema::create('tunnels', function (Blueprint $table) {
            $table->id();

            $table->string('name')->index();

            $table->char('protocol', 5)->index()->default('tcp');

            $table->string('custom_domain')->nullable()->index();

            $table->string('local_address')->index();

            $table->unsignedSmallInteger('remote_port')->index()->nullable();

            $table->string('sk')->index()->nullable();

            $table->unsignedBigInteger('user_id')->index();
            $table->foreign('user_id')->references('id')->on('users')->cascadeOnDelete();

            $table->unsignedBigInteger('server_id')->index();
            $table->foreign('server_id')->references('id')->on('servers')->cascadeOnDelete();

            // use_encryption
            $table->boolean('use_encryption')->default(false)->index();

            // use_compression
            $table->boolean('use_compression')->default(false)->index();


            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tunnels');
    }
};
