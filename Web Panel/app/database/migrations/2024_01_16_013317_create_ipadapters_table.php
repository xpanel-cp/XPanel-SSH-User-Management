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

        if (!Schema::hasTable('ipadapters')) {
            Schema::create('ipadapters', function (Blueprint $table) {
                $table->id();
                $table->string('email_cf');
                $table->longText('token_cf');
                $table->string('sub_cf');
                $table->string('status_chanched');
                $table->string('status_active');
                $table->string('log_change_hourly');
                $table->string('log_change_traffic');
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ipadapters');
    }
};
