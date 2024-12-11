<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PendenciaProcesso extends Model
{
    use HasFactory;
    protected $table = 'processo_pendencia';
    protected $fillable = [
        'id_processo',
        'observacao',
        'id_usuario_email',
        'id_processo_vencimento_valor',
        'id_usuario'
    ];

    public function getObservacaoPendenciaByProcessoId($id){
        return PendenciaProcesso::select('processo_pendencia.created_at', 'processo_pendencia.observacao', 'users.name as name')
        ->leftJoin('users', 'users.id', 'processo_pendencia.id_usuario')
        ->where('processo_pendencia.id_processo', $id)
        ->orderBy('created_at', 'desc')
        ->get();
    }

}
