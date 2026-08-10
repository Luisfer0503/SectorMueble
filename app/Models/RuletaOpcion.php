<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RuletaOpcion extends Model
{
    use HasFactory;

    protected $table = 'ruleta_opciones';

    protected $fillable = [
        'posicion',
        'titulo',
        'codigo_cupon',
        'tipo_descuento',
        'descuento_valor',
        'tiempo_minutos',
        'color_bg',
        'activo',
    ];

    protected $casts = [
        'descuento_valor' => 'float',
        'tiempo_minutos' => 'integer',
        'activo' => 'boolean',
    ];
}
