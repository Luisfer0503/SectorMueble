<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RopaCategoria extends Model
{
    use HasFactory;

    protected $table = 'ropa_categorias';

    protected $fillable = [
        'nombre',
    ];
}
