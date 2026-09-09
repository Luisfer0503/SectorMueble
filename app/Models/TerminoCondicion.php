<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class TerminoCondicion extends Model
{
    use HasFactory;

    protected $table = 'terminos_condiciones';

    protected $fillable = [
        'contenido',
    ];

    /**
     * Obtener el contenido activo de Términos y Condiciones
     */
    public static function obtenerContenido(): string
    {
        $registro = self::find(1);
        if ($registro && !empty($registro->contenido)) {
            return $registro->contenido;
        }

        return "Términos y Condiciones de Sector Mueble.";
    }
}
