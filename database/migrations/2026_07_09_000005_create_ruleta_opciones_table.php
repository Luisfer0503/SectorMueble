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
        Schema::create('ruleta_opciones', function (Blueprint $table) {
            $table->id();
            $table->integer('posicion')->unique(); // 1, 2, 3
            $table->string('titulo');
            $table->string('codigo_cupon')->nullable();
            $table->string('tipo_descuento')->default('porcentaje'); // porcentaje, fijo, envio_gratis
            $table->decimal('descuento_valor', 8, 2)->default(0);
            $table->integer('tiempo_minutos')->default(15); // tiempo para reclamar
            $table->string('color_bg')->default('#D97706');
            $table->boolean('activo')->default(true);
            $table->timestamps();
        });

        // Insertar las 3 opciones por defecto
        DB::table('ruleta_opciones')->insert([
            [
                'posicion' => 1,
                'titulo' => '15% OFF en tu primera compra',
                'codigo_cupon' => 'RULETA15',
                'tipo_descuento' => 'porcentaje',
                'descuento_valor' => 15.00,
                'tiempo_minutos' => 15,
                'color_bg' => '#B45309', // Amber 700
                'activo' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'posicion' => 2,
                'titulo' => 'Envío Gratis en tu pedido',
                'codigo_cupon' => 'ENVIORULETA',
                'tipo_descuento' => 'envio_gratis',
                'descuento_valor' => 0.00,
                'tiempo_minutos' => 20,
                'color_bg' => '#15803D', // Green 700
                'activo' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'posicion' => 3,
                'titulo' => '$500 Descuento Especial',
                'codigo_cupon' => 'RULETA500',
                'tipo_descuento' => 'fijo',
                'descuento_valor' => 500.00,
                'tiempo_minutos' => 10,
                'color_bg' => '#1E3A8A', // Blue 900
                'activo' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ruleta_opciones');
    }
};
