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
        if (!Schema::hasColumn('zapatos', 'categoria')) {
            Schema::table('zapatos', function (Blueprint $table) {
                $table->string('categoria')->default('ZAPATO ESCOLAR')->after('bordado');
            });
        }

        // Asegurar que cualquier registro previo quede con ZAPATO ESCOLAR
        DB::table('zapatos')->whereNull('categoria')->orWhere('categoria', '')->update([
            'categoria' => 'ZAPATO ESCOLAR'
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('zapatos', function (Blueprint $table) {
            $table->dropColumn('categoria');
        });
    }
};
