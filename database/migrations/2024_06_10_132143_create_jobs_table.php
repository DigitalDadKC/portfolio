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
        Schema::create('states', function (Blueprint $table) {
            $table->id();
            $table->string('abbr');
            $table->string('state');
        });
        Schema::create('companies', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('logo');
            $table->string('address');
            $table->string('city');
            $table->foreignId('state_id')->constrained();
            $table->integer('zip_code');
            $table->text('terms')->nullable()->default(NULL);
            $table->timestamps();
        });
        Schema::create('jobs', function (Blueprint $table) {
            $table->id();
            $table->integer('number')->unique();
            $table->string('address');
            $table->string('city');
            $table->foreignId('state_id')->constrained();
            $table->integer('zip');
            $table->timestamp('start_date')->nullable()->default(NULL);
            $table->string('notes')->nullable()->default(NULL);
            $table->timestamps();
        });
        Schema::create('proposals', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable()->default(NULL);
            $table->integer('contingency')->nullable()->default(NULL);
            $table->foreignId('job_id')->constrained()->onDelete('cascade');
            $table->enum('type', ['base', 'alternate', 'change order'])->constrained();
            $table->text('exclusions')->nullable()->default(NULL);
            $table->timestamps();
        });
        Schema::create('scopes', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable()->default(NULL);
            $table->float('area')->nullable()->default(NULL);
            $table->foreignId('proposal_id')->constrained()->onDelete('cascade');
            $table->timestamps();
        });

        Schema::create('unit_of_measurements', function (Blueprint $table) {
            $table->id();
            $table->string('UOM');
        });

        Schema::create('lines', function (Blueprint $table) {
            $table->id();
            $table->string('description')->nullable()->default(NULL);
            $table->foreignId('unit_of_measurement_id')->constrained();
            $table->decimal('days')->nullable()->default(NULL);
            $table->integer('price')->nullable()->default(NULL);
            $table->integer('quantity')->nullable()->default(NULL);
            $table->integer('order');
            $table->foreignId('scope_id')->constrained()->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lines');
        Schema::dropIfExists('unit_of_measurements');
        Schema::dropIfExists('scopes');
        Schema::dropIfExists('proposals');
        Schema::dropIfExists('jobs');
        Schema::dropIfExists('companies');
        Schema::dropIfExists('states');
    }
};
