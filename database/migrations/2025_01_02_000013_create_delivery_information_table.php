<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('delivery_information', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('street');
            $table->string('post_code', 50);
            $table->string('city', 100);
            $table->string('province', 100)->nullable();
            $table->string('country', 100);
            $table->string('house', 50)->nullable();
            $table->string('first_name', 50);
            $table->string('last_name', 50);
            $table->string('phone_number', 20);
            $table->string('session_id')->nullable();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('delivery_information');
    }
};
