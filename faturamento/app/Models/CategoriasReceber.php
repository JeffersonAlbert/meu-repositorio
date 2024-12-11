<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CategoriasReceber extends Model
{
    use HasFactory;
    protected $table = 'categorias_receber';
    protected $fillable = [
        'categoria',
        'id_empresa',
        'descricao'
    ];
}
