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
        if (!Schema::hasTable('adapterlists')) {
            Schema::create('adapterlists', function (Blueprint $table) {
                $table->id();
                $table->string('ip');
                $table->string('status_active');
                $table->string('status_service');
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('adapterlists');
    }
};
