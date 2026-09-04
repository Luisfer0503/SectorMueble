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
            if (!Schema::hasColumn('productos', 'tipo_mueble')) {
                $table->string('tipo_mueble')->default('01')->after('proveedor');
            }
            if (!Schema::hasColumn('productos', 'numero_piezas')) {
                $table->integer('numero_piezas')->default(1)->after('tipo_mueble');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('productos', function (Blueprint $table) {
            if (Schema::hasColumn('productos', 'tipo_mueble')) {
                $table->dropColumn('tipo_mueble');
            }
            if (Schema::hasColumn('productos', 'numero_piezas')) {
                $table->dropColumn('numero_piezas');
            }
        });
    }
};
