<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('product_variants', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->float('price');
            $table->foreignUuid('product_id')->constrained('products')->cascadeOnDelete();
            $table->string('symbol', 4);
            $table->unsignedInteger('inventory')->default(0);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('product_variants');
    }
};
