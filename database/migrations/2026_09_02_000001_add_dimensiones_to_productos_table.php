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
            $table->string('imagen_dimension_lateral')->nullable()->after('colores');
            $table->string('imagen_dimension_frontal')->nullable()->after('imagen_dimension_lateral');
            $table->string('imagen_dimension_superior')->nullable()->after('imagen_dimension_frontal');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('productos', function (Blueprint $table) {
            $table->dropColumn(['imagen_dimension_lateral', 'imagen_dimension_frontal', 'imagen_dimension_superior']);
        });
    }
};
