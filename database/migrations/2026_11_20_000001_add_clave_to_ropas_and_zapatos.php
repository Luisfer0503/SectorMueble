<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('ropas', function (Blueprint $table) {
            if (!Schema::hasColumn('ropas', 'clave')) {
                $table->string('clave', 50)->nullable()->unique()->after('id');
            }
        });

        Schema::table('zapatos', function (Blueprint $table) {
            if (!Schema::hasColumn('zapatos', 'clave')) {
                $table->string('clave', 50)->nullable()->unique()->after('id');
            }
        });
    }

    public function down(): void
    {
        Schema::table('ropas', function (Blueprint $table) {
            if (Schema::hasColumn('ropas', 'clave')) {
                $table->dropUnique(['clave']);
                $table->dropColumn('clave');
            }
        });

        Schema::table('zapatos', function (Blueprint $table) {
            if (Schema::hasColumn('zapatos', 'clave')) {
                $table->dropUnique(['clave']);
                $table->dropColumn('clave');
            }
        });
    }
};
