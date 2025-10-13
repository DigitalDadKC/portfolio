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
        Schema::create('skills', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('image');
            $table->timestamps();
        });
        Schema::create('projects', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('description')->nullable()->default(NULL);
            $table->string('image')->nullable()->default(NULL);
            $table->string('url')->nullable();
            $table->integer('order');
            $table->boolean('active')->default(1);
            $table->timestamps();
        });
        Schema::create('project_skill', function (Blueprint $table) {
            $table->id();
            $table->foreignId('skill_id')->constrained();
            $table->foreignId('project_id')->constrained()->onDelete('cascade');
        });
        Schema::create('features', function(Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->integer('order');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('features');
        Schema::dropIfExists('project_skill');
        Schema::dropIfExists('projects');
        Schema::dropIfExists('skills');
    }
};
