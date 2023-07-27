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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('username');
            $table->string('password');
            $table->string('email')->nullable();
            $table->string('mobile')->nullable();
            $table->string('multiuser');
            $table->string('start_date')->nullable();
            $table->string('end_date')->nullable();
            $table->string('date_one_connect')->nullable();
            $table->string('customer_user');
            $table->string('status');
            $table->string('traffic');
            $table->string('referral')->nullable();
            $table->longText('desc')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
