<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasColumn('companies', 'floor_plan_path')) {
            Schema::table('companies', function (Blueprint $table) {
                $table->dropColumn('floor_plan_path');
            });
        }
    }

    public function down(): void
    {
        if (! Schema::hasColumn('companies', 'floor_plan_path')) {
            Schema::table('companies', function (Blueprint $table) {
                $table->string('floor_plan_path')->nullable()->after('layout_description');
            });
        }
    }
};
