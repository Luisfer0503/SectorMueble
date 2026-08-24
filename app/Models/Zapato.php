<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Zapato extends Model
{
    use HasFactory;

    protected $table = 'zapatos';

    protected $fillable = [
        'estilo',
        'numero',
        'color',
        'material',
        'cantidad',
        'precio',
        'imagen_url',
        'detalles_ia',
    ];

    protected $casts = [
        'cantidad'    => 'integer',
        'precio'      => 'decimal:2',
        'detalles_ia' => 'array',
    ];

    /**
     * Accessor para formatear siempre la URL completa de la imagen del zapato.
     */
    public function getImagenUrlAttribute($value): string
    {
        if (empty($value)) {
            return asset('storage/zapatos/default.png');
        }

        if (str_starts_with($value, 'http://') || str_starts_with($value, 'https://')) {
            return $value;
        }

        return asset(ltrim($value, '/'));
    }

    /**
     * Calcula el valor total de inventario de este ítem (cantidad * precio).
     */
    public function valorTotal(): float
    {
        return (float) ($this->cantidad * $this->precio);
    }
}
