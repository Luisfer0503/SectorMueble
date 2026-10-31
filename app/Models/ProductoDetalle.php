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
        'ultima_pieza',
        'agotado',
        'porcentaje_descuento',
        'precio_descuento',
    ];

    protected $casts = [
        'precio'               => 'float',
        'stock'                => 'integer',
        'activo'               => 'boolean',
        'ultima_pieza'         => 'boolean',
        'agotado'              => 'boolean',
        'porcentaje_descuento' => 'integer',
        'precio_descuento'     => 'decimal:2',
    ];

    /**
     * Indica si el subartículo pertenece a un mueble con promoción especial seleccionada.
     */
    public function isPromoEspecial(): bool
    {
        if ($this->relationLoaded('producto')) {
            return $this->producto ? $this->producto->isPromoEspecial() : false;
        }
        return $this->producto()->first()?->isPromoEspecial() ?? false;
    }

    /**
     * Porcentaje de descuento para pago de contado.
     * 50% para productos seleccionados, 40% para el resto (durante la campaña).
     * Fuera de campaña: porcentaje_descuento individual si tiene.
     */
    public function getPorcentajeDescuentoContadoAttribute(): int
    {
        if (Producto::isCampanaPromocionalActiva()) {
            return $this->isPromoEspecial() ? 50 : 40;
        }
        return (int) ($this->attributes['porcentaje_descuento'] ?? 0);
    }

    /**
     * Porcentaje de descuento para pago con Meses Sin Intereses (3, 6, 9, 12 MSI).
     * 35% para productos seleccionados, 30% para el resto (durante la campaña).
     */
    public function getPorcentajeDescuentoMsiAttribute(): int
    {
        if (Producto::isCampanaPromocionalActiva()) {
            return $this->isPromoEspecial() ? 35 : 30;
        }
        return 0;
    }

    /**
     * Precio con descuento de contado calculado sobre el precio base original del subartículo.
     */
    public function getPrecioContadoAttribute(): float
    {
        if (Producto::isCampanaPromocionalActiva()) {
            $base = (float) $this->precio;
            return round($base * (1 - ($this->porcentaje_descuento_contado / 100)), 2);
        }
        return $this->precioEfectivo();
    }

    /**
     * Precio con descuento MSI calculado sobre el precio base original del subartículo.
     */
    public function getPrecioMsiAttribute(): float
    {
        if (Producto::isCampanaPromocionalActiva()) {
            $base = (float) $this->precio;
            return round($base * (1 - ($this->porcentaje_descuento_msi / 100)), 2);
        }
        return $this->precioEfectivo();
    }

    /**
     * Cuota mensual a Meses Sin Intereses (3, 6, 9 o 12 meses).
     */
    public function getMensualidadMsi(int $meses = 12): float
    {
        if ($meses <= 0) $meses = 12;
        return round($this->precio_msi / $meses, 2);
    }

    /**
     * Indica si el subartículo tiene promoción activa.
     */
    public function tieneDescuento(): bool
    {
        if (Producto::isCampanaPromocionalActiva()) {
            return true;
        }
        return !is_null($this->attributes['porcentaje_descuento'] ?? null) && ((int) ($this->attributes['porcentaje_descuento'] ?? 0)) > 0;
    }

    /**
     * Retorna el precio final efectivo del subartículo.
     */
    public function precioEfectivo(): float
    {
        if (Producto::isCampanaPromocionalActiva()) {
            return $this->precio_contado;
        }

        if ($this->tieneDescuento() && !empty($this->attributes['precio_descuento'])) {
            return (float) $this->attributes['precio_descuento'];
        }
        return (float) $this->precio;
    }

    /**
     * Accessor para porcentaje de descuento compatible con código previo.
     */
    public function getPorcentajeDescuentoAttribute($value): int
    {
        if (Producto::isCampanaPromocionalActiva()) {
            return $this->porcentaje_descuento_contado;
        }
        return (int) ($value ?? 0);
    }

    /**
     * Accessor para precio con descuento compatible con código previo.
     */
    public function getPrecioDescuentoAttribute($value): float
    {
        if (Producto::isCampanaPromocionalActiva()) {
            return $this->precio_contado;
        }
        return (float) ($value ?? $this->precio);
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
