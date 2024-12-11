<?php

namespace App\Http\Controllers\SYS;

use App\Helpers\FormatUtils;
use App\Helpers\UploadFiles;
use App\Http\Controllers\Controller;
use App\Models\Clientes;
use App\Models\ContasReceber;
use App\Models\Empresa;
use App\Models\Processo;
use App\Models\Produtos;
use App\Models\Vendas;
use App\Services\CustomPdf;
use App\Services\ProcessService;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Spatie\PdfToImage\Pdf;

class PdfController extends Controller
{
    private $pdf;
    protected $processService;
    public function __construct(ProcessService $processService)
    {
        $this->middleware('auth');
        $this->pdf = new UploadFiles();
        $this->processService = $processService;
    }
    public function showPdfThumbnail(string $pdf)
    {
        if(file_exists(storage_path('app/public/tmp/'.$pdf))){
            Log::info('Início do processamento de dados', ['data' => $pdf]);
            $thumb = new Pdf(storage_path('app/public/tmp/'.$pdf));
            $outputFileName = str_replace('.pdf', '.jpg', $pdf);
            $thumb->saveImage(storage_path('app/public/tmp/'.$outputFileName));
            $this->pdf->uploadToR2(
                "thumbnails/{$outputFileName}",
                file_get_contents(storage_path('app/public/tmp/'.$outputFileName)
            ));
            unlink(storage_path('app/public/tmp/'.$outputFileName));
            return;
        }
        return;
    }

    public static function createThumbnail(string $pdf)
    {
        $thumb = new self();
        $thumb->showPdfThumbnail($pdf);
        return $thumb->pdf2Png($pdf);
    }

    public function pagesPerPdf($numberOfPages) : array
    {
        if(is_null($numberOfPages) || $numberOfPages == "null"){
            return [];
        }
        $r2 = new R2Controller();
        $arrayFiles = json_decode($numberOfPages);
        foreach($arrayFiles as $file){
            foreach(range(1, $file->pages) as $pageNumber){
                $fileName = str_replace('.pdf', '.png', "page_{$pageNumber}_{$file->file}");
                $filesName[] = "img/".$fileName;
                if(!Cache::has('blob_'.$fileName)){
                    $image = $r2->getImage("img/{$fileName}");
                    Cache::put('blob_'.$fileName, $image['blob'], now()->addWeek(1));
                    Cache::put('content_type_'.$fileName, $image['content_type'], now()->addWeek(1));
                }
            }
        }
        return $filesName;
    }

    public function pdf2Png(string $pdf)
    {
        $fileSufix = substr($pdf, 0, -4);

        $tempDirectory = sys_get_temp_dir() . '/' . uniqid();
        if (!is_dir($tempDirectory)) {
            mkdir($tempDirectory);
        }

        $pdf = new Pdf(storage_path('app/public/tmp/'.$pdf));
        $pdf->setOutputFormat('png');
        foreach(range(1, $pdf->getNumberOfPages()) as $pageNumber){
            $pdf->setPage($pageNumber)
            ->saveImage("{$tempDirectory}/page_{$pageNumber}_{$fileSufix}.png");
            $base64 = base64_encode(file_get_contents("{$tempDirectory}/page_{$pageNumber}_{$fileSufix}.png"));
            $this->pdf->uploadToR2("img/page_{$pageNumber}_{$fileSufix}.png", $base64);
            unlink("{$tempDirectory}/page_{$pageNumber}_{$fileSufix}.png");
        }
        return $pdf->getNumberOfPages();
    }

    public function convertPdf($files, $type)
    {
        foreach(json_decode($files) as $file){
            $this->pdf2Png($file);
            $pages[] = ["file" => $file,"pages" => $this->getPngPages($file)];
        }
        return $pages;
    }

    public function getPngPages($file)
    {
        if($file == "teste.pdf"){
            return 1;
        }
        $pdf = new Pdf("uploads/{$file}");
        return $pdf->getNumberOfPages();
    }

