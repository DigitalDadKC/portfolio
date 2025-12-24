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
        Schema::create('contracts', function (Blueprint $table) {
            $table->id();
            $table->integer('price');
            $table->date('date')->default(now());
            $table->string('file_path')->nullable()->default(NULL);
            $table->boolean('sent')->default(0);
            $table->string('signwell_id')->nullable()->default(NULL);
            $table->foreignId('client_id')->constrained()->nullable();
            $table->foreignId('employee_id')->constrained()->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contracts');
    }
};
