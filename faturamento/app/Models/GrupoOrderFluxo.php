<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GrupoOrderFluxo extends Model
{
    use HasFactory;
    protected $table = 'grupo_order_fluxo';
    protected $fillable = [
        'id_grupo',
        'id_fluxo',
        'ativo',
        'id_user'
    ];
}
