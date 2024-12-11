<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Messages extends Model
{
    use HasFactory;

    protected $table = 'messages';
    protected $fillable = [
        'id_usuario',
        'id_empresa',
        'id_processo',
        'message',
        'id_processo',
        'trace_code',
        'visto'
    ];
}
