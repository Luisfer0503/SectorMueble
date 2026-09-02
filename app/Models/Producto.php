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
        'imagen_url',
        'imagen_secundaria_url',
        'modelo_3d_url',
        'categoria',
        'calificacion',
        'destacado',
        'porcentaje_descuento',
        'precio_descuento',
        'colores',
        'imagen_dimension_lateral',
        'imagen_dimension_frontal',
        'imagen_dimension_superior',
    ];

    protected $casts = [
        'precio_descuento'      => 'decimal:2',
        'calificacion'          => 'decimal:1',
        'destacado'             => 'boolean',
        'porcentaje_descuento'  => 'integer',
        'colores'               => 'array',
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

    /**
     * Accessor para limpiar automáticamente menciones a Casa Tapier o Samar de la descripción.
     */
    public function getDescripcionAttribute($value): string
    {
        if (empty($value)) {
            return '';
        }

        $clean = preg_replace('/\.?\s*Proveedor\s*:\s*(CASA TAPIER|SAMAR MUEBLES|SAMAR|CASA TAPIER\.)/i', '', $value);
        $clean = preg_replace('/CASA\s+TAPIER/i', '', $clean);
        $clean = preg_replace('/SAMAR\s+MUEBLES/i', '', $clean);
        $clean = preg_replace('/\bSAMAR\b/i', '', $clean);
        $clean = preg_replace('/\s+/', ' ', $clean);
        $clean = preg_replace('/\s+([,\.])/', '$1', $clean);
        $clean = preg_replace('/([,\.])\s*([,\.])+/', '$1', $clean);

        return trim($clean);
    }

    /**
     * Accessor para la imagen principal del producto.
     * Toma prioritariamente la imagen del primer acabado/subartículo registrado en producto_detalles.
     */
    public function getImagenUrlAttribute($value): string
    {
        $primerDetalle = null;
        if ($this->relationLoaded('detalles')) {
            $primerDetalle = $this->detalles->first();
        } else {
            $primerDetalle = $this->detalles()->first();
        }

        if ($primerDetalle && !empty($primerDetalle->imagen)) {
            return $primerDetalle->imagen_url;
        }

        if (empty($value)) {
            return asset('storage/productos/default.png');
        }

        if (str_starts_with($value, 'http://') || str_starts_with($value, 'https://')) {
            return $value;
        }

        return asset(ltrim($value, '/'));
    }

    /**
     * Accessor para la URL de la imagen secundaria del producto.
     */
    public function getImagenSecundariaUrlAttribute($value): ?string
    {
        if (empty($value)) {
            return null;
        }

        if (str_starts_with($value, 'http://') || str_starts_with($value, 'https://')) {
            return $value;
        }

        return asset(ltrim($value, '/'));
    }

    /**
     * Retorna la lista estructurada de acabados (material e imagen de mueble por acabado).
     */
    public function getAcabadosListaAttribute(): array
    {
        $raw = $this->colores;
        if (!empty($raw) && is_array($raw)) {
            $formatted = [];
            foreach ($raw as $index => $item) {
                $matImg = !empty($item['material_imagen']) ? asset(ltrim($item['material_imagen'], '/')) : (!empty($item['hex']) ? null : null);
                $muebleImg = !empty($item['mueble_imagen']) ? asset(ltrim($item['mueble_imagen'], '/')) : (!empty($item['imagen']) ? asset(ltrim($item['imagen'], '/')) : $this->imagen_url);

                $formatted[] = [
                    'key' => 'acabado_' . $index,
                    'nombre' => $item['nombre'] ?? ('Acabado ' . ($index + 1)),
                    'material_imagen' => $matImg,
                    'mueble_imagen' => $muebleImg,
                ];
            }
            return $formatted;
        }

        // Acabados por defecto si aún no se han configurado
        return [
            [
                'key' => 'acabado_0',
                'nombre' => 'Madera Natural',
                'material_imagen' => null,
                'mueble_imagen' => $this->imagen_url,
            ],
        ];
    }

    /**
     * Alias de retrocompatibilidad.
     */
    public function getColoresListaAttribute(): array
    {
        return $this->getAcabadosListaAttribute();
    }

    /**
     * Accessors para las URLs de imágenes de dimensiones
     */
    public function getImagenDimensionLateralUrlAttribute(): ?string
    {
        $val = $this->getRawOriginal('imagen_dimension_lateral');
        if (empty($val)) return null;
        if (str_starts_with($val, 'http://') || str_starts_with($val, 'https://')) return $val;
        return asset(ltrim($val, '/'));
    }

    public function getImagenDimensionFrontalUrlAttribute(): ?string
    {
        $val = $this->getRawOriginal('imagen_dimension_frontal');
        if (empty($val)) return null;
        if (str_starts_with($val, 'http://') || str_starts_with($val, 'https://')) return $val;
        return asset(ltrim($val, '/'));
    }

    public function getImagenDimensionSuperiorUrlAttribute(): ?string
    {
        $val = $this->getRawOriginal('imagen_dimension_superior');
        if (empty($val)) return null;
        if (str_starts_with($val, 'http://') || str_starts_with($val, 'https://')) return $val;
        return asset(ltrim($val, '/'));
    }

    /**
     * Relación HasMany con los subartículos/detalles del mueble.
     */
    public function detalles()
    {
        return $this->hasMany(ProductoDetalle::class, 'producto_id');
    }

    /**
     * Obtiene dinámicamente el precio principal del mueble desde el primer subartículo (producto_detalles).
     */
    public function getPrecioAttribute(): float
    {
        if ($this->relationLoaded('detalles')) {
            $primerDetalle = $this->detalles->first();
            if ($primerDetalle && $primerDetalle->precio !== null) {
                return (float) $primerDetalle->precio;
            }
        }

        $primerDetalle = $this->detalles()->first();
        if ($primerDetalle && $primerDetalle->precio !== null) {
            return (float) $primerDetalle->precio;
        }

        return 0.00;
    }

    /**
     * Obtiene dinámicamente el stock total del mueble sumando el inventario de sus subartículos (producto_detalles).
     */
    public function getStockAttribute(): int
    {
        if ($this->relationLoaded('detalles')) {
            return (int) $this->detalles->sum('stock');
        }
        return (int) $this->detalles()->sum('stock');
    }
}
