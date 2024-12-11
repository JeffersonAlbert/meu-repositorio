<?php

namespace App\Http\Controllers\SYS;

use App\Exports\ContasReceberExports;
use App\Exports\ContasPagarExports;
use App\Exports\PlanilhaPagarExports;
use App\Http\Controllers\Controller;
use App\Models\Bancos;
use App\Models\CategoriasReceber;
use App\Models\CentroCusto;
use App\Models\Clientes;
use App\Models\ContasReceber;
use App\Models\Contratos;
use App\Models\DRE;
use App\Models\Filial;
use App\Models\FormasPagamento;
use App\Models\Fornecedor;
use App\Models\Processo;
use App\Models\Produtos;
use App\Models\Rateio;
use App\Models\SubCategoriaDRE;
use App\Models\TipoCobranca;
use App\Services\ProcessService;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Maatwebsite\Excel\Facades\Excel;

class ReportController extends Controller
{
    private $contasReceber;
    protected $processService;

    public function __construct(ProcessService $processService)
    {
        $this->middleware('auth');
        $this->contasReceber = new ContasReceber();
        $this->processService = $processService;
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $contasReceber = $this->contasReceber;
        $mensalReceber =  $contasReceber->contasReceberMensal();
        $mensalPagar = $contasReceber->contasPagarMensal();
        $anualReceber = $contasReceber->contasReceberAnual();
        $anualPagar = $contasReceber->contasPagarAnual();
        $relatorio = $contasReceber->relatorioContasReceber($request);
        $categorias = CategoriasReceber::where('id_empresa', auth()->user()->id_empresa)->get();
        $centrosCustos = CentroCusto::where('id_empresa', auth()->user()->id_empresa)->get();
        $rateios = Rateio::where('id_empresa', auth()->user()->id_empresa)->get();
        $contratos = Contratos::where('id_empresa', auth()->user()->id_empresa)->get();
        $produtos = Produtos::where('id_empresa', auth()->user()->id_empresa)->get();
        $filiais = Filial::where('id_empresa', auth()->user()->id_empresa)->get();
        return view('dashboard.relatorio', compact(
            'mensalReceber',
            'mensalPagar',
            'anualReceber',
            'anualPagar',
            'relatorio',
            'categorias',
            'centrosCustos',
            'rateios',
            'contratos',
            'produtos',
            'filiais'
        ));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    public function indexReportDre($periodo = null, $startYear = null, $endYear = null)
    {
        $dreInstance = new DREController();
        $data = $dreInstance->dreReport($periodo, $startYear, $endYear);
        $startYear = $startYear ?? date('Y');
        $endYear = $endYear ?? date('Y');
        $periodo = $periodo ?? 'monthly';
        return view('reports.dre', compact('data', 'periodo', 'startYear', 'endYear'));
    }

    public function contasReceber(Request $request)
    {
        $clientes = Clientes::where('id_empresa', auth()->user()->id_empresa)->get();
        $centroCustos = CentroCusto::where('id_empresa', auth()->user()->id_empresa)->get();
        $dreReceitas = (new SubCategoriaDRE())->dreReceita();
        $relatorio = $this->contasReceber->relatorioContasReceber($request);
        $rateios = Rateio::where('id_empresa', auth()->user()->id_empresa)->get();
        $contratos = Contratos::where('id_empresa', auth()->user()->id_empresa)->get();
        $produtos = Produtos::where('id_empresa', auth()->user()->id_empresa)->get();
        $filiais = Filial::where('id_empresa', auth()->user()->id_empresa)->get();
        return view('reports.contas-receber',
            compact(
                'relatorio',
                'clientes',
                'centroCustos',
                'dreReceitas',
                'rateios',
                'contratos',
                'produtos',
                'filiais'
            ));
    }

    public function contasReceberRelatorio(Request $request)
    {
        $relatorio = $this->contasReceber->relatorioContasReceber($request);
        return response()->json($relatorio);
    }

    public function gridContasReceber(Request $request)
    {
        $relatorio = $this->contasReceber->relatorioContasReceber($request);
        return $relatorio;
    }

    public function indexContasPagar(Request $request)
    {
        $p = new Processo();
        $contasReceber = $this->contasReceber;
        $mensalPagar = $contasReceber->contasPagarMensal();
        $anualPagar = $contasReceber->contasPagarAnual();
        $anualReceber = $contasReceber->contasReceberAnual();
        $mensalReceber =  $contasReceber->contasReceberMensal();
        $processos = $p->allProcessos();
        $tipos_cobranca = TipoCobranca::where('id_empresa', auth()->user()->id_empresa);
        $bancos = Bancos::where('id_empresa', auth()->user()->id_empresa);
        $formas_pagamento = FormasPagamento::all();
        $centrosCusto = CentroCusto::where('id_empresa', auth()->user()->id_empresa)->get();
        $rateios = Rateio::where('id_empresa', auth()->user()->id_empresa)->get();
        $filiais = Filial::where('id_empresa', auth()->user()->id_empresa)->get();
        $contratos = Contratos::where('id_empresa', auth()->user()->id_empresa)->get();
        $produtos = Produtos::where('id_empresa', auth()->user()->id_empresa)->get();

        $listaProcessos = $processos['result'];
        //dd($listaProcessos);
        return view('dashboard.relatorioContasPagar', compact(
            'mensalPagar',
            'anualPagar',
            'mensalReceber',
            'anualReceber',
            'listaProcessos',
            'tipos_cobranca',
            'bancos',
            'formas_pagamento',
            'centrosCusto',
            'rateios',
            'filiais',
            'contratos',
            'produtos'
        ));
    }

    public function dataReport($request, $type)
    {
        if($type == 'contas_receber'){
            $relatorio = $this->contasReceber->relatorioContasReceber($request);
        }
        if($type == 'contas_pagar'){
            $relatorio = $this->dataContasPagar($request);
        }

        return $relatorio;
    }

    public function dataContasPagar($request)
    {
        $this->processService->setUser(auth()->user());

        $report = $this->processService;

        if ($traceCode = $request->get('trace_code')) {
            $report = $report->byTraceCode($traceCode);
        }

        if ($fornecedor = $request->get('fornecedor')) {
            $report = $report->bySupplier($fornecedor);
        }

        if ($tipoCobranca = $request->get('tipo_cobranca')) {
            $report = $report->byBillingType($tipoCobranca);
        }

        if ($banco = $request->get('banco')) {
            $report = $report->byBank($banco);
        }

        if ($formaPagamento = $request->get('forma_pagamento')) {
            $report = $report->byPaymentMethod($formaPagamento);
        }

        if ($pagamentoInicial = $request->get('pagamentoInicial') && $pagamentoFinal = $request->get('pagamentoFinal')) {
            $report = $report->byPaymentDateRange($pagamentoInicial, $pagamentoFinal);
        }

        $vencimentoInicial = $request->get('vencimentoInicial') ?? date('Y-m-01');
        $vencimentoFinal = $request->get('vencimentoFinal') ?? date('Y-m-t');

        $report = $report->byBillingDateRange($vencimentoInicial, $vencimentoFinal);

        if ($rateio = $request->get('rateio')) {
            $report = $report->byApportioned($rateio);
        }

        if ($centroCusto = $request->get('centro_custo')) {
            $report = $report->byCenterCost($centroCusto);
        }

        // Filtrar por itens não desativados
        $report = $report->isNotDisabled()->list();

        return $report;
    }


    public function gridContasPagar(Request $request)
    {
        $fornecedores = Fornecedor::where('id_empresa', auth()->user()->id_empresa)->get();
        $dreReceitas = (new SubCategoriaDRE())->dreDespesa();
        $centroCustos = CentroCusto::where('id_empresa', auth()->user()->id_empresa)->get();
        $rateios = Rateio::where('id_empresa', auth()->user()->id_empresa)->get();
        $contratos = Contratos::where('id_empresa', auth()->user()->id_empresa)->get();
        $produtos = Produtos::where('id_empresa', auth()->user()->id_empresa)->get();
        $filiais = Filial::where('id_empresa', auth()->user()->id_empresa)->get();
        $relatorio = $this->dataReport($request, 'contas_pagar');

        if($request->has('vencimentoInicial') && $request->has('vencimentoFinal')){
            $relatorio = $this->dataReport($request, 'contas_pagar');
            return response()->json($relatorio);
        }

        return view('reports.contas-pagar',compact(
            'relatorio',
            'fornecedores',
            'dreReceitas',
            'centroCustos',
            'rateios',
            'contratos',
            'produtos',
            'filiais'
        ));
    }

    public function excelContasReceber(Request $request)
    {
        return Excel::download(new ContasReceberExports($request), 'contas_receber.xlsx');
    }
    public function relatorioContasPagar(Request $request)
    {
        return Excel::download(new ContasPagarExports($request->all()), 'contas_pagar.xlsx');
    }

    public function excelContasPagar(Request $request)
    {
        return Excel::download(new PlanilhaPagarExports($request, $this->processService), 'contas_pagar.xlsx');
    }
}
