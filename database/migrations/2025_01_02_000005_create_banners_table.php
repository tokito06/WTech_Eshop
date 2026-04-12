<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('banners', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('name');
            $table->string('description', 255)->nullable();
            $table->unsignedInteger('sort_order')->default(0);
            $table->foreignUuid('image_id')->nullable()->constrained('images')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('banners');
    }
};
