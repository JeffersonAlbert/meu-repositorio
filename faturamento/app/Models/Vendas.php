<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vendas extends Model
{
    use HasFactory;

    protected $fillable = [
        'id_empresa',
        'dados_venda',
        'valor_total',
        'cliente_id',
        'user_id',
    ];
}
