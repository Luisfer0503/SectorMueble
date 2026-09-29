<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ropa extends Model
{
    use HasFactory;

    protected $table = 'ropas';

    protected $fillable = [
        'categoria',
        'marca',
        'talla',
        'estilo',
        'color',
        'codigo_barras',
        'art',
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
     * Genera la Clave Alterna según la fórmula para Ropa: R(Marca)(Estilo)(Art)(Color)T(Talla)
     * Ejemplo: RNIKEPOLO102NEGROTCH
     */
    public function getClaveAlternaAttribute(): string
    {
        return self::generarClaveAlterna(
            $this->marca,
            $this->estilo,
            $this->art,
            $this->color,
            $this->talla
        );
    }

    /**
     * Genera la Clave Alterna a partir de los valores recibidos sin incluir el código de barras.
     */
    public static function generarClaveAlterna($marca, $estilo, $art, $color, $talla): string
    {
        $cleanMarca  = strtoupper(str_replace(['Á','É','Í','Ó','Ú','á','é','í','ó','ú','Ñ','ñ'], ['A','E','I','O','U','A','E','I','O','U','N','N'], preg_replace('/[^A-Za-z0-9]/', '', $marca ?? '')));
        $cleanEstilo = !empty($estilo) ? strtoupper(preg_replace('/[^A-Za-z0-9]/', '', $estilo)) : '';
        $cleanArt    = !empty($art) ? strtoupper(preg_replace('/[^A-Za-z0-9]/', '', $art)) : '';
        $cleanColor  = !empty($color) ? strtoupper(str_replace(['Á','É','Í','Ó','Ú','á','é','í','ó','ú','Ñ','ñ'], ['A','E','I','O','U','A','E','I','O','U','N','N'], preg_replace('/[^A-Za-z0-9]/', '', $color))) : '';
        
        $tallaStr = trim((string)($talla ?? ''));
        if (!str_starts_with(strtolower($tallaStr), 't')) {
            $tallaStr = 'T' . $tallaStr;
        } else {
            $tallaStr = strtoupper($tallaStr);
        }

        return "R{$cleanMarca}{$cleanEstilo}{$cleanArt}{$cleanColor}{$tallaStr}";
    }

    /**
     * Retorna la Descripción formateada para reportes y Excel.
     */
    public function getDescripcionCompletaAttribute(): string
    {
        $desc = "ROPA MARCA {$this->marca}";
        if (!empty($this->estilo)) {
            $desc .= " ESTILO {$this->estilo}";
        }
        if (!empty($this->art)) {
            $desc .= " ART {$this->art}";
        }
        if (!empty($this->color)) {
            $desc .= " COLOR {$this->color}";
        }
        $desc .= " TALLA {$this->talla}";
        return strtoupper(str_replace(['Á','É','Í','Ó','Ú','á','é','í','ó','ú'], ['A','E','I','O','U','A','E','I','O','U'], $desc));
    }

    /**
     * Accessor para formatear siempre la URL completa de la imagen de ropa.
     */
    public function getImagenUrlAttribute($value): string
    {
        if (empty($value)) {
            return asset('storage/ropa/default.png');
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
