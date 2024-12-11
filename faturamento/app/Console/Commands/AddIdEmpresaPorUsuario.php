<?php

namespace App\Console\Commands;

use App\Models\Processo;
use App\Models\User;
use Illuminate\Console\Command;

class AddIdEmpresaPorUsuario extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:add-id-empresa-por-usuario';

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
        $processoSemIdEmpresa = Processo::where('id_empresa', 0)->get();

        foreach($processoSemIdEmpresa as $processo){
            $idEmpresaPorUsuario = User::where('id', $processo->id_user)->first();
            $updateProcesso = Processo::where('id', $processo->id)->update([
                'id_empresa' => $idEmpresaPorUsuario->id_empresa
            ]);
            //dd($updateProcesso);
            echo $processo->id_user.PHP_EOL;
        }
    }
}
