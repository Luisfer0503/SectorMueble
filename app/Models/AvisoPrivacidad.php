<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AvisoPrivacidad extends Model
{
    use HasFactory;

    protected $table = 'avisos_privacidad';

    protected $fillable = [
        'contenido',
    ];

    /**
     * Obtener el contenido activo del Aviso de Privacidad como texto
     */
    public static function obtenerContenido(): string
    {
        $registro = self::find(1);
        if ($registro && !empty($registro->contenido)) {
            return $registro->contenido;
        }

        return "Aviso de Privacidad de Sector Mueble.";
    }

    /**
     * Obtener lista estructurada de secciones de Aviso de Privacidad.
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
                'titulo' => '1. Identidad y Domicilio del Responsable',
                'contenido' => "CASA TAPICERÍA Y ATELIER S.A. de C.V. (en adelante \"SECTOR MUEBLE\"), con domicilio en Calle Lago de Chapala 105, Int. 4, Col. Manantiales, C.P. 72760, San Pedro Cholula, Puebla, con RFC CTA2209268QA, es el responsable del tratamiento y protección de sus datos personales."
            ],
            [
                'titulo' => '2. Datos Personales Recabados',
                'contenido' => "Para llevar a cabo las finalidades descritas en el presente Aviso de Privacidad, recabaremos datos de identificación, datos de contacto y datos necesarios para facturación y entrega."
            ]
        ];
    }
}
