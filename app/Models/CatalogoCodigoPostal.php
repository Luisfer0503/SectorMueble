<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CatalogoCodigoPostal extends Model
{
    use HasFactory;

    protected $table = 'catalogo_codigos_postales';

    protected $fillable = [
        'codigo_postal',
        'estado',
        'municipio',
        'zona_cobertura',
        'activo',
    ];

    protected $casts = [
        'activo' => 'boolean',
    ];
}
