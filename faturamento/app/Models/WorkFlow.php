<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WorkFlow extends Model
{
    use HasFactory;
    protected $table = 'workflow';
    protected $fillable = [
        'nome',
        'id_grupos'
    ];

    public function getWorkFlow($id_empresa = null)
    {
        return WorkFlow::select(
            'workflow.id as id',
            'workflow.nome as nome',
            GruposProcesso::raw("GROUP_CONCAT(grupo_processos.nome SEPARATOR ',') as grupos"),
            GruposProcesso::raw("GROUP_CONCAT(grupo_processos.id SEPARATOR ',') as id_grupos")
        )
            ->leftJoin('grupo_order_fluxo as gof', 'workflow.id', '=', 'gof.id_fluxo')
            ->leftJoin('grupo_processos', 'gof.id_grupo', '=', 'grupo_processos.id')
            ->where('gof.ativo', '=', true)
            ->where('grupo_processos.id_empresa', isset($id_empresa) ? $id_empresa : auth()->user()->id_empresa)
            ->groupBy('workflow.id', 'workflow.nome')
            ->get();
    }

    public function getWorkFlowById(string $id)
    {
        return WorkFlow::select(
            'workflow.id as id',
            'workflow.nome as nome',
            GruposProcesso::raw('GROUP_CONCAT(grupo_processos.id SEPARATOR ",") as grupos')
        )
            ->leftJoin('grupo_order_fluxo as gof', 'workflow.id', '=', 'gof.id_fluxo')
            ->leftJoin('grupo_processos', 'gof.id_grupo', '=', 'grupo_processos.id')
            ->where('gof.ativo', '=', true)
            ->groupBy('workflow.id', 'workflow.nome')
            ->find($id);
    }

    public function getWorkFlowList($queryWorkflow, $pageWorkflowSearch, $limitWorkflow)
    {
        $workflow =  WorkFlow::select(
            'workflow.id as id',
            'workflow.nome as nome',
            GruposProcesso::raw("GROUP_CONCAT(grupo_processos.nome SEPARATOR ',') as grupos"),
            GruposProcesso::raw("GROUP_CONCAT(grupo_processos.id SEPARATOR ',') as id_grupos")
        )
            ->leftJoin('grupo_order_fluxo as gof', 'workflow.id', '=', 'gof.id_fluxo')
            ->leftJoin('grupo_processos', 'gof.id_grupo', '=', 'grupo_processos.id')
            ->where('gof.ativo', '=', true)
            ->where('grupo_processos.id_empresa', auth()->user()->id_empresa)
            ->groupBy('workflow.id', 'workflow.nome')
            ->where('workflow.nome', 'like', "%$queryWorkflow%")
            ->skip(($pageWorkflowSearch -1) * $limitWorkflow)
            ->take($limitWorkflow)
            ->get();

        if($workflow->count() == 0){
            return [['id' => null, 'nome' => 'Nenhum workflow encontrado']];
        }
        if($workflow->count() >= 1 and $pageWorkflowSearch == 1){
            return $workflow->toArray();
        }
        if($workflow->count() >= 1 and $pageWorkflowSearch > 1){
            return $workflow->toArray();
        }
    }
}
