<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ZapatoCategoria extends Model
{
    use HasFactory;

    protected $table = 'zapato_categorias';

    protected $fillable = [
        'nombre',
    ];
}
