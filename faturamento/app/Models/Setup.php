<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Setup extends Model
{
    use HasFactory;
    protected $table = 'setup';
    protected $fillable = [
        'id_empresa',
        'dias_antes_vencimento',
        'dias_sem_movimentacao',
        'exige_ordem_processo',
        'dias_vencimento_maximo',
        'logo',
    ];
}
