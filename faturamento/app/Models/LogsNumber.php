<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LogsNumber extends Model
{
    use HasFactory;

    protected $table = 'logs_number';
    protected $fillable = [
        'id_empresa',
        'message',
    ];
}
