<?php

namespace App\Console\Commands;

use App\Http\Controllers\SYS\R2Controller;
use App\Jobs\ProcessPdf;
use App\Models\Processo;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use function PHPUnit\Framework\isJson;

class AdjustProcessFile extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:adjust-process-file';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Adjust file for file_types_and_desc and number_of_pages and upload to R2 storage';

    /**
     * Execute the console command.
     */
    public function handle() : void
    {
        $process = Processo::whereNull('number_of_pages')->get();
        $r2 = new R2Controller();
        foreach($process as $p) {
            //$this->adjustDocToFileTypesAndDesc($p->doc_name);
            $processo = Processo::find($p->id);
            $fileTypeAndDesc = $this->adjustDocToFileTypesAndDesc($p->doc_name);
            $processo->files_types_desc = is_null($fileTypeAndDesc) ? null : $fileTypeAndDesc;
            $processo->save();

            echo "Processo {$p->id} ajustado\n";

            var_dump($fileTypeAndDesc);

            if($fileTypeAndDesc == "null"){
                echo "Processo {$p->id} não possui arquivos\n";
                continue;
            }

            if(isJson($fileTypeAndDesc)){
                $files = json_decode($fileTypeAndDesc);
                foreach($files as $file){
                    $contents = Storage::disk('public')->get('uploads/'.$file->fileName);
                    Storage::disk('r2')->put('uploads/'.$file->fileName, $contents);
                    echo "Arquivo {$file->fileName} enviado para o R2\n";
                }
                ProcessPdf::dispatch($p->id);
                continue;
            }
            break;
        }
    }

    public function adjustDocToFileTypesAndDesc($docName)
    {
        foreach(json_decode($docName) as $file){
            if($file !== 'teste.pdf'){
                $file_type_and_desc[] = [
                    'fileDesc' => null,
                    'fileName' => $file,
                    'fileType' => 'documento_fiscal'

                ];
            }else{
                $file_type_and_desc = null;
            }
        }
        return json_encode($file_type_and_desc);
    }
}
