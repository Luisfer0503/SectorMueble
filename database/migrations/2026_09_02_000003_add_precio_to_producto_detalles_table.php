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
        Schema::table('producto_detalles', function (Blueprint $table) {
            if (!Schema::hasColumn('producto_detalles', 'precio')) {
                $table->decimal('precio', 10, 2)->nullable()->after('imagen');
            }
        });

        // Autocompletar precios y SKUs en producto_detalles existentes
        $detalles = DB::table('producto_detalles')->get();
        foreach ($detalles as $det) {
            $producto = DB::table('productos')->where('id', $det->producto_id)->first();
            $precioDefecto = $producto->precio ?? 0.00;

            $skuDefecto = $det->sku;
            if (empty($skuDefecto)) {
                $skuDefecto = 'SKU-' . sprintf('%04d', $det->producto_id) . '-' . sprintf('%02d', $det->id);
            }

            DB::table('producto_detalles')
                ->where('id', $det->id)
                ->update([
                    'precio' => $det->precio ?? $precioDefecto,
                    'sku'    => $skuDefecto,
                ]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('producto_detalles', function (Blueprint $table) {
            if (Schema::hasColumn('producto_detalles', 'precio')) {
                $table->dropColumn('precio');
            }
        });
    }
};
