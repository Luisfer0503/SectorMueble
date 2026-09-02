<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductoDetalle extends Model
{
    use HasFactory;

    protected $table = 'producto_detalles';

    protected $fillable = [
        'producto_id',
        'sku',
        'nombre',
        'imagen',
        'precio',
        'stock',
    ];

    protected $casts = [
        'precio' => 'float',
        'stock'  => 'integer',
    ];

    /**
     * Relación con el mueble padre.
     */
    public function producto(): BelongsTo
    {
        return $this->belongsTo(Producto::class, 'producto_id');
    }

    /**
     * Accessor para la URL completa de la imagen del detalle/subartículo.
     */
    public function getImagenUrlAttribute(): ?string
    {
        $val = $this->attributes['imagen'] ?? null;
        if (empty($val)) {
            return asset('storage/productos/default.png');
        }

        if (str_starts_with($val, 'http://') || str_starts_with($val, 'https://')) {
            return $val;
        }

        return asset(ltrim($val, '/'));
    }
}
