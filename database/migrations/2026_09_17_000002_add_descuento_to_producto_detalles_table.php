<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Producto;
use App\Models\ProductoDetalle;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (!Schema::hasColumn('producto_detalles', 'porcentaje_descuento')) {
            Schema::table('producto_detalles', function (Blueprint $table) {
                $table->integer('porcentaje_descuento')->nullable()->after('stock');
                $table->decimal('precio_descuento', 10, 2)->nullable()->after('porcentaje_descuento');
            });
        }

        // Aplicar descuentos a King size (35%) y 5%, 10%, 15% a los demás muebles
        $discountsPool = [5, 10, 15];
        $nonKingIndex = 0;

        $productos = Producto::with('detalles')->get();

        foreach ($productos as $producto) {
            $isKing = (bool) preg_match('/king/i', $producto->nombre . ' ' . $producto->descripcion);

            if ($isKing) {
                $pct = 35;
            } else {
                $pct = $discountsPool[$nonKingIndex % 3];
                $nonKingIndex++;
            }

            // Actualizar subartículos
            foreach ($producto->detalles as $detalle) {
                $precioBase = (float) ($detalle->precio ?? $producto->precio ?? 0);
                $precioDesc = ($precioBase > 0 && $pct > 0) ? round($precioBase * (1 - ($pct / 100)), 2) : null;

                $detalle->update([
                    'porcentaje_descuento' => $pct,
                    'precio_descuento'     => $precioDesc,
                ]);
            }

            // Actualizar producto padre para mantener sincronizada la vista de lista/catálogo
            $precioPadreBase = (float) ($producto->precio ?? 0);
            $precioPadreDesc = ($precioPadreBase > 0 && $pct > 0) ? round($precioPadreBase * (1 - ($pct / 100)), 2) : null;

            $producto->update([
                'porcentaje_descuento' => $pct,
                'precio_descuento'     => $precioPadreDesc,
            ]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('producto_detalles', function (Blueprint $table) {
            $table->dropColumn(['porcentaje_descuento', 'precio_descuento']);
        });
    }
};
