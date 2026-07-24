<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pricing_multipliers', function (Blueprint $table) {
            $table->id();
            $table->enum('type', ['scale', 'complexity', 'urgency']);
            $table->string('label');
            $table->string('description')->nullable();
            $table->decimal('value', 4, 2); // mis: 1.00, 1.30, 1.80
            $table->boolean('is_active')->default(true);
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pricing_multipliers');
    }
};
