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
        Schema::table('pedidos', function (Blueprint $table) {
            $table->boolean('requiere_factura')->default(false)->after('estado');
            $table->string('rfc_receptor')->nullable()->after('requiere_factura');
            $table->string('razon_social')->nullable()->after('rfc_receptor');
            $table->string('regimen_fiscal')->nullable()->after('razon_social');
            $table->string('uso_cfdi')->nullable()->after('regimen_fiscal');
            $table->string('codigo_postal_fiscal')->nullable()->after('uso_cfdi');
            $table->string('correo_facturacion')->nullable()->after('codigo_postal_fiscal');
            $table->string('factura_estado')->default('no_solicitada')->after('correo_facturacion');
            $table->string('factura_uuid')->nullable()->after('factura_estado');
            $table->string('factura_pdf_url')->nullable()->after('factura_uuid');
            $table->string('factura_xml_url')->nullable()->after('factura_pdf_url');
            $table->text('factura_error')->nullable()->after('factura_xml_url');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pedidos', function (Blueprint $table) {
            $table->dropColumn([
                'requiere_factura',
                'rfc_receptor',
                'razon_social',
                'regimen_fiscal',
                'uso_cfdi',
                'codigo_postal_fiscal',
                'correo_facturacion',
                'factura_estado',
                'factura_uuid',
                'factura_pdf_url',
                'factura_xml_url',
                'factura_error',
            ]);
        });
    }
};
