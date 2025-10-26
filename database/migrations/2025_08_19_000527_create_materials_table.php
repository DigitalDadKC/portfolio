<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('material_unit_sizes', function (Blueprint $table) {
            $table->id();
            $table->string('unit_size');
        });

        Schema::create('material_categories', function (Blueprint $table) {
            $table->id();
            $table->string('name');
        });

        Schema::create('materials', function (Blueprint $table) {
            $table->id();
            $table->string('sku');
            $table->string('name');
            $table->integer('price');
            $table->boolean('discountable');
            $table->foreignId('material_category_id')->constrained();
            $table->foreignId('material_unit_size_id')->constrained();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('materials');
        Schema::dropIfExists('material_categories');
        Schema::dropIfExists('material_unit_sizes');
    }
};
