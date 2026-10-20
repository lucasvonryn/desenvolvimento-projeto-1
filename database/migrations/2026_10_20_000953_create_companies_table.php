<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('companies', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('responsible_name')->nullable();
            $table->string('responsible_phone')->nullable();
            $table->text('address')->nullable();
            $table->text('layout_description')->nullable();
            $table->unsignedInteger('notebooks_count')->default(0);
            $table->unsignedInteger('smartphones_count')->default(0);
            $table->unsignedInteger('cameras_count')->default(0);
            $table->unsignedInteger('other_devices_count')->default(0);
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('companies');
    }
};
