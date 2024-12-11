<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DRE extends Model
{
    use HasFactory;
    protected $table = 'dre';
    protected $fillable = [
        'nome',
        'tipo',
        'codigo',
        'id_empresa',
        'id_usuario',
        'editable'
    ];
}
