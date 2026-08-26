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
        'imagen_secundaria_url',
        'categoria',
        'stock',
        'calificacion',
        'destacado',
        'porcentaje_descuento',
        'precio_descuento',
        'colores',
    ];

    protected $casts = [
        'precio'                => 'decimal:2',
        'precio_descuento'      => 'decimal:2',
        'calificacion'          => 'decimal:1',
        'destacado'             => 'boolean',
        'stock'                 => 'integer',
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
     * Accessor para formatear siempre correctamente la URL de la imagen principal del producto.
     */
    public function getImagenUrlAttribute($value): string
    {
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
}
