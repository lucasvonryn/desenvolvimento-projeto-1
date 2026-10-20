<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('measurements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained()->cascadeOnDelete();
            $table->foreignId('room_id')->constrained()->cascadeOnDelete();
            $table->date('measured_on');
            $table->string('time_slot', 8);
            $table->decimal('speed_24_mbps', 8, 2);
            $table->decimal('speed_5_mbps', 8, 2);
            $table->timestamps();

            $table->unique(['room_id', 'measured_on', 'time_slot']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('measurements');
    }
};
