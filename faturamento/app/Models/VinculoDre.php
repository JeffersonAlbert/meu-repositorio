<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VinculoDre extends Model
{
    use HasFactory;

    protected $table = 'vinculo_dre';
    protected $fillable = [
        'descricao',
        'tipo'
    ];
}
