<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (!Schema::hasColumn('productos', 'promo_especial')) {
            Schema::table('productos', function (Blueprint $table) {
                $table->boolean('promo_especial')->default(false)->after('agotado');
            });
        }

        if (!Schema::hasColumn('pedidos', 'tipo_pago')) {
            Schema::table('pedidos', function (Blueprint $table) {
                $table->string('tipo_pago', 50)->default('contado')->after('total');
            });
        }

        // Marcar los 6 muebles seleccionados iniciales:
        // Cama Aura, Sofá Milán, Sillón Bimba, Banca Zenit, Bombay Sofá Cama, Sala Concherto
        $nombres = [
            'Aura%',
            'Milán - Sofá%',
            'Bimba%',
            'Zenit%',
            'Bombay%',
            'Sala Concherto%',
        ];

        foreach ($nombres as $patron) {
            DB::table('productos')->where('nombre', 'like', $patron)->update(['promo_especial' => true]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasColumn('productos', 'promo_especial')) {
            Schema::table('productos', function (Blueprint $table) {
                $table->dropColumn('promo_especial');
            });
        }

        if (Schema::hasColumn('pedidos', 'tipo_pago')) {
            Schema::table('pedidos', function (Blueprint $table) {
                $table->dropColumn('tipo_pago');
            });
        }
    }
};

