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
        Schema::create('clients', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable()->default(NULL);
            $table->string('company');
            $table->string('email')->nullable()->default(NULL);
            $table->timestamps();
        });
        Schema::create('outreaches', function (Blueprint $table) {
            $table->id();
            $table->foreignId('client_id')->constrained();
            $table->date('date_emailed');
            $table->timestamps();
        });
        Schema::create('client_invoices', function (Blueprint $table) {
            $table->id();
            $table->string('number')->unique();
            $table->date('date_created');
            $table->date('due_date');
            $table->decimal('total_price', 6, 2);
            $table->text('terms_and_conditions');
            $table->string('paid');
            $table->string('session_id')->nullable()->default(NULL);
            $table->foreignId('client_id')->constrained();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('client_invoices');
        Schema::dropIfExists('outreaches');
        Schema::dropIfExists('clients');
    }
};
