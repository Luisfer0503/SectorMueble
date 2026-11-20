<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Zapato extends Model
{
    use HasFactory;

    protected $table = 'zapatos';

    protected $fillable = [
        'clave',
        'categoria',
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
     * Si en la misma categoría existe un registro previo con el mismo modelo pero DIFERENTE precio,
     * se le concatena el precio al final (ejemplo: M1214SINTETICONEGROT22.0250).
     */
    public function getClaveAlternaAttribute(): string
    {
        return self::generarClaveAlterna(
            $this->estilo,
            $this->material,
            $this->color,
            $this->bordado,
            $this->numero,
            $this->precio,
            $this->categoria
        );
    }

    /**
     * Genera la Clave Alterna a partir de los valores recibidos.
     * Si existe un calzado previo en la categoría con características idénticas pero precio diferente,
     * agrega el precio al final de la clave.
     */
    public static function generarClaveAlterna($estilo, $material, $color, $bordado, $numero, $precio = null, $categoria = null, $ignoreId = null): string
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

        $baseClave = "M{$cleanEstilo}{$cleanMaterial}{$cleanColor}{$cleanBordado}{$tallaStr}";

        if (empty($categoria) || $precio === null) {
            return $baseClave;
        }

        $floatPrecio = (float)$precio;

        // Buscar el primer registro base creado en la categoría con estas mismas características
        $queryBase = self::query()->where('categoria', $categoria)
            ->whereRaw('LOWER(TRIM(COALESCE(estilo, ""))) = ?', [strtolower(trim($estilo ?? ''))])
            ->whereRaw('LOWER(TRIM(COALESCE(material, ""))) = ?', [strtolower(trim($material ?? ''))])
            ->whereRaw('LOWER(TRIM(COALESCE(color, ""))) = ?', [strtolower(trim($color ?? ''))])
            ->whereRaw('LOWER(TRIM(COALESCE(bordado, ""))) = ?', [strtolower(trim($bordado ?? ''))])
            ->whereRaw('LOWER(TRIM(COALESCE(numero, ""))) = ?', [strtolower(trim($numero ?? ''))]);

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
     * Extrae la primera palabra de la categoría y la convierte a singular.
     * Ejemplo: "PANTALONES DE DAMA" -> "PANTALON", "ZAPATOS CABALLERO" -> "ZAPATO", "BOTAS DAMA" -> "BOTA"
     */
    public static function obtenerPrimeraPalabraSingular(?string $categoria, string $fallback = 'ZAPATO'): string
    {
        if (empty($categoria)) {
            return $fallback;
        }

        $cat = trim(preg_replace('/\s+/', ' ', $categoria));
        $palabras = explode(' ', $cat);
        $primera = trim($palabras[0] ?? '');

        if (empty($primera)) {
            return $fallback;
        }

        $palabraMayus = strtoupper(str_replace(
            ['Á','É','Í','Ó','Ú','á','é','í','ó','ú'],
            ['A','E','I','O','U','A','E','I','O','U'],
            $primera
        ));

        $mapaDirecto = [
            'PANTALONES'  => 'PANTALON',
            'PANTALÓN'    => 'PANTALON',
            'PANTALON'    => 'PANTALON',
            'ZAPATOS'     => 'ZAPATO',
            'ZAPATO'      => 'ZAPATO',
            'BOTAS'       => 'BOTA',
            'BOTA'        => 'BOTA',
            'BOTINES'     => 'BOTIN',
            'BOTÍN'       => 'BOTIN',
            'BOTIN'       => 'BOTIN',
            'MOCASINES'   => 'MOCASIN',
            'MOCASÍN'     => 'MOCASIN',
            'MOCASIN'     => 'MOCASIN',
            'TENIS'       => 'TENIS',
            'SANDALIAS'   => 'SANDALIA',
            'SANDALIA'    => 'SANDALIA',
            'ZAPATILLAS'  => 'ZAPATILLA',
            'ZAPATILLA'   => 'ZAPATILLA',
            'CHANCLAS'    => 'CHANCLA',
            'CHANCLA'     => 'CHANCLA',
            'HUARACHES'   => 'HUARACHE',
            'HUARACHE'    => 'HUARACHE',
            'BLUSAS'      => 'BLUSA',
            'BLUSA'       => 'BLUSA',
            'PLAYERAS'    => 'PLAYERA',
            'PLAYERA'     => 'PLAYERA',
            'CAMISAS'     => 'CAMISA',
            'CAMISA'      => 'CAMISA',
            'CAMISETAS'   => 'CAMISETA',
            'CAMISETA'    => 'CAMISETA',
            'SUDADERAS'   => 'SUDADERA',
            'SUDADERA'    => 'SUDADERA',
            'CHAMARRAS'   => 'CHAMARRA',
            'CHAMARRA'    => 'CHAMARRA',
            'VESTIDOS'    => 'VESTIDO',
            'VESTIDO'     => 'VESTIDO',
            'FALDAS'      => 'FALDA',
            'FALDA'       => 'FALDA',
            'SHORTS'      => 'SHORT',
            'SHORT'       => 'SHORT',
            'JEANS'       => 'JEAN',
            'JEAN'        => 'JEAN',
            'SACOS'       => 'SACO',
            'SACO'        => 'SACO',
            'ABRIGOS'     => 'ABRIGO',
            'ABRIGO'      => 'ABRIGO',
            'CHALECOS'    => 'CHALECO',
            'CHALECO'     => 'CHALECO',
            'SUETERES'    => 'SUETER',
            'SUÉTERES'    => 'SUETER',
            'SUETER'      => 'SUETER',
            'CALCETINES'  => 'CALCETIN',
            'CALCETÍN'    => 'CALCETIN',
            'CALCETIN'    => 'CALCETIN',
            'CINTURONES'  => 'CINTURON',
            'CINTURÓN'    => 'CINTURON',
            'CINTURON'    => 'CINTURON',
            'MEDIAS'      => 'MEDIA',
            'MEDIA'       => 'MEDIA',
            'TRAJES'      => 'TRAJE',
            'TRAJE'       => 'TRAJE',
            'CONJUNTOS'   => 'CONJUNTO',
            'CONJUNTO'    => 'CONJUNTO',
            'OVEROLES'    => 'OVEROL',
            'OVEROL'      => 'OVEROL',
            'PIJAMAS'     => 'PIJAMA',
            'PIYAMAS'     => 'PIJAMA',
            'PIJAMA'      => 'PIJAMA',
            'GORRAS'      => 'GORRA',
            'GORRA'       => 'GORRA',
            'SOMBREROS'   => 'SOMBRERO',
            'SOMBRERO'    => 'SOMBRERO',
            'TACOS'       => 'TACO',
            'TACO'        => 'TACO',
            'TAQUETES'    => 'TAQUETE',
            'TAQUETE'     => 'TAQUETE',
            'ZUECOS'      => 'ZUECO',
            'ZUECO'       => 'ZUECO',
            'PANTUFLAS'   => 'PANTUFLA',
            'PANTUFLA'    => 'PANTUFLA',
            'BERMUDAS'    => 'BERMUDA',
            'BERMUDA'     => 'BERMUDA',
            'TOPS'        => 'TOP',
            'TOP'         => 'TOP',
            'BODIES'      => 'BODY',
            'BODY'        => 'BODY',
            'LEGGINGS'    => 'LEGGING',
            'LEGGING'     => 'LEGGING',
            'CORSETES'    => 'CORSET',
            'CORSET'      => 'CORSET',
            'MOCHILAS'    => 'MOCHILA',
            'MOCHILA'     => 'MOCHILA',
            'BOLSAS'      => 'BOLSA',
            'BOLSA'       => 'BOLSA',
            'CARTERAS'    => 'CARTERA',
            'CARTERA'     => 'CARTERA',
            'LENTES'      => 'LENTES',
            'GAFAS'       => 'GAFAS',
            'RELOJES'     => 'RELOJ',
            'RELOJ'       => 'RELOJ',
        ];

        if (isset($mapaDirecto[$palabraMayus])) {
            return $mapaDirecto[$palabraMayus];
        }

        $len = strlen($palabraMayus);

        if (in_array($palabraMayus, ['TENIS', 'GAFAS', 'LENTES', 'PARAGUAS', 'CORTAUÑAS'])) {
            return $palabraMayus;
        }

        if (str_ends_with($palabraMayus, 'ONES') && $len > 4) {
            return substr($palabraMayus, 0, -4) . 'ON';
        }

        if (str_ends_with($palabraMayus, 'INES') && $len > 4) {
            return substr($palabraMayus, 0, -4) . 'IN';
        }

        if (str_ends_with($palabraMayus, 'ERES') && $len > 4) {
            return substr($palabraMayus, 0, -4) . 'ER';
        }

        if (preg_match('/(AL|OL|EL|UL)ES$/', $palabraMayus) && $len > 4) {
            return substr($palabraMayus, 0, -2);
        }

        if (str_ends_with($palabraMayus, 'CES') && $len > 3) {
            return substr($palabraMayus, 0, -3) . 'Z';
        }

        if (str_ends_with($palabraMayus, 'ES') && $len > 4) {
            return substr($palabraMayus, 0, -2);
        }

        if (str_ends_with($palabraMayus, 'S') && $len > 3) {
            return substr($palabraMayus, 0, -1);
        }

        return $palabraMayus;
    }

    /**
     * Retorna la Descripción formateada para reportes y Excel.
     */
    public function getDescripcionCompletaAttribute(): string
    {
        $tipo = self::obtenerPrimeraPalabraSingular($this->categoria, 'ZAPATO');
        $desc = "{$tipo} ESTILO {$this->estilo} {$this->material} {$this->color}";
        if (!empty($this->bordado)) {
            $desc .= " BORDADO {$this->bordado}";
        }
        $desc .= " TALLA {$this->numero}";
        return strtoupper(str_replace(['Á','É','Í','Ó','Ú','á','é','í','ó','ú'], ['A','E','I','O','U','A','E','I','O','U'], $desc));
    }

    /**
     * Retorna la Descripción formateada para SICAR con toda la información disponible.
     */
    public function getDescripcionSicarAttribute(): string
    {
        $tipo = self::obtenerPrimeraPalabraSingular($this->categoria, 'CALZADO');
        $parts = [$tipo];

        if (!empty($this->estilo)) {
            $parts[] = "ESTILO {$this->estilo}";
        }
        if (!empty($this->material)) {
            $parts[] = "MATERIAL {$this->material}";
        }
        if (!empty($this->color)) {
            $parts[] = "COLOR {$this->color}";
        }
        if (!empty($this->bordado)) {
            $parts[] = "BORDADO {$this->bordado}";
        }
        if (!empty($this->numero)) {
            $parts[] = "NUMERO {$this->numero}";
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
     * Genera el prefijo de la Clave SICAR basado en el nombre de la categoría de calzado.
     * Ejemplo: "Zapato Escolar" => "ZAPATOESC", "TENIS DE NIÑA" => "TENISNIN"
     */
    public static function generarPrefijoCategoria(?string $categoria, string $fallback = 'CALZADO'): string
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
     * Formato: PREFIJO + 8 dígitos (ej. ZAPATOESC00000001)
     */
    public static function generarSiguienteClave(?string $categoria, ?int $ignoreId = null): string
    {
        $prefix = self::generarPrefijoCategoria($categoria, 'CALZADO');
        
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
     * Obtiene la clave persistente del calzado o la genera y guarda si no la tiene aún.
     * Una vez asignada, NUNCA cambia para evitar inconsistencias en SICAR.
     */
    public function obtenerOAsignarClave(): string
    {
        if (!empty($this->clave)) {
            return $this->clave;
        }

        $nuevaClave = self::generarSiguienteClave($this->categoria ?? 'CALZADO', $this->id);
        $this->clave = $nuevaClave;
        $this->save();

        return $nuevaClave;
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
