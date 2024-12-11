<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProcessoVencimentoValor extends Model
{
    use HasFactory;
    protected $table = 'processo_vencimento_valor';
    protected $fillable = [
        'id_processo',
        'data_vencimento',
        'price',
        'pago'
    ];
}
