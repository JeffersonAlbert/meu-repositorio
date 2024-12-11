<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pagamentos extends Model
{
    use HasFactory;
    protected $table = 'pagamentos';
    protected $fillable = [
        'data_pagamento',
        'valor_pago',
        'forma_pagamento',
        'id_banco',
        'id_processo',
        'id_processo_vencimento_valor',
        'id_empresa',
        'observacao',
        'juros',
        'multa',
        'desconto',
    ];
}
