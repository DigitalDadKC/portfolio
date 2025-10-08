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
        Schema::create('contracts', function (Blueprint $table) {
            $table->id();
            $table->string('Name');
            $table->string('Contract_No')->nullable()->default(NULL);
            $table->date('Effective_Date')->nullable()->default(NULL);
            $table->date('Termination_Date')->nullable()->default(NULL);
            $table->decimal('Admin_Fee', 4, 4)->nullable()->default(NULL);
            $table->decimal('Discount', 3, 3)->nullable()->default(NULL);
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