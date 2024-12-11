<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProcessoHistorico extends Model
{
    use HasFactory;
    protected $table = 'processo_historico';
    protected $fillable = [
        'id_usuario',
        'acao',
        'id_processo',
    ];

    public function createHistory(string $id, string $text)
    {
        return ProcessoHistorico::create([
            'acao' => $text,
            'id_usuario' => auth()->user()->id,
            'id_processo' => $id
        ]);
    }

    public function getHistory(string $id)
    {
        return ProcessoHistorico::select(
            'processo_historico.id',
            'users.name as u_name',
            'processo_historico.acao as historico',
            'processo_historico.created_at'
        )
        ->where('id_processo', $id)
        ->leftJoin('users', 'users.id', 'processo_historico.id_usuario')
        ->orderBy('created_at', 'desc')
        ->get();
    }

}
