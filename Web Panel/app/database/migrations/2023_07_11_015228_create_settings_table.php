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
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->string('ssh_port')->nullable();
            $table->string('tls_port')->nullable();
            $table->string('t_token')->nullable();
            $table->string('t_id')->nullable();
            $table->string('language')->nullable();
            $table->string('multiuser')->nullable();
            $table->string('ststus_multiuser')->nullable();
            $table->string('home_url')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('settings');
    }
};
