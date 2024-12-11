<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Filial extends Model
{
    use HasFactory;
    protected $table = "filial";
    protected $fillable = [
        'cnpj',
        'id_empresa',
        'inscricao_estadual',
        'nome',
        'razao_social',
        'cep',
        'endereco',
        'cidade',
        'numero',
        'complemento',
        'bairro',
        'observacao'
    ];
}
