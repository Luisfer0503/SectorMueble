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
        Schema::create('zapatos', function (Blueprint $table) {
            $table->id();
            $table->string('estilo')->nullable();
            $table->string('numero')->nullable();
            $table->string('color')->nullable();
            $table->string('material')->nullable();
            $table->integer('cantidad')->default(1);
            $table->decimal('precio', 10, 2)->default(0.00);
            $table->string('imagen_url')->nullable();
            $table->json('detalles_ia')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('zapatos');
    }
};
