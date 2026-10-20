<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('equipment', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->unique()->constrained()->cascadeOnDelete();
            $table->string('brand');
            $table->string('model');
            $table->text('summary')->nullable();
            $table->text('technical_specs')->nullable();
            $table->text('wifi_specs')->nullable();
            $table->text('security_specs')->nullable();
            $table->unsignedInteger('max_wifi_devices')->nullable();
            $table->unsignedInteger('recommended_devices')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('equipment');
    }
};
