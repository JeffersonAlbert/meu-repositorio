<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReceberVencimentoValor extends Model
{
    use HasFactory;
    protected $table = 'receber_vencimento_valor';
    protected $fillable = [
        'id_contas_receber',
        'vencimento',
        'valor',
        'status',
        'id_usuario',
        'juros',
        'multa',
        'desconto',
        'observacao',
        'id_bank'
    ];
}
