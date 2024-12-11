<?php

namespace App\Jobs;

use App\Helpers\UploadFiles;
use App\Http\Controllers\SYS\PdfController;
use App\Http\Controllers\SYS\R2Controller;
use App\Models\Processo;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Spatie\PdfToImage\Pdf;

class ProcessPdf implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $pdfId;
    protected $upload;
    public $timeout = 3600;
   // public $tries = 1000; // Exemplo: tenta 5 vezes

    /**
     * Create a new job instance.
     */
    public function __construct($pdfId)
    {
        $this->pdfId = $pdfId;
        $this->upload = new UploadFiles();
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $process = Processo::find($this->pdfId);
        if(!$process) {
            return;
        }

        $files = json_decode($process->files_types_desc);

        $tempPdfPath = sys_get_temp_dir() . '/pdf_' . uniqid();
        $tempImagePath = sys_get_temp_dir() . '/image_' . uniqid();
        is_dir($tempPdfPath) ? null : mkdir($tempPdfPath);
        is_dir($tempImagePath) ? null : mkdir($tempImagePath);

        foreach($files as $file){
            $pdfContent = Storage::disk('r2')->get('uploads/'.$file->fileName);
            $pdContentType = Storage::disk('r2')->mimeType('uploads/'.$file->fileName);

            if ($pdContentType !== 'application/pdf') {
                Storage::disk('r2')->put('img/page_1_'.$file->fileName, $pdfContent);
                $pages[] = [
                    'file' => $file->fileName,
                    'pages' => 1
                ];

            }

            if($pdContentType == 'application/pdf'){
                $suffixName = substr($file->fileName, 0, -4);
                file_put_contents($tempPdfPath."/{$file->fileName}", $pdfContent);

                $pdf = new Pdf($tempPdfPath."/{$file->fileName}");

                foreach(range(1, $pdf->getNumberOfPages()) as $pageNumber){
                    $pngFile = "page_{$pageNumber}_{$suffixName}.png";
                    $pngFilePath = "{$tempImagePath}/{$pngFile}";
                    $pdf->setPage($pageNumber)
                        ->saveImage($pngFilePath);
                    Storage::disk('r2')->put("img/{$pngFile}", file_get_contents($pngFilePath));
                    unlink($pngFilePath);
                }

                unlink($tempPdfPath."/{$file->fileName}");

                $pages[] = [
                    'file' => $file->fileName,
                    'pages' => $pdf->getNumberOfPages()
                ];

            }
        }

        if(is_null($process->number_of_pages) || $process->number_of_pages == 'null'){
            $process->number_of_pages = json_encode($pages);
        } else {
            $process->number_of_pages = json_encode(array_merge(json_decode($process->number_of_pages), $pages));
        }

        $process->save();
    }

}
