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
        Schema::create('csi_divisions', function (Blueprint $table) {
            $table->id();
            $table->string('code');
            $table->string('name');
        });
        Schema::create('csi_sections', function (Blueprint $table) {
            $table->id();
            $table->string('code');
            $table->string('name');
            $table->foreignId('csi_division_id')->constrained();
        });
        Schema::create('csi_subsections', function (Blueprint $table) {
            $table->id();
            $table->string('code');
            $table->string('name');
            $table->foreignId('csi_section_id')->constrained();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('csi_subsections');
        Schema::dropIfExists('csi_sections');
        Schema::dropIfExists('csi_divisions');
    }
};
