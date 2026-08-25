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
        'bordado',
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
     * Genera la Clave Alterna según la fórmula: M(Estilo)(Material)(Color)[Bordado]T(Talla)
     * Ejemplo: M1214SINTETICONEGROT22.0
     */
    public function getClaveAlternaAttribute(): string
    {
        return self::generarClaveAlterna($this->estilo, $this->material, $this->color, $this->bordado, $this->numero);
    }

    /**
     * Genera la Clave Alterna a partir de los valores recibidos.
     */
    public static function generarClaveAlterna($estilo, $material, $color, $bordado, $numero): string
    {
        $cleanEstilo = strtoupper(preg_replace('/[^A-Za-z0-9]/', '', $estilo ?? ''));
        $cleanMaterial = strtoupper(str_replace(['Á','É','Í','Ó','Ú','á','é','í','ó','ú','Ñ','ñ'], ['A','E','I','O','U','A','E','I','O','U','N','N'], preg_replace('/[^A-Za-z0-9]/', '', $material ?? '')));
        $cleanColor = strtoupper(str_replace(['Á','É','Í','Ó','Ú','á','é','í','ó','ú','Ñ','ñ'], ['A','E','I','O','U','A','E','I','O','U','N','N'], preg_replace('/[^A-Za-z0-9]/', '', $color ?? '')));
        $cleanBordado = !empty($bordado) ? strtoupper(str_replace(['Á','É','Í','Ó','Ú','á','é','í','ó','ú','Ñ','ñ'], ['A','E','I','O','U','A','E','I','O','U','N','N'], preg_replace('/[^A-Za-z0-9]/', '', $bordado))) : '';
        
        $tallaStr = trim((string)($numero ?? ''));
        if (!str_starts_with(strtolower($tallaStr), 't')) {
            $tallaStr = 'T' . $tallaStr;
        } else {
            $tallaStr = strtoupper($tallaStr);
        }

        return "M{$cleanEstilo}{$cleanMaterial}{$cleanColor}{$cleanBordado}{$tallaStr}";
    }

    /**
     * Retorna la Descripción formateada para reportes y Excel.
     */
    public function getDescripcionCompletaAttribute(): string
    {
        $desc = "ZAPATO ESTILO {$this->estilo} {$this->material} {$this->color}";
        if (!empty($this->bordado)) {
            $desc .= " BORDADO {$this->bordado}";
        }
        $desc .= " TALLA {$this->numero}";
        return strtoupper(str_replace(['Á','É','Í','Ó','Ú','á','é','í','ó','ú'], ['A','E','I','O','U','A','E','I','O','U'], $desc));
    }

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
