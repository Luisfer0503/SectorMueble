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
        Schema::create('producto_detalles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('producto_id')->constrained('productos')->onDelete('cascade');
            $table->string('sku')->nullable()->comment('SKU dejado en blanco por requerimiento');
            $table->string('nombre');
            $table->string('imagen')->nullable();
            $table->integer('stock')->default(0);
            $table->timestamps();
        });

        // Poblar la tabla producto_detalles a partir de los datos existentes en el JSON 'colores' de productos
        $productos = DB::table('productos')->get();

        foreach ($productos as $producto) {
            $coloresRaw = $producto->colores;
            $stockPadre = (int) ($producto->stock ?? 10);
            
            if (!empty($coloresRaw)) {
                $colores = json_decode($coloresRaw, true);
                if (is_array($colores) && count($colores) > 0) {
                    foreach ($colores as $index => $item) {
                        $nombre = !empty($item['nombre']) ? trim($item['nombre']) : ('Acabado ' . ($index + 1));
                        $imagen = $item['mueble_imagen'] ?? $item['material_imagen'] ?? $item['imagen'] ?? null;

                        DB::table('producto_detalles')->insert([
                            'producto_id' => $producto->id,
                            'sku'         => null,
                            'nombre'      => $nombre,
                            'imagen'      => $imagen,
                            'stock'       => $stockPadre,
                            'created_at'  => now(),
                            'updated_at'  => now(),
                        ]);
                    }
                } else {
                    // Si no hay acabados específicos, crear al menos un detalle principal por defecto
                    DB::table('producto_detalles')->insert([
                        'producto_id' => $producto->id,
                        'sku'         => null,
                        'nombre'      => $producto->nombre . ' (Original / Natural)',
                        'imagen'      => $producto->imagen_url,
                        'stock'       => $stockPadre,
                        'created_at'  => now(),
                        'updated_at'  => now(),
                    ]);
                }
            } else {
                // Acabado por defecto
                DB::table('producto_detalles')->insert([
                    'producto_id' => $producto->id,
                    'sku'         => null,
                    'nombre'      => $producto->nombre . ' (Original / Natural)',
                    'imagen'      => $producto->imagen_url,
                    'stock'       => $stockPadre,
                    'created_at'  => now(),
                    'updated_at'  => now(),
                ]);
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('producto_detalles');
    }
};
