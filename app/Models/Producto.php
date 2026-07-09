<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Producto extends Model
{
    use HasFactory;

    protected $table = 'productos';

    protected $fillable = [
        'nombre',
        'slug',
        'descripcion',
        'precio',
        'imagen_url',
        'categoria',
        'stock',
        'calificacion',
        'destacado',
    ];

    protected $casts = [
        'precio' => 'decimal:2',
        'calificacion' => 'decimal:1',
        'destacado' => 'boolean',
        'stock' => 'integer',
    ];
}
