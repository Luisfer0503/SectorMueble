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
        if (!Schema::hasColumn('producto_detalles', 'material_imagen')) {
            Schema::table('producto_detalles', function (Blueprint $table) {
                $table->string('material_imagen')->nullable()->after('imagen');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasColumn('producto_detalles', 'material_imagen')) {
            Schema::table('producto_detalles', function (Blueprint $table) {
                $table->dropColumn('material_imagen');
            });
        }
    }
};
