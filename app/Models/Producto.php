<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Producto extends Model
{
    use HasFactory;

    protected $table = 'productos';

    protected $fillable = [
        'nombre',
        'slug',
        'descripcion',
        'precio',
        'imagen_url',
        'categoria',
        'stock',
        'calificacion',
        'destacado',
        'porcentaje_descuento',
        'precio_descuento',
    ];

    protected $casts = [
        'precio'                => 'decimal:2',
        'precio_descuento'      => 'decimal:2',
        'calificacion'          => 'decimal:1',
        'destacado'             => 'boolean',
        'stock'                 => 'integer',
        'porcentaje_descuento'  => 'integer',
    ];

    /**
     * Indica si el producto tiene descuento directo activo.
     */
    public function tieneDescuento(): bool
    {
        return !is_null($this->porcentaje_descuento) && $this->porcentaje_descuento > 0;
    }

    /**
     * Retorna el precio final (con descuento si aplica, si no el precio normal).
     */
    public function precioEfectivo(): float
    {
        if ($this->tieneDescuento() && $this->precio_descuento) {
            return (float) $this->precio_descuento;
        }
        return (float) $this->precio;
    }
}