    public function generatePdf(
        $usuario,
        $fornecedor,
        $valor,
        $formaPagamento,
        $numeroNota,
        $dataInclusao,
        $anexos,
        $rastreio,
        $empresa
    )
    {
        $customPdf = new CustomPdf();
        $quantidadeAnexos = count(json_decode($anexos));
        $html = "<html>
        <head>
            <title>Protocolo de Impressão</title>
            <style>
                body {
                    font-family: Arial, sans-serif;
                    margin: 0;
                    padding: 20px;
                }
                .header {
                    text-align: center;
                    margin-bottom: 20px;
                }
                .content {
                    border: 1px solid #ddd;
                    padding: 20px;
                }
                .info {
                    margin-bottom: 10px;
                }
                .attachment-info {
                    margin-top: 20px;
                    border-top: 1px solid #ddd;
                    padding-top: 10px;
                }
                .footer {
                    text-align: right;
                }
            </style>
        </head>
        <body>
            <div class='header'>
                <h1>Protocolo de Impressão</h1>
            </div>
            <div class='content'>
                <div class='info'>
                    <strong>Identificação:</strong> {$rastreio}
                </div>
                <div class='info'>
                    <strong>Empresa:</strong> {$empresa}
                </div>
                <div class='info'>
                    <strong>Filial:</strong> Sem filial
                </div>
                <div class='info'>
                    <strong>Usuário:</strong> {$usuario}
                </div>
                <div class='info'>
                    <strong>Fornecedor:</strong> {$fornecedor}
                </div>
                <div class='info'>
                    <strong>Valor:</strong> R$ {$valor}
                </div>
                <div class='info'>
                    <strong>Forma de Pagamento:</strong> {$formaPagamento}
                </div>
                <div class='info'>
                    <strong>Número da Nota:</strong> {$numeroNota}
                </div>
                <div class='info'>
                    <strong>Data de Inclusão:</strong> {$dataInclusao}
                </div>
                <div class='info'>
                    <strong>Quantidade de Anexos:</strong> {$quantidadeAnexos}
                </div>
            </div>
            <div class='attachment-info'>
                <strong>Anexos:</strong>
                <ul>";
        foreach(json_decode($anexos) as $anexo){
            $html .= "<li>{$anexo}</li>";
        }
        $html .= "</ul>
            </div>
        </body>
        </html>";
        $pdfOutput = $customPdf->generatePdf($html);

        return response($pdfOutput)
            ->header('Content-Type', 'application/pdf')
            ->header('Content-Disposition', 'inline; filename="exemplo.pdf"');
    }

    public function generateOrcamento($id)
    {
        $idEmpresa = auth()->user()->id_empresa;
        $venda = Vendas::select('vendas.*', 'users.name', 'clientes.nome', 'clientes.id as clienteId',
            'setup.logo')
            ->leftJoin('users', 'users.id', '=', 'vendas.user_id')
            ->leftJoin('clientes', 'clientes.id', '=', 'vendas.cliente_id')
            ->leftJoin('setup', 'setup.id_empresa', '=', 'vendas.id_empresa')
            ->where('vendas.id_empresa', auth()->user()->id_empresa)
            ->first($id);
        $competencia = FormatUtils::formatDate($venda->updated_at, 'd/m/Y');
        $dadosVenda = json_decode($venda->dados_venda);
        foreach($dadosVenda->produtoId as $produto){
            $produtos[] = Produtos::find($produto)->produto;
        }
        $descricoes = $dadosVenda->observacao;
        $quantidades = $dadosVenda->quantidade;
        $valoresUnitarios = $dadosVenda->valor_unitario;
        $valoresTotais = $dadosVenda->valor_total;

        $parcelas = '';
        $dataVencimento = $dadosVenda->data_vencimento;
        $valorReceber = $dadosVenda->valor_receber;

        $logo = "logo/{$idEmpresa}/{$venda->logo}";
        $logoFile = Storage::disk('r2')->get($logo);
        $logoContentTypes = Storage::disk('r2')->mimeType($logo);
        $srcImg = 'data:'.$logoContentTypes.';base64,'.base64_encode($logoFile);

        $emitente = Empresa::find(auth()->user()->id_empresa);
        $telefoneFormatadoEmitente = FormatUtils::formatPhone($emitente->telefone);
        $cepFormatadoEmitente = FormatUtils::formatCep($emitente->cep);

        $cliente = Clientes::find($venda->cliente_id);
        $telefoneFormatadoCliente = FormatUtils::formatPhone($cliente->telefone);
        $cepFormatadoCliente = FormatUtils::formatCep($cliente->cep);

        $html = view('pdf.orcamento',
            [
                'venda' => $venda,
                'competencia' => $competencia,
                'parcelas' => $parcelas,
                'logo' => $srcImg,
                'emitente' => $emitente,
                'telefoneFormatadoEmitente' => $telefoneFormatadoEmitente,
                'cepFormatadoEmitente' => $cepFormatadoEmitente,
                'cliente' => $cliente,
                'telefoneFormatadoCliente' => $telefoneFormatadoCliente,
                'cepFormatadoCliente' => $cepFormatadoCliente,
                'dadosVenda' => $dadosVenda,
                'produtos' => $produtos,
                'descricoes' => $descricoes,
                'quantidades' => $quantidades,
                'valoresUnitarios' => $valoresUnitarios,
                'valoresTotais' => $valoresTotais,
                'dataVencimento' => $dataVencimento,
                'valorReceber' => $valorReceber
            ]
        )->render();

        $customPdf = new CustomPdf();
        $pdfOutput = $customPdf->generatePdf($html, 'orcamento.pdf',1);
        return response($pdfOutput)
            ->header('Content-Type', 'application/pdf')
            ->header('Content-Disposition', 'inline; filename="exemplo.pdf"');
    }

