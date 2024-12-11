<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Queue\Worker;
use Illuminate\Support\Facades\DB;

class Empresa extends Model
{
    use HasFactory;
    protected $table = 'empresa';
    protected $fillable = [
        'cpf_cnpj',
        'nome',
        'razao_social',
        'endereco',
        'numero',
        'complemento',
        'bairro',
        'cep',
        'cidade',
        'token',
        'expiration_time',
        'observacao'
    ];

    public function workflowPorEmpresa($id)
    {
        return WorkFlow::select(
                'workflow.id as w_id',
                'workflow.nome as w_nome',
                'empresa.id as e_id',
                'empresa.nome as e_nome',
                'grupo_processos.id as gp_id',
                'grupo_processos.nome as gp_nome'
            )
            ->leftJoin('grupo_processos', function ($join){
                $join->on(
                     DB::raw("JSON_UNQUOTE(JSON_EXTRACT(workflow.id_grupos, '$[0]'))"),
                        '=',
                        'grupo_processos.id'
                );
            })
            ->leftJoin('empresa', 'grupo_processos.id_empresa', 'empresa.id')
            ->where('empresa.id', $id)
            ->get();
    }

    public function gruposPorEmpresa($id)
    {
        return Empresa::select('empresa.*', 'grupo_processos.id as gp_id', 'grupo_processos.nome AS gp_nome', 'workflow.id as w_id'
            ,'workflow.nome AS w_nome')
            ->leftJoin('grupo_processos', 'grupo_processos.id_empresa', '=', 'empresa.id')
            ->leftJoin('workflow', DB::raw("JSON_UNQUOTE(JSON_EXTRACT(workflow.id_grupos, '$[0]'))"), '=', 'grupo_processos.id')
            ->where('empresa.id', $id)
            ->get();
    }
}
