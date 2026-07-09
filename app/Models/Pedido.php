<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pedido extends Model
{
    protected $table = 'pedidos';

    protected $fillable = [
        'nombre_cliente',
        'correo_cliente',
        'telefono_cliente',
        'direccion_envio',
        'ciudad',
        'codigo_postal',
        'total',
        'estado',
    ];

    public function detalles()
    {
        return $this->hasMany(DetallePedido::class, 'pedido_id');
    }
}