    public function pdfDre($periodo, $startYear, $endYear)
    {
       $dre = new DREController();
       $result = $dre->dreReport($periodo, $startYear, $endYear);
       $html =  view('pdf.dre',
           [
           'data' => $result,
           'periodo' => $periodo,
           'startYear' => $startYear,
           'endYear' => $endYear
           ]
       )->render();

        $customPdf = new CustomPdf();
        $pdfOutput = $customPdf->setPaper('a4', 'landscape')
            ->generatePdf($html, 'dre.pdf', 1);

        return response($pdfOutput)
            ->header('Content-Type', 'application/pdf')
            ->header('Content-Disposition', 'inline; filename="exemplo.pdf"');
    }

    public function pdfFluxoCaixa($date)
    {
        $fluxoCaixa = new FinanceiroController();
        $result = $fluxoCaixa->fluxoCaixa($date);
        $html =  view('pdf.fluxo-caixa',
            [
                'data' => $result,
            ]
        )->render();

        $customPdf = new CustomPdf();
        $pdfOutput = $customPdf->setPaper('a4', '')
            ->generatePdf($html, 'fluxo-caixa.pdf', 1);

        return response($pdfOutput)
            ->header('Content-Type', 'application/pdf')
            ->header('Content-Disposition', 'inline; filename="fluxo-caixa.pdf"');
    }

    public function pdfContasReceber(Request $request)
    {
        $relatorio = (new ContasReceber())->relatorioContasReceber($request);
        $html = view('pdf.contas-receber',
            [
                'relatorio' => $relatorio,
            ]
        )->render();

        $customPdf = new CustomPdf();
        $pdfOutput = $customPdf->setPaper('a4', 'landscape')
            ->generatePdf($html, 'contas-receber.pdf', 1);

        return response($pdfOutput)
            ->header('Content-Type', 'application/pdf')
            ->header('Content-Disposition', 'attachment; filename="contas-receber.pdf"');
    }

    public function pdfContasPagar(Request $request)
    {
        $relatorio = (new ReportController($this->processService))->gridContasPagar($request);

        $html = view('pdf.contas-pagar',
            [
                'relatorio' => json_decode($relatorio->content()),
            ]
        )->render();

        $customPdf = new CustomPdf();
        $pdfOutput = $customPdf->setPaper('a4', 'landscape')
            ->generatePdf($html, 'contas-pagar.pdf', 1);

        return response($pdfOutput)
            ->header('Content-Type', 'application/pdf')
            ->header('Content-Disposition', 'attachment; filename="contas-pagar.pdf"');
    }

    public function desktopMiniatures($id)
    {
        $process = Processo::find($id);
        $result = $this->pagesPerPdf($process->number_of_pages);
        if(!empty($result)){
            return json_encode(['success' => true, 'files' => $result]);
        }
        return $result;
    }

    public function viewerPdf($pdfName)
    {
        $r2 = new R2Controller();
        $pdf = $r2->fetch("uploads/{$pdfName}");
        return view('pdfjs.viewer', ['pdf' => $pdf]);
    }
}
