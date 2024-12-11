<?php

namespace App\Console\Commands;

use App\Models\Processo;
use Illuminate\Console\Command;

class AdjustFileNameDescription extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:adjust-file-name-description';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Comando para pegar arquivos antigos e colocar da forma correta na coluna correta';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $processoInstance = new Processo();
        $processos = $processoInstance->all();

        foreach($processos as $processo){
            echo "{$processo->id} {$processo->doc_name}".PHP_EOL;
            $newJsonFile = [];
            foreach(json_decode($processo->doc_name) as $docName){
                $newJsonFile[] = [
                    'fileName' => $docName,
                    'fileType' => 'documento_fiscal',
                    'fileDesc' => 'Sem descrição'
                ];
            }
            echo "nomes arquivos".PHP_EOL,
            print_r(json_encode($newJsonFile));
            echo PHP_EOL."limpando variavel".PHP_EOL;
            $processoInstance->where('id', $processo->id)
            ->whereNull('files_types_desc')
            ->update([
                'files_types_desc' => json_encode($newJsonFile),
            ]);
            unset($newJsonFile);
        }
    }
}
