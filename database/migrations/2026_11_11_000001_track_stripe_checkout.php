<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('pedidos', function (Blueprint $table) {
            if (!Schema::hasColumn('pedidos', 'stripe_session_id')) {
                $table->string('stripe_session_id')->nullable()->unique();
            }
            if (!Schema::hasColumn('pedidos', 'checkout_fingerprint')) {
                $table->string('checkout_fingerprint', 64)->nullable();
            }
            if (!Schema::hasColumn('pedidos', 'stripe_currency')) {
                $table->string('stripe_currency', 3)->nullable();
            }
            if (!Schema::hasColumn('pedidos', 'stripe_paid_at')) {
                $table->timestamp('stripe_paid_at')->nullable();
            }
            if (!Schema::hasColumn('pedidos', 'stock_reservado')) {
                $table->boolean('stock_reservado')->default(false);
            }
        });
        Schema::table('detalles_pedido', function (Blueprint $table) {
            if (!Schema::hasColumn('detalles_pedido', 'producto_detalle_id')) {
                $table->unsignedBigInteger('producto_detalle_id')->nullable();
            }
        });
    }

    public function down(): void
    {
        Schema::table('detalles_pedido', fn (Blueprint $table) => $table->dropColumn('producto_detalle_id'));
        Schema::table('pedidos', function (Blueprint $table) {
            $table->dropUnique(['stripe_session_id']);
            $table->dropColumn(['stripe_session_id', 'checkout_fingerprint', 'stripe_currency', 'stripe_paid_at', 'stock_reservado']);
        });
    }
};
