<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ObservacaoProcesso extends Model
{
    use HasFactory;
    protected $table = 'observacao_processo';
    protected $fillable = [
        'id_processo',
        'id_usuario',
        'observacao',
    ];

    public function createObservacaoProcesso(string $id_processo, string $id_usuario, string $observacao)
    {
        return ObservacaoProcesso::create([
            'id_usuario' => $id_usuario,
            'id_processo' => $id_processo,
            'observacao' => $observacao
        ]);
    }

    public function getObservacaoProcesso(string $id)
    {
        return ObservacaoProcesso::select('observacao_processo.created_at', 'observacao_processo.observacao', 'users.name as name')
        ->leftJoin('users', 'users.id', 'observacao_processo.id_usuario')
        ->where('observacao_processo.id', $id)
        ->get();
    }

    public function getObservacaoProcessoByProcessoId($id){
        return ObservacaoProcesso::select('observacao_processo.created_at', 'observacao_processo.observacao', 'users.name as name')
        ->leftJoin('users', 'users.id', 'observacao_processo.id_usuario')
        ->where('observacao_processo.id_processo', $id)
        ->orderBy('created_at', 'desc')
        ->get();
    }
}
