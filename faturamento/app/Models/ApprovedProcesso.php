<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ApprovedProcesso extends Model
{
    use HasFactory;
    protected $table = 'approved_processo';
    protected $fillable = [
        'id_processo',
        'id_grupo',
        'id_usuario',
        'id_processo_vencimento_valor'
    ];
}
