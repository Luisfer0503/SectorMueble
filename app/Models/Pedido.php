<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pedido extends Model
{
    protected $table = 'pedidos';

    protected $fillable = [
        'usuario_id',
        'nombre_cliente',
        'correo_cliente',
        'telefono_cliente',
        'direccion_envio',
        'ciudad',
        'codigo_postal',
        'total',
        'cupon_codigo',
        'descuento',
        'estado',
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
    ];

    protected $casts = [
        'requiere_factura' => 'boolean',
    ];

    public function detalles()
    {
        return $this->hasMany(DetallePedido::class, 'pedido_id');
    }

    public function usuario()
    {
        return $this->belongsTo(User::class, 'usuario_id');
    }
}
