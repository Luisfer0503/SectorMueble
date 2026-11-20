<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SicarDescarga extends Model
{
    use HasFactory;

    protected $table = 'sicar_descargas';

    protected $fillable = [
        'tipo',
        'categoria',
        'fecha',
        'fecha_hora',
        'total_articulos',
        'filas',
    ];

    protected $casts = [
        'fecha'           => 'date',
        'fecha_hora'      => 'datetime',
        'total_articulos' => 'integer',
        'filas'           => 'array',
    ];
}
