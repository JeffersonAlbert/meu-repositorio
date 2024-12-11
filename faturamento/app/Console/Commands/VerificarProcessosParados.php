<?php

namespace App\Console\Commands;

use App\Helpers\FormatUtils;
use App\Mail\ProcessoParado;
use App\Models\Empresa;
use App\Models\Messages;
use App\Models\Processo;
use App\Models\Setup;
use App\Models\User;
use App\Models\WorkFlow;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class VerificarProcessosParados extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:verificar-processos-parados';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    public function __construct()
    {
        parent::__construct();
    }


    /**
     * Execute the console command.
     */
    public function handle()
    {
        $empresas = Empresa::all();
        foreach($empresas as $empresa){
            $setup = Setup::where('id_empresa', $empresa->id)->first();
            if($setup !== null) {
                FormatUtils::formatStrLog("Quantidade de registros do setup: %d", 'processo-inativo', [$setup->count()]);
                if($setup->dias_sem_movimentacao !== null){
                    $colecao = $this->getInativos([
                        'diasInativo' => $setup->dias_sem_movimentacao,
                        'diasMaximoVencimento' => $setup->dias_maximo_vencimento
                    ], $empresa->id);
                    if($colecao !== null){
                        $gruposOrdernados = $this->ordenarColecaoPorWorkflowId($colecao);
                        $this->enviaEmailParaGrupos($gruposOrdernados);
                        $this->alertaInativos($gruposOrdernados, $empresa->id, $setup->dias_sem_movimentacao);
                    }
                }
            }
        }

    }

    public function alertaInativos($array, $id_empresa, $diasInativo)
    {
        foreach($array as $key => $grupo){
            FormatUtils::formatStrLog("Id's dos usuarios: %s", 'processo-inativo', $grupo[$key]->id_user);
            $arrayIdUser = explode(",", $grupo[$key]->id_user);
            foreach($grupo as $data){
                foreach($arrayIdUser as $idUser){
                    Messages::create([
                        'id_usuario' => $idUser,
                        'id_empresa' => $id_empresa,
                        'id_processo' => $data->id,
                        'trace_code' => $data->trace_code,
                        'message' => "Processo com numero de rastreio {$data->trace_code} esta inativo a mais de {$diasInativo}",
                    ]);
                }
            }
        }
        return;
    }

    public function enviaEmailParaGrupos($gruposOrdenados)
    {
        foreach($gruposOrdenados as $key => $grupo){
            FormatUtils::formatStrLog("Emails: %s", 'processo-inativo', [$grupo[0]->emails]);
            $to = explode(";", $grupo[0]->emails);
            foreach($grupo as $data){
                $dados[] = [
                    'trace_code' => $data->trace_code,
                    'vencimento' => $data->pvv_dtv,
                    'id' => $data->id,
                    'updated_at' => date('d/m/Y H:i:s', strtotime($data->updated_at_pvv))
                ];
            }
            $mail = new ProcessoParado($dados);
            Mail::to($to)->send($mail);
        }
        return;
    }

    public function ordenarColecaoPorWorkflowId($array)
    {
        $grupos = [];
        foreach ($array as $processos) {
            foreach($processos as $data){
                $pWorkflowId = $data["p_workflow_id"];
                if (!isset($grupos[$pWorkflowId])) {
                    $grupos[$pWorkflowId] = [];
                }
                $grupos[$pWorkflowId][] = $data;
            }
        }
        return $grupos;
    }

    public function getInativos(array $arrayDias, int $id_empresa)
    {
        $instanceGrupos = new WorkFlow();
        $grupos = $instanceGrupos->getWorkFlow($id_empresa);
        FormatUtils::formatStrLog("Quantidade de grupos: %d", 'processo-inativo', [$grupos->count()]);
        foreach($grupos as $grupo){
            $inativos[] = Processo::getProcessoInativos($arrayDias, ['id_empresa' => $id_empresa, 'id_grupos' => $grupo->id_grupos]);
        }
        if($inativos == null){
            return null;
        }
        FormatUtils::formatStrLog("Quantidade de inativos: %d", 'processo-inativo', [count($inativos)]);
        $colecaoSemDuplicatas = collect($inativos)->unique('id')->values()->all();

        return $colecaoSemDuplicatas;
    }
}
