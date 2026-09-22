<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PoliticaEnvio extends Model
{
    use HasFactory;

    protected $table = 'politicas_envio';

    protected $fillable = [
        'contenido',
    ];

    /**
     * Obtener el contenido activo de Políticas de Envío como texto
     */
    public static function obtenerContenido(): string
    {
        $registro = self::find(1);
        if ($registro && !empty($registro->contenido)) {
            return $registro->contenido;
        }

        return "Políticas de Envío de Sector Mueble.";
    }

    /**
     * Obtener lista estructurada de secciones de Políticas de Envío.
     * Retorna array de secciones: [ ['titulo' => '...', 'contenido' => '...'], ... ]
     */
    public static function obtenerSecciones(): array
    {
        $registro = self::find(1);
        if ($registro && !empty($registro->contenido)) {
            $decoded = json_decode($registro->contenido, true);
            if (is_array($decoded) && !empty($decoded)) {
                return $decoded;
            }
        }

        return self::seccionesPorDefecto();
    }

    /**
     * Secciones iniciales estructuradas por defecto para Sector Mueble
     */
    public static function seccionesPorDefecto(): array
    {
        return [
            [
                'titulo' => '1. Cobertura de Entrega y Envíos Nacionales',
                'contenido' => "En SECTOR MUEBLE realizamos envíos de muebles y decoración a toda la República Mexicana."
            ],
            [
                'titulo' => '2. Costos de Envío y Promoción de Envío Gratuito',
                'contenido' => "Los costos de flete se calculan en función de la ubicación de destino y el volumen total de la compra."
            ]
        ];
    }
}
