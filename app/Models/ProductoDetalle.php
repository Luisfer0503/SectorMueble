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
        'material_imagen',
        'precio',
        'stock',
        'activo',
        'porcentaje_descuento',
        'precio_descuento',
    ];

    protected $casts = [
        'precio'               => 'float',
        'stock'                => 'integer',
        'activo'               => 'boolean',
        'porcentaje_descuento' => 'integer',
        'precio_descuento'     => 'decimal:2',
    ];

    /**
     * Indica si el subartículo tiene descuento directo activo.
     */
    public function tieneDescuento(): bool
    {
        return !is_null($this->porcentaje_descuento) && $this->porcentaje_descuento > 0;
    }

    /**
     * Retorna el precio final efectivo del subartículo.
     */
    public function precioEfectivo(): float
    {
        if ($this->tieneDescuento() && $this->precio_descuento !== null) {
            return (float) $this->precio_descuento;
        }
        return (float) $this->precio;
    }

    /**
     * Scope para filtrar únicamente subartículos activos.
     */
    public function scopeActivo($query)
    {
        return $query->where('activo', true);
    }

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

    /**
     * Accessor para la URL completa de la muestra de material.
     */
    public function getMaterialImagenUrlAttribute(): ?string
    {
        $val = $this->attributes['material_imagen'] ?? null;
        if (empty($val)) {
            return null;
        }

        if (str_starts_with($val, 'http://') || str_starts_with($val, 'https://')) {
            return $val;
        }

        return asset(ltrim($val, '/'));
    }
}
