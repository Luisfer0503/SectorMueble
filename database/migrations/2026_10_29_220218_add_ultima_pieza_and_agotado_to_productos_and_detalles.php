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
        Schema::table('productos', function (Blueprint $table) {
            if (!Schema::hasColumn('productos', 'ultima_pieza')) {
                $table->boolean('ultima_pieza')->default(false)->after('activo');
            }
            if (!Schema::hasColumn('productos', 'agotado')) {
                $table->boolean('agotado')->default(false)->after('ultima_pieza');
            }
        });

        Schema::table('producto_detalles', function (Blueprint $table) {
            if (!Schema::hasColumn('producto_detalles', 'ultima_pieza')) {
                $table->boolean('ultima_pieza')->default(false)->after('activo');
            }
            if (!Schema::hasColumn('producto_detalles', 'agotado')) {
                $table->boolean('agotado')->default(false)->after('ultima_pieza');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('productos', function (Blueprint $table) {
            if (Schema::hasColumn('productos', 'agotado')) {
                $table->dropColumn('agotado');
            }
            if (Schema::hasColumn('productos', 'ultima_pieza')) {
                $table->dropColumn('ultima_pieza');
            }
        });

        Schema::table('producto_detalles', function (Blueprint $table) {
            if (Schema::hasColumn('producto_detalles', 'agotado')) {
                $table->dropColumn('agotado');
            }
            if (Schema::hasColumn('producto_detalles', 'ultima_pieza')) {
                $table->dropColumn('ultima_pieza');
            }
        });
    }
};
