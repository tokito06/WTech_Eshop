<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('name');
            $table->string('description', 2000);
            $table->unsignedBigInteger('brand_id');
            $table->unsignedBigInteger('category_id');
            $table->enum('sex', ['men', 'women', 'kids', 'unisex'])->default('unisex');
            $table->enum('status', ['active', 'archived'])->default('active');
            $table->timestamp('created_at')->useCurrent();

            $table->foreign('brand_id')->references('id')->on('brands')->restrictOnDelete();
            $table->foreign('category_id')->references('id')->on('categories')->restrictOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
