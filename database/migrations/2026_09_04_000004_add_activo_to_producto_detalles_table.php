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
        if (!Schema::hasColumn('producto_detalles', 'activo')) {
            Schema::table('producto_detalles', function (Blueprint $table) {
                $table->boolean('activo')->default(true)->after('stock');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasColumn('producto_detalles', 'activo')) {
            Schema::table('producto_detalles', function (Blueprint $table) {
                $table->dropColumn('activo');
            });
        }
    }
};
