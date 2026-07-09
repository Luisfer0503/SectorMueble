<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cupon extends Model
{
    protected $table = 'cupones';

    protected $fillable = [
        'codigo',
        'tipo',
        'valor',
        'activo',
    ];

    protected $casts = [
        'valor' => 'decimal:2',
        'activo' => 'boolean',
    ];

    /**
     * Calcula el importe del descuento para un subtotal dado.
     */
    public function calcularDescuento($subtotal)
    {
        if ($this->tipo === 'porcentaje') {
            return ($subtotal * $this->valor) / 100;
        }

        // Tipo fijo
        return min($this->valor, $subtotal);
    }
}
