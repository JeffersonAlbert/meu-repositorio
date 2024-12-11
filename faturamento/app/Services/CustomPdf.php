<?php
namespace App\Services;

use App\Helpers\UploadFiles;
use App\Http\Controllers\SYS\R2Controller;
use Dompdf\Dompdf;
use Dompdf\Options;

class CustomPdf
{
    protected $dompdf;
    public $r2;

    public function __construct()
    {
        $this->r2 = new UploadFiles();
        $options = new Options();
        $options->set('isHtml5ParserEnabled', true);
        $options->set('isRemoteEnabled', true);

        $this->dompdf = new Dompdf($options);
    }

    public function generatePdf($html, $fileName = 'documento.pdf', $download = false)
    {

        $this->dompdf->loadHtml($html);

        $this->dompdf->render();

        // Define o comportamento do PDF: download ou visualização no navegador
        $output = $this->dompdf->stream($fileName, ['Attachment' => $download]);
        return $output;
    }

    public function setPaper($size, $orientation)
    {

        $this->dompdf->setPaper($size, $orientation);
        return $this;
    }

    public function generatePdfLivewire($html, $fileName = 'documento.pdf', $download = false)
    {
        $this->dompdf->loadHtml($html);
        $this->dompdf->render();

        // Define o comportamento do PDF: download ou visualização no navegador
        $output = $this->dompdf->output();
        return $output;
    }
}
