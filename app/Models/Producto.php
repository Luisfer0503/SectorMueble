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
     * Accessor para formatear siempre correctamente la URL de la imagen del producto.
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
     * Retorna la lista estructurada de colores para el producto (configurada por Admin o fallback por defecto).
     */
    public function getColoresListaAttribute(): array
    {
        $raw = $this->colores;
        if (!empty($raw) && is_array($raw)) {
            $formatted = [];
            foreach ($raw as $index => $item) {
                $img = !empty($item['imagen']) ? asset(ltrim($item['imagen'], '/')) : null;
                $formatted[] = [
                    'key' => 'color_' . $index,
                    'nombre' => $item['nombre'] ?? ('Color ' . ($index + 1)),
                    'hex' => $item['hex'] ?? '#888888',
                    'imagen' => $img,
                    'filter' => $item['filter'] ?? null,
                ];
            }
            return $formatted;
        }

        // Colores por defecto si aún no se han configurado específicamente
        return [
            [
                'key' => 'color_0',
                'nombre' => 'Original / Natural',
                'hex' => '#D4A373',
                'imagen' => $this->imagen_url,
                'filter' => 'none',
            ],
            [
                'key' => 'color_1',
                'nombre' => 'Nogal Oscuro',
                'hex' => '#4A2810',
                'imagen' => null,
                'filter' => 'sepia(0.35) hue-rotate(-20deg) brightness(0.82) contrast(1.15)',
            ],
            [
                'key' => 'color_2',
                'nombre' => 'Gris Grafito',
                'hex' => '#374151',
                'imagen' => null,
                'filter' => 'grayscale(0.88) brightness(0.88) contrast(1.15)',
            ],
        ];
    }
}
