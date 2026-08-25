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
        if (Schema::hasTable('zapatos') && !Schema::hasColumn('zapatos', 'bordado')) {
            Schema::table('zapatos', function (Blueprint $table) {
                $table->string('bordado')->nullable()->after('material');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('zapatos') && Schema::hasColumn('zapatos', 'bordado')) {
            Schema::table('zapatos', function (Blueprint $table) {
                $table->dropColumn('bordado');
            });
        }
    }
};
