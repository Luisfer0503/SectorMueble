<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::table('productos')
            ->whereIn('categoria', ['Salón', 'Salon', 'Muebles Auxiliares'])
            ->update(['categoria' => 'Salas']);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // No revert operation required
    }
};
