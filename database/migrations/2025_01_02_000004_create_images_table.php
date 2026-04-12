<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('images', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('name');
            $table->string('path');
            $table->unsignedInteger('position')->default(0);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('images');
    }
};
