<?php
namespace App\Services;

use App\Models\ApprovedProcesso;
use App\Models\GruposProcesso;
use App\Models\WorkFlow;
use Illuminate\Support\Facades\DB;

class ApprovedAccounts
{
    public function calculateApprovedAccounts($accounts)
    {
        $approved_process = [];
        foreach($accounts as $account){
            $distinctCombinations = DB::table('approved_processo')
                ->select('id_usuario', 'id_grupo')
                ->where('id_processo', $account->id)
                ->where('id_processo_vencimento_valor', $account->pvv_id)
                ->distinct();

            $accountApproved = DB::table(DB::raw("({$distinctCombinations->toSql()}) as distinct_combinations"))
                ->mergeBindings($distinctCombinations) // Necessário para mesclar os bindings da subquery
                ->count();

            $workflow = WorkFlow::select('id_grupos')
                ->where('id', $account->p_workflow_id)
                ->first();

            $accountWorkflowGroupsCount = count(json_decode($workflow->id_grupos));

            if ($accountWorkflowGroupsCount > 0) {
                $approval_progress[$account->id." ".date('Y-m-d', strtotime($account->pvv_dtv))] = [
                    'approved' => $accountApproved,
                    'total' => $accountWorkflowGroupsCount,
                    'percentual' => ($accountApproved / $accountWorkflowGroupsCount) * 100
                ];
            } else {
                 $approval_progress[$account->id." ".date('Y-m-d', strtotime($account->pvv_dtv))] = [
                    'approved' => $accountApproved,
                    'total' => $accountWorkflowGroupsCount,
                    'percentual' => ($accountApproved / $accountWorkflowGroupsCount) * 100
                ];
            }
        }
        return $approval_progress;
    }

    public function verifyMyApproved($accounts)
    {

        foreach ($accounts as $account) {
            $approvedProcess[] = $this->getApprovedAccount($account->p_workflow_id, $account->id, $account->pvv_id);
        }
        foreach($approvedProcess as $approved){
            if($approved != null){
                foreach($approved as $app){
                    if($app != null){
                        foreach($app as $a){
                            $approvedArray[] = $a;
                        }
                    }
                }
            }
        }
        return $approvedArray;
    }

    public function getApprovedAccount($processWorkflowId, $id_processo, $id_processo_vencimento_valor)
    {
        foreach(json_decode(auth()->user()->id_grupos) as $id_grupo){
            $approved = ApprovedProcesso::select(
                'approved_processo.id_processo as id_processo',
                'approved_processo.id_processo_vencimento_valor as id_processo_vencimento_valor',
                'users.name as u_nome',
                'grupo_processos.id as id',
                'grupo_processos.nome as nome',
                'approved_processo.created_at'
            )
                ->leftJoin('grupo_processos', 'approved_processo.id_grupo', 'grupo_processos.id')
                ->leftJoin('users', 'users.id', 'approved_processo.id_usuario')
                ->where('grupo_processos.id', $id_grupo)
                ->where('approved_processo.id_processo', $id_processo)
                ->where('approved_processo.id_processo_vencimento_valor', $id_processo_vencimento_valor)
                ->get();
            if($approved->count() > 0){
                $approvedArray[] = $approved->toArray();
            }else{
                $approvedArray[] = null;
            }
        }
        return $approvedArray;
    }

    public function getGroupsForApproval($processWorkflowId)
    {
        $groups = GruposProcesso::select('id', 'nome')
            ->whereIn('id', json_decode(WorkFlow::select('id_grupos')->where('id', $processWorkflowId)->first()->id_grupos))
            ->get();
        return $groups;
    }

    public function getApprovalsForGroup($groups, $processId, $pvvId)
    {
        foreach($groups as $group){
            $approvals[] = ApprovedProcesso::select(
                'approved_processo.id_processo as id_processo',
                'approved_processo.id_processo_vencimento_valor as id_processo_vencimento_valor',
                'users.name as u_nome',
                'grupo_processos.id as id_grupo',
                'grupo_processos.nome as nome',
                'approved_processo.created_at'
            )
                ->leftJoin('grupo_processos', 'approved_processo.id_grupo', 'grupo_processos.id')
                ->leftJoin('users', 'users.id', 'approved_processo.id_usuario')
                ->where('grupo_processos.id', $group->id)
                ->where('approved_processo.id_processo', $processId)
                ->where('approved_processo.id_processo_vencimento_valor', $pvvId)
                ->get();
        }
        return $approvals;
    }
}
