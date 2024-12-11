<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Helpers\FormatUtils;
use App\Mail\ProximoVencimento;
use App\Models\Empresa;
use App\Models\Messages;
use App\Models\Processo;
use App\Models\Setup;
use App\Models\User;
use Illuminate\Support\Facades\Mail;

class ProcessoProximoVencimento extends Command
{
    public $empresas;

    public function __construct()
    {
        parent::__construct();
    }
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:processo-proximo-vencimento';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $empresas = Empresa::all();
        foreach($empresas as $empresa){
            $setup = Setup::where('id_empresa', $empresa->id)->first();
            if($setup !== null){
                FormatUtils::formatStrLog("Verificando vencimentos proximos da empresa: %s dias antes do vencimento: %d", 'proximo vencimento',[
                    $empresa->nome,
                    $setup->dias_antes_vencimento
                ]);
                $faturas = $this->getProximoVencimento($setup->dias_antes_vencimento, $empresa->id);
                if($faturas['result'] == false){
                    FormatUtils::formatStrLog("Não foi possivel encontrar usuarios financeiros para a empresa: %s", 'proximo vencimento', [
                        $empresa->nome
                    ]);
                    continue;
                }
                $this->enviarRelatorioVencimentos($faturas);
                $this->alertProximoVencimento($faturas);
            }
        }
    }

    public function alertProximoVencimento(array|object $array)
    {
        FormatUtils::formatStrLog("Enviando alertas para exibição na web", "proximo vencimento", []);
        foreach($array['result']['processos'] as $processo){
            foreach($array['result']['email'] as $email){
                Messages::create([
                    'id_usuario' => $email->id,
                    'id_empresa' => $email->id_empresa,
                    'id_processo' => $processo->id,
                    'trace_code' => $processo->trace_code,
                    'message' => "Processo com numero de rastreio {$processo->trace_code} esta proximo do vencimento"
                ]);
            }
        }
    }


    public function enviarRelatorioVencimentos(array|object $array)
    {
        $emails = $array['result']['email']->pluck('email');
        $to = $emails->toArray();
        $strEmail = implode(',', $to);
        foreach($array['result']['processos'] as $processo){
            FormatUtils::formatStrLog("Enviando os emails %s", "proximo vencimento",[$strEmail]);
            $dados[] = [
                'trace_code' => $processo->trace_code,
                'vencimento' => $processo->pvv_dtv,
                'id' => $processo->id,
                'updated_at' => date('d/m/Y H:i:s', strtotime($processo->updated_at_pvv))
            ];
            $mail = new ProximoVencimento($dados);
            Mail::to($to)->send($mail);
        }
    }

    public function getProximoVencimento(int $diasVencimento, int $idEmpresa)
    {
        $processo = new Processo();
        $financeiro = User::select('id', 'id_empresa', 'email')->where('id_empresa', $idEmpresa)->where('financeiro', true)->get();
        $vencendo = $processo->getProximoVencimento($diasVencimento, $idEmpresa);
        if($financeiro->count() == 0){
            return ['result' => false];
        }
        return ['result' => ['email' => $financeiro, 'processos' => $vencendo]];
    }
}
