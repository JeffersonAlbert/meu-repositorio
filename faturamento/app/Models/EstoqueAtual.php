<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EstoqueAtual extends Model
{
    use HasFactory;

    protected $table = 'estoque_atual';

    protected $fillable = [
        'produto_id',
        'quantidade',
    ];

}
