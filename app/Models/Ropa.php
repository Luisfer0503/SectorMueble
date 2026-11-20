<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ropa extends Model
{
    use HasFactory;

    protected $table = 'ropas';

    protected $fillable = [
        'clave',
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
     * Si en la misma categoría existe un registro previo con el mismo modelo pero DIFERENTE precio,
     * se le concatena el precio al final (ejemplo: RNIKEPOLO102NEGROTCH270).
     */
    public function getClaveAlternaAttribute(): string
    {
        return self::generarClaveAlterna(
            $this->marca,
            $this->estilo,
            $this->art,
            $this->color,
            $this->talla,
            $this->precio,
            $this->categoria
        );
    }

    /**
     * Genera la Clave Alterna a partir de los valores recibidos.
     * Si existe un artículo previo en la categoría con características idénticas pero precio diferente,
     * agrega el precio al final de la clave.
     */
    public static function generarClaveAlterna($marca, $estilo, $art, $color, $talla, $precio = null, $categoria = null, $ignoreId = null): string
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

        $baseClave = "R{$cleanMarca}{$cleanEstilo}{$cleanArt}{$cleanColor}{$tallaStr}";

        if (empty($categoria) || $precio === null) {
            return $baseClave;
        }

        $floatPrecio = (float)$precio;

        // Buscar el primer registro base creado en la categoría con estas mismas características
        $queryBase = self::query()->where('categoria', $categoria)
            ->whereRaw('LOWER(TRIM(marca)) = ?', [strtolower(trim($marca))])
            ->whereRaw('LOWER(TRIM(COALESCE(estilo, ""))) = ?', [strtolower(trim($estilo ?? ''))])
            ->whereRaw('LOWER(TRIM(COALESCE(art, ""))) = ?', [strtolower(trim($art ?? ''))])
            ->whereRaw('LOWER(TRIM(COALESCE(color, ""))) = ?', [strtolower(trim($color ?? ''))])
            ->whereRaw('LOWER(TRIM(talla)) = ?', [strtolower(trim($talla))]);

        if (!empty($ignoreId)) {
            $queryBase->where('id', '!=', $ignoreId);
        }

        $firstBase = $queryBase->orderBy('id', 'asc')->first();

        if ($firstBase) {
            $precioBase = (float)$firstBase->precio;
            // Si el precio de este artículo difiere del precio del registro base original
            if (abs($floatPrecio - $precioBase) >= 0.01) {
                $precioClean = ($floatPrecio == (int)$floatPrecio) ? (string)(int)$floatPrecio : str_replace('.', '', (string)$floatPrecio);
                return $baseClave . $precioClean;
            }
        }

        return $baseClave;
    }

    /**
     * Retorna la Descripción formateada para reportes y Excel.
     */
    public function getDescripcionCompletaAttribute(): string
    {
        $tipo = Zapato::obtenerPrimeraPalabraSingular($this->categoria, 'ROPA');
        $desc = "{$tipo} MARCA {$this->marca}";
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
     * Retorna la Descripción formateada para SICAR con toda la información y código de barras.
     */
    public function getDescripcionSicarAttribute(): string
    {
        $tipo = Zapato::obtenerPrimeraPalabraSingular($this->categoria, 'ROPA');
        $parts = [$tipo];

        if (!empty($this->marca)) {
            $parts[] = "MARCA {$this->marca}";
        }
        if (!empty($this->estilo)) {
            $parts[] = "ESTILO {$this->estilo}";
        }
        if (!empty($this->art)) {
            $parts[] = "ART {$this->art}";
        }
        if (!empty($this->color)) {
            $parts[] = "COLOR {$this->color}";
        }
        if (!empty($this->talla)) {
            $parts[] = "TALLA {$this->talla}";
        }
        if (!empty($this->codigo_barras)) {
            $parts[] = "BARCODE {$this->codigo_barras}";
        }

        $desc = implode(' ', $parts);
        return strtoupper(str_replace(
            ['Á','É','Í','Ó','Ú','á','é','í','ó','ú'],
            ['A','E','I','O','U','A','E','I','O','U'],
            preg_replace('/\s+/', ' ', trim($desc))
        ));
    }

    /**
     * Genera el prefijo de la Clave SICAR basado en el nombre de la categoría.
     * Ejemplo: "Abrigo Caballero" => "ABRIGOCAB", "BLUSAS" => "BLUSAS"
     */
    public static function generarPrefijoCategoria(?string $categoria, string $fallback = 'ROPA'): string
    {
        $clean = strtoupper(str_replace(
            ['Á','É','Í','Ó','Ú','á','é','í','ó','ú','Ñ','ñ'],
            ['A','E','I','O','U','A','E','I','O','U','N','N'],
            $categoria ?? ''
        ));
        $clean = preg_replace('/[^A-Z0-9\s]/', '', $clean);
        $allWords = array_values(array_filter(explode(' ', trim($clean))));

        $stopWords = ['DE', 'DEL', 'PARA', 'Y', 'EL', 'LA', 'LOS', 'LAS', 'EN', 'CON', 'POR', 'O'];
        $words = (count($allWords) > 1) 
            ? array_values(array_filter($allWords, fn($w) => !in_array($w, $stopWords)))
            : $allWords;

        if (empty($words)) {
            $words = !empty($allWords) ? $allWords : [$fallback];
        }

        if (count($words) === 1) {
            return substr($words[0], 0, 15);
        }

        $prefix = substr($words[0], 0, 10);
        for ($i = 1; $i < count($words); $i++) {
            $w = $words[$i];
            if (in_array($w, ['NINA', 'NINAS'])) {
                $prefix .= 'NINA';
            } elseif (in_array($w, ['NINO', 'NINOS'])) {
                $prefix .= 'NINO';
            } else {
                $prefix .= substr($w, 0, 3);
            }
        }

        return substr($prefix, 0, 20);
    }

    /**
     * Genera la siguiente Clave SICAR única y secuencial para la categoría.
     * Formato: PREFIJO + 8 dígitos (ej. ABRIGOCAB00000001)
     */
    public static function generarSiguienteClave(?string $categoria, ?int $ignoreId = null): string
    {
        $prefix = self::generarPrefijoCategoria($categoria, 'ROPA');
        
        $query = self::where('clave', 'like', "{$prefix}%");
        if ($ignoreId) {
            $query->where('id', '!=', $ignoreId);
        }
        $clavesExistentes = $query->pluck('clave')->toArray();

        $maxNum = 0;
        foreach ($clavesExistentes as $c) {
            if (preg_match('/^' . preg_quote($prefix, '/') . '(\d+)$/', $c, $m)) {
                $num = (int)$m[1];
                if ($num > $maxNum) {
                    $maxNum = $num;
                }
            }
        }

        $siguienteNum = $maxNum + 1;
        $siguienteClave = $prefix . sprintf('%08d', $siguienteNum);

        while (self::where('clave', $siguienteClave)->when($ignoreId, fn($q) => $q->where('id', '!=', $ignoreId))->exists()) {
            $siguienteNum++;
            $siguienteClave = $prefix . sprintf('%08d', $siguienteNum);
        }

        return $siguienteClave;
    }

    /**
     * Obtiene la clave persistente de la prenda o la genera y guarda si no la tiene aún.
     * Una vez asignada, NUNCA cambia para evitar inconsistencias en SICAR.
     */
    public function obtenerOAsignarClave(): string
    {
        if (!empty($this->clave)) {
            return $this->clave;
        }

        $nuevaClave = self::generarSiguienteClave($this->categoria ?? 'ROPA DIVERSA', $this->id);
        $this->clave = $nuevaClave;
        $this->save();

        return $nuevaClave;
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
