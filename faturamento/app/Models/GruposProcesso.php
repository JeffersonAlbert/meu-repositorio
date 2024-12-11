<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GruposProcesso extends Model
{
    use HasFactory;
    protected $table = 'grupo_processos';
    protected $fillable = [
        'nome',
        'id_empresa',
        'criar_usuario',
        'move_processo',
        'deleta_processo',
        'criar_fluxo',
    ];
}
