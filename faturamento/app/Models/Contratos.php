<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Contratos extends Model
{
    use HasFactory;
    protected $table = 'contratos';
    protected $fillable = [
        'nome',
        'id_empresa',
        'id_cliente',
        'id_fornecedor',
        'files',
        'inicio_contrato',
        'vigencia',
        'vigencia_inicial',
        'vigencia_final',
        'valor',
        'id_produtos'
    ];
}
