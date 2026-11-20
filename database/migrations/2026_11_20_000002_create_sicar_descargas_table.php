<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('sicar_descargas', function (Blueprint $table) {
            $table->id();
            $table->string('tipo', 20); // 'ROPA' o 'CALZADO'
            $table->string('categoria', 100)->default('TODAS');
            $table->date('fecha');
            $table->dateTime('fecha_hora');
            $table->integer('total_articulos')->default(0);
            $table->longText('filas'); // JSON con las filas exportadas en esa fecha
            $table->timestamps();

            $table->index(['tipo', 'categoria', 'fecha']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sicar_descargas');
    }
};
