<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Zapato extends Model
{
    use HasFactory;

    protected $table = 'zapatos';

    protected $fillable = [
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
