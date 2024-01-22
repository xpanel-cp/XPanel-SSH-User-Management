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
        if (!Schema::hasTable('log_connections')) {
        Schema::create('log_connections', function (Blueprint $table) {
            $table->id();
            $table->string('username');
            $table->string('connection');
            $table->string('datecon')->nullable();
            $table->timestamps();
        });
            }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('log_connections');
    }
};
