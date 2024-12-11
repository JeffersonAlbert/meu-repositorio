<?php

/**
 * Controller da funcao do financeiro
 * php version 8.1
 *
 * @category Controller
 * @package  App\Http\Controllers\SYS;
 *
 * @author  Demostenes <demostenex@gmail.com>
 * @license https://app.number.app.br MIT
 * @link    https://app.number.app.br PHP 8.1p
 */

namespace App\Http\Controllers\SYS;

use App\Exports\FluxoCaixaExports;
use App\Helpers\FormatUtils;
use App\Helpers\UploadFiles;
use App\Helpers\Utils;
use App\Http\Controllers\Controller;
use App\Models\Bancos;
use App\Models\CategoriasReceber;
use App\Models\CentroCusto;
use App\Models\ContasReceber;
use App\Models\Contratos;
use App\Models\Filial;
use App\Models\FormasPagamento;
use App\Models\Processo;
use App\Models\Produtos;
use App\Models\Rateio;
use App\Models\ReceberVencimentoValor;
use App\Models\SubCategoriaDRE;
use App\Models\TipoCobranca;
use App\Services\FinancesService;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\View\View;

use Maatwebsite\Excel\Facades\Excel;
use function GuzzleHttp\json_decode;

/**
 * Controller da funcao do financeiro
 *
 * @category Controller
 * @package  App\Http\Controllers\SYS;
 *
 * @author  Demostenes <demostenex@gmail.com>
 * @license https://app.number.app.br MIT
 * @link    https://app.number.app.br PHP 8.1
 */
class FinanceiroController extends Controller
{
    protected $processo;

    /**
     * Exige a autenticacao do usuario
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @return view
     */

    public function index(Request $request)
    {
        if ($request->has('page')) {
            $processo = new ProcessoController();
            return $processo->consultaProcesso($request);
        }
        $processoInstance = new Processo();
        $processos = $processoInstance->getProcessosAprovadosPaginated();
        $qtdeFinanceiro = $processos->total();
        $processosNovos = $processoInstance->getProcessosPaginated();
        $pendentes = $processoInstance->getPendentesPaginated();
        $qtdeNovosProcessos = $processosNovos->total();
        $qtdeProcessosPendentes = $pendentes->total();
        $processosPagos = $processoInstance->getProcessosCompletosPaginated();
        $processoPago = $processosPagos->total();
        $centrosCustos = CentroCusto::where('id_empresa', auth()->user()->id_empresa)->get();
        $rateios = Rateio::where('id_empresa', auth()->user()->id_empresa)->get();
        $filiais = Filial::where('id_empresa', auth()->user()->id_empresa)->get();
        return view(
            'processo.gridFinanceiroProcessos',
            compact(
                'processos',
                'qtdeNovosProcessos',
                'qtdeProcessosPendentes',
                'qtdeFinanceiro',
                'processoPago',
                'centrosCustos',
                'rateios',
                'filiais'
            )
        );
    }


    /**
     * Consulta a tabela do financeiro para exibir as contas a pagar
     *
     * @param $search recebe o formulario
     *
     * @return view
     */
    public function controleFinanceiro(Request $search)
    {
        if (!auth()->user()->financeiro) {
            return redirect()->route('processo.index');
        }
        $processos = new Processo();
        $contasReceber = new ContasReceber();

        $dados = $processos->allProcessos($search->all());
        $pagarMensal = $contasReceber->contasPagarMensal();
        $pagarDia = $contasReceber->contasPagarDia();  // vencem hoje
        $vencidas = $contasReceber->contasPagarMensalVencidas();
        $aVencer = $contasReceber->contasPagarMensalAVencer();
        $contasPagasMes = $contasReceber->contasPagasMes();
        $faturas = $dados['result'];
        if ($search->get('tipo') !== null) {
            return response()->json($faturas);
        }

        $maiorValor = max([$aVencer + $vencidas, $contasPagasMes]);
        $total = $dados['qtdeAndamento'] + $dados['qtdePendentes'] + $dados['qtdePagos'];
        $qtdeAndamento = $dados['qtdeAndamento'];
        $qtdePendentes = $dados['qtdePendentes'];
        $qtdePagos = $dados['qtdePagos'];
        $tipos_cobranca = TipoCobranca::where(
            'id_empresa',
            auth()->user()->id_empresa
        )
            ->get();
        $formas_pagamento = FormasPagamento::all();
        $bancos = Bancos::where(
            'id_empresa',
            auth()->user()->id_empresa
        )
            ->get();
        $centrosCusto = CentroCusto::where('id_empresa', auth()->user()->id_empresa)->get();
        $rateios = Rateio::where('id_empresa', auth()->user()->id_empresa)->get();
        $filiais = Filial::where('id_empresa', auth()->user()->id_empresa)->get();
        return view(
            'financeiro.contaspagar.grid',
            compact(
                'faturas',
                'total',
                'qtdeAndamento',
                'qtdePendentes',
                'qtdePagos',
                'tipos_cobranca',
                'bancos',
                'formas_pagamento',
                'centrosCusto',
                'rateios',
                'pagarMensal',
                'pagarDia',
                'aVencer',
                'contasPagasMes',
                'filiais',
                'maiorValor',
                'vencidas'
            )
        );
    }

    /**
     * Exibe contas a pargar do financeiro do sistema
     *
     * @return view
     */
    public function receberFinanceiro(Request $request)
    {
        return redirect()->route('acccounts-receivable.index');

        $categorias = CategoriasReceber::where('id_empresa', auth()->user()->id_empresa)->get();
        $centrosCustos = CentroCusto::where('id_empresa', auth()->user()->id_empresa)->get();
        $contratos = Contratos::where('id_empresa', auth()->user()->id_empresa)->get();
        $rateios = Rateio::where('id_empresa', auth()->user()->id_empresa)->get();
        $produtos = Produtos::where('id_empresa', auth()->user()->id_empresa)->get();
        $contasReceber = new ContasReceber();
        $receberDiario = $contasReceber->contasReceberDiario();
        $receberVencidos = $contasReceber->contasReceberVencidos();
        $receberPago = $contasReceber->contasReceberPago();
        $aVencer = $contasReceber->contasReceberAVencer();
        $contasReceber = $contasReceber->pegarContasReceberPorEmpresa('', true);
        $pago = $contasReceber['pago'];
        $pendente = $contasReceber['pendente'];
        $contasReceber = $contasReceber['query'];
        $geral = $contasReceber->total();
        $filiais = Filial::where('id_empresa', auth()->user()->id_empresa)->get();
        $maiorValor = max([$receberVencidos + $aVencer, $receberPago]);
        return view(
            'financeiro.contasreceber.grid',
            compact(
                'categorias',
                'centrosCustos',
                'contasReceber',
                'contratos',
                'rateios',
                'produtos',
                'pago',
                'pendente',
                'geral',
                'filiais',
                'receberDiario',
                'receberVencidos',
                'receberPago',
                'aVencer',
                'maiorValor'
            )
        );
    }

    /**
     * Exibe o formulario de cadastro de contas a pagar
     *
     * @return view
     */
    public function receberCadastar(): View
    {
        $categorias = CategoriasReceber::where(
            'id_empresa',
            auth()->user()->id_empresa
        )->get();
        $centrosCusto = CentroCusto::where(
            'id_empresa',
            auth()->user()->id_empresa
        )->get();
        $contratos = Contratos::where(
            'id_empresa',
            auth()->user()->id_empresa
        )->get();
        $rateios = Rateio::where('id_empresa', auth()->user()->id_empresa)->get();
        $filiais = Filial::where('id_empresa', auth()->user()->id_empresa)->get();
        $subDres = SubCategoriaDRE::select('sub_categoria_dre.id as sub_id', 'sub_categoria_dre.descricao as sub_desc')
            ->leftJoin('dre', 'sub_categoria_dre.id_dre', 'dre.id')
            ->where('sub_categoria_dre.id_empresa', auth()->user()->id_empresa)
            ->where('dre.tipo', 'receita')
            ->orWhere('dre.tipo', 'receita')
            ->whereNull('sub_categoria_dre.id_empresa')
            ->where('tipo', 'receita')
            ->get();
        return view(
            'financeiro.contasreceber.form',
            compact(
                'categorias',
                'centrosCusto',
                'rateios',
                'contratos',
                'filiais',
                'subDres'
            )
        );
    }

    /**
     * Busca contas as pagar do menu financeiro
     *
     * @param $request recebe dados de consulta do formulario
     *
     * @return view
     */
    public function buscaFinanceiro(Request $request): JsonResponse
    {
        if (!auth()->user()->financeiro) {
            return response()->json(
                [
                    'redirect' => route('forma-pagamento.index')
                ]
            );
        }
        $processos = new Processo();
        $financeiro = new ContasReceber();

        $dados = $processos->allProcessos($request->all());
        if ($request->has('vencimentoInicial') && $request->has('vencimentoFinal')) {
            $periodoVencido = [$request->get('vencimentoInicial'), date('Y-m-d')];
            $periodoAVencer = [date('Y-m-d'), $request->get('vencimentoFinal')];
            $valorVencidas = $financeiro->contasPagarMensalVencidas($periodoVencido);
            $aVencer = $financeiro->contasPagarMensalAVencer($periodoAVencer);
        }

        $faturas = $dados['result'];
        if ($request->get('tipo') !== null) {
            return response()->json([
                'faturas' => $faturas,
                'valor_vencido' => isset($valorVencidas) ? $valorVencidas : null,
                'valor_vencer' => isset($aVencer) ? $aVencer : null,
            ]);
        }
        return response()->json($faturas);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return void
     */
    public function create(): void
    {
        dd('laal');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param $request recebe algum formulario
     *
     * @return void
     */
    public function store(Request $request): RedirectResponse|JsonResponse
    {
        $upload = new UploadFiles();

        $messages = [
            'competencia.required' => 'Precisa preencher os dados da competencia',
            'name.required' => 'Precisa preencher o nome do cliente',
            'valor_total.required' => 'Precisa informar um valor de nota',
            'sub_categoria_dre.required' => 'Precisa selecionar uma categoria',
            'vencimento.required' => 'Precisa de um valor',
            'codigo_referencia.required' => 'Precisa adicionar um codigo de referencia',
            'condicao' => 'Selecione a condição de pagamento'
        ];

        $validator = Validator::make(
            $request->all(),
            [
                'competencia' => 'required',
                'name' => 'required',
                'valor_total' => 'required',
                'sub_categoria_dre' => 'required',
                'vencimento' => 'required',
                'codigo_referencia' => 'required',
                'condicao' => 'required'
            ],
            $messages
        );

        if ($validator->fails()) {
            return response()->json(
                [
                    'errors' => $validator->errors()
                ],
                422
            );
        }

        $data = $request->except('_token', 'name', 'valor_total', 'files');

        if (!is_numeric('id_centro_custo')) {
            $data['id_centro_custo'] = null;
        }

        if ($request->has('files')) {
            $files = $upload->uploadNewFile($request->file('files'));
            $filesDescAndType = $upload->fileDescAndType($files, $request->get('tipo_anexo'), $request->get('descricao_arquivo'));
        }

        $valorTotal = FormatUtils::formatMoneyDb($request->get('valor_total'));
        $data['valor_total'] = $valorTotal;
        $data['id_empresa'] = auth()->user()->id_empresa;
        $cliente = $request->get('name');
        $clienteParts = explode('-', $cliente);
        $idCliente = trim($clienteParts[0]);
        $data['id_cliente'] = $idCliente;
        $data['sub_categoria_dre'] = $data['sub_categoria_dre'];
        $data['files_types_desc'] = isset($filesDescAndType) ? json_encode($filesDescAndType) : null;
        $data['id_categoria'] = is_numeric($request->get('id_categoria')) ? $request->get('id_categoria') : 0;
        $data['tipo'] = null;
        if ($request->has('id_contrato') and !is_null($request->get('id_contrato'))) {
            $contratoParts = explode('-', $request->get('id_contrato'));
            $idContrato = $contratoParts[0];
        }

        $data['id_contrato'] = isset($idContrato) ? $idContrato : null;

        if ($request->has('id_produto') and !is_null($request->get('id_contrato'))) {
            $produtoParts = explode('-', $request->get('id_produto'));
            $idProduto = $produtoParts[0];
            $data['id_produto'] = $idProduto;
        }

        $data['id_usuario'] = auth()->user()->id;
        if ($request->get('valor_vencimento') !== null) {
            $data['valor_vencimento'] = FormatUtils::formatMoneyDb($request->get('valor_vencimento'));
        } else {
            $data['valor_vencimento'] = FormatUtils::formatMoneyDb($request->get('valor_total'));
        }

        $data['observacao'] = $request->get('observacao');
        $data['rateio'] = $request->get('id_rateio');
        $data['trace_code'] = FormatUtils::traceCode();
        $cadastraReceber = ContasReceber::create($data);
        $arrayIdsContasReceber[] = $cadastraReceber->id;
        if ($request->has('vencimento_recorrente')) {
            $vencimento_recorrente = $request->get('vencimento_recorrente');
            $valor_recorrente = $request->get('valor_recorrente');
            foreach ($vencimento_recorrente as $key => $vencimento) {
                if ($key !== 0) {
                    $data['vencimento'] = $vencimento;
                    $data['trace_code'] = FormatUtils::traceCode();
                    $idContasReceber = ContasReceber::create($data);
                    $arrayIdsContasReceber[] = isset($idContasReceber) ? $idContasReceber->id : null;
                }
            }
        }

        return response()->json(
            [
                'success' => [
                    'message' => [
                        'Inserido com sucesso'
                    ],
                    'id' => $arrayIdsContasReceber,
                ]
            ]
        );
    }

    public function destroyFile(Request $request)
    {
        $upload = new UploadFiles();
        $processo = ContasReceber::select('files_types_desc')
            ->where('id', $request->get('id'))
            ->first();
        $files = $upload->removeFile(json_decode($processo->files_types_desc, true), $request->all());
        $updateProcesso = ContasReceber::where('id', $request->get('id'))->update(
            [
                'files_types_desc' => json_encode($files),
            ]
        );
        if ($updateProcesso) {
            return response()->json(
                [
                    'success' => 'success',
                    'msg' => 'Atualizado com sucesso'
                ]
            );
        }

        if (!$updateProcesso) {
            return response()->json([
                'error' => 'error',
                'msg' => 'Erro ao tentar atualizar'
            ], 422);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param $id recebe algum id
     *
     * @return void
     */
    public function show(string $id): void {}

    /**
     * Show the form for editing the specified resource.
     *
     * @param $id recebe um id
     *
     * @return void
     */
    public function edit(string $id): View|RedirectResponse
    {
        $contasReceber = ContasReceber::select(
            'contas_receber.id',
            'contas_receber.trace_code',
            'contas_receber.competencia as competencia',
            'contas_receber.id_cliente as id_cliente',
            'clientes.nome as nome_cliente',
            'clientes.cpf_cnpj as doc_cliente',
            'contas_receber.descricao as descricao',
            'contas_receber.observacao as observacao',
            'contas_receber.valor_total',
            'contas_receber.vencimento',
            'categoria.categoria as categoria',
            'centro_custos.nome as nome_centro_custo',
            'contas_receber.codigo_referencia as codigo_referencia',
            'contratos.id as id_contrato',
            'contratos.nome as nome_contrato',
            'produtos.id as id_produto',
            'produtos.produto as produto',
            'rateio.nome as rateio',
            'sub_categoria_dre.id as sub_id',
            'sub_categoria_dre.descricao as sub_desc',
            'files_types_desc',
            'contas_receber.id_empresa'
        )
            ->leftJoin('clientes', 'clientes.id', 'contas_receber.id_cliente')
            ->leftJoin('categorias_receber as categoria', 'categoria.id', 'contas_receber.id_categoria')
            ->leftJoin('centro_custos', 'centro_custos.id', 'contas_receber.id_centro_custo')
            ->leftJoin('contratos', 'contratos.id', 'contas_receber.id_contrato')
            ->leftJoin('produtos', 'produtos.id', 'contas_receber.id_produto')
            ->leftJoin('rateio_setup as rateio', 'rateio.id', 'contas_receber.rateio')
            ->leftJoin('sub_categoria_dre', 'sub_categoria_dre.id', 'contas_receber.sub_categoria_dre')
            ->find($id);

        $categorias = CategoriasReceber::where('id_empresa', auth()->user()->id_empresa)->get();
        $centrosCusto = CentroCusto::where('id_empresa', auth()->user()->id_empresa)->get();
        $rateios = Rateio::where('id_empresa', auth()->user()->id_empresa)->get();
        $subDres = SubCategoriaDRE::select('sub_categoria_dre.id as sub_id', 'sub_categoria_dre.descricao as sub_desc')
            ->leftJoin('dre', 'sub_categoria_dre.id_dre', 'dre.id')
            ->where('sub_categoria_dre.id_empresa', auth()->user()->id_empresa)
            ->where('dre.tipo', 'receita')
            ->orWhere('dre.tipo', 'receita')
            ->whereNull('sub_categoria_dre.id_empresa')
            ->where('tipo', 'receita')
            ->get();

        if(auth()->user()->id_empresa == $contasReceber->id_empresa){
            return view('financeiro.contasreceber.form', compact('contasReceber', 'categorias', 'centrosCusto', 'rateios', 'subDres'));
        }

        return redirect()->route('contas-receber.index');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param $request recebe um formulario
     * @param $id      recebe id
     *
     * @return void
     */
    public function update(Request $request, string $id): RedirectResponse|JsonResponse
    {
        $upload = new UploadFiles();
        $messages = [
            'competencia.required' => 'Precisa preencher os dados da competencia',
            'name.required' => 'Precisa preencher o nome do cliente',
            'valor_total.required' => 'Precisa informar um valor de nota',
            'sub_categoria_dre.required' => 'Precisa selecionar uma categoria',
            'vencimento.required' => 'Precisa de um valor',
            'codigo_referencia.required' => 'Precisa adicionar um codigo de referencia'
        ];

        $validator = Validator::make(
            $request->all(),
            [
                'competencia' => 'required',
                'name' => 'required',
                'valor_total' => 'required',
                'sub_categoria_dre' => 'required',
                'vencimento' => 'required',
                'codigo_referencia' => 'required'
            ],
            $messages
        );

        if ($validator->fails()) {
            return response()->json(
                [
                    'errors' => $validator->errors()
                ],
                422
            );
        }

        $data = $request->except(
            '_token',
            'name',
            'valor_total',
            '_method',
            'vencimento_recorrente',
            'valor_recorrente',
            'files',
            'tipo_anexo',
            'descricao_arquivo'
        );

        if (!is_numeric('id_centro_custo')) {
            $data['id_centro_custo'] = null;
        }

        if ($request->has('files')) {
            $files = $upload->uploadNewFile($request->file('files'));
            $filesDescAndType = $upload->fileDescAndType($files, $request->get('tipo_anexo'), $request->get('descricao_arquivo'));
        }

        $valorTotal = FormatUtils::formatMoneyDb($request->get('valor_total'));
        $data['valor_total'] = $valorTotal;
        $data['id_empresa'] = auth()->user()->id_empresa;
        $cliente = $request->get('name');
        $clienteParts = explode('-', $cliente);
        $idCliente = trim($clienteParts[0]);
        $data['id_cliente'] = $idCliente;
        $data['sub_categoria_dre'] = $data['sub_categoria_dre'];
        $data['tipo'] = null;

        if ($request->has('id_contrato')) {
            $contratoParts = explode('-', $request->get('id_contrato'));
            $idContrato = $contratoParts[0];
        }
        $data['id_contrato'] = is_int($idContrato) ? $idContrato : 0;

        if ($request->has('id_produto')) {
            $produtoParts = explode('-', $request->get('id_produto'));
            $idProduto = $produtoParts[0];
            $data['id_produto'] = $idProduto;
        }
        $data['id_produto'] = is_int($idProduto) ? $idProduto : 0;

        $data['id_usuario'] = auth()->user()->id;
        if ($request->get('valor_vencimento') !== null) {
            $data['valor_vencimento'] = FormatUtils::formatMoneyDb($request->get('valor_vencimento'));
        } else {
            $data['valor_vencimento'] = FormatUtils::formatMoneyDb($request->get('valor_total'));
        }

        $data['observacao'] = $request->get('observacao');
        $data['rateio'] = $request->get('id_rateio');
        $data['id_categoria'] = is_numeric($request->get('id_categoria')) ? $request->get('id_categoria') : 0;
        $data['files_types_desc'] = isset($filesDescAndType) ? json_encode($filesDescAndType) : null;

        $cadastraReceber = ContasReceber::where('id', $id)->update($data);
        $arrayIdsContasReceber[] = $id;

        return response()->json(
            [
                'success' => [
                    'message' => [
                        'Inserido com sucesso'
                    ],
                    'id' => $arrayIdsContasReceber,
                ]
            ]
        );
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param $id recebe id
     *
     * @return void
     */
    public function destroy(string $id): void {}

    public function pegarAbas(Request $request)
    {
        $tipo = null;

        if ($request->get('tipo') == 'receber-geral') {
            $data['tipo'] = null;
        }

        if ($request->get('tipo') == 'receber-pendentes') {
            $data['tipo'] = 'aberto';
        }

        if ($request->get('tipo') == 'receber-pagas') {
            $data['tipo'] = 'pago';
        }

        $data['request'] = $request->all();

        $contasReceber = new ContasReceber();

        $result = $contasReceber->pegarContasReceberPorEmpresa($data);

        foreach ($result as $data) {
            if (Utils::isDateFromDatabaseLessThanToday($data->vencimento)) {
                $vencidos[] = $data->valor;
            } else {
                $aVencer[] = $data->valor;
            }
        }

        return response()->json([
            'result' => $result,
            'vencidos' => isset($vencidos) ? array_sum($vencidos) : 0,
            'a_vencer' => isset($aVencer) ? array_sum($aVencer) : 0
        ]);
    }

    function baixarReceber(Request $request)
    {
        $receber = ReceberVencimentoValor::where('id_contas_receber', $request->get('id'))->count();

        if ($receber > 0) {
            return response()->json(['message' => ['error' => ['Essa fatura ja foi recebida anteriormente']]], 422);
        }

        $receber = ReceberVencimentoValor::create([
            'id_contas_receber' => $request->get('id'),
            'id_usuario' => auth()->user()->id,
            'status' => $request->get('divergente') == 'true' ? 'Pago divergente' : 'Pago',
            'vencimento' => $request->get('data'),
            'valor' => FormatUtils::formatMoneyDb($request->get('valor'))
        ]);

        if ($request->get('divergente') == 'true') {
            $valorPagamento = FormatUtils::formatMoneyDb($request->get('valor'));
            $registroOriginal = ContasReceber::find($request->get('id'));
            $valorProximaFatura = bcsub($registroOriginal->valor_vencimento, $valorPagamento, 4);
            $novoRegistro = $registroOriginal->replicate();
            $novoRegistro->valor_vencimento = $valorProximaFatura;
            $novoRegistro->valor_total = $valorProximaFatura;
            $novoRegistro->descricao = "Fatura referente a divergencia da fatura com id {$request->get('id')}";
            $novoRegistro->save();
        }

        return response()->json(['message' => ['success' => ['Feito o recebimento da fatura com sucesso']], 'redirect' => route('financeiro.receber')]);
    }

    public function dashboardFinanceiro()
    {
        $start = date('Y-m-01');
        $end = date('Y-m-t');
        Carbon::setLocale('pt_BR');
        $periodo = str_replace('/', ' de ', Carbon::now()->translatedFormat('F/Y'));
        $finance = new FinancesService(auth()->user()->id_empresa);
        $faturamentoBruto = $finance->calculateReceivePaymentsByPeriod($start, $end);
        $despesasFixas = $finance->calculateFixedExpensesByPeriod($start, $end);
        $custosOperacionais = $finance->calculateOperationalCostsByPeriod($start, $end);
        $deducoesImpostos = $finance->calculateTaxDeductionsByPeriod($start, $end);
        $historicoReceitaAnual = $finance->invoiceHistoryAnual();
        $historicoDespesasAnual = $finance->expensesHistoryAnual();
        return view('dashboard.index', compact(
            'periodo',
            'faturamentoBruto',
                'despesasFixas',
                'custosOperacionais',
                'deducoesImpostos',
                'deducoesImpostos',
                'historicoReceitaAnual',
                'historicoDespesasAnual'
            )
        );
    }

    public function receberLote(Request $request)
    {
        foreach ($request->get('ids') as $id) {
            $receber = ReceberVencimentoValor::create([
                'id_contas_receber' => $id,
                'id_usuario' => auth()->user()->id,
                'status' => 'Pago',
                'vencimento' => date('Y-m-d'),
                'valor' => FormatUtils::formatMoneyDb($request->get('valor'))
            ]);
        }
        return response()->json(['message' => ['success' => ['Recebimento em lote feito com sucesso']], 'redirect' => route('financeiro.receber')]);
    }

    public function fluxoCaixa($arrayDate = null)
    {
        $bancos = Bancos::where('id_empresa', auth()->user()->id_empresa)->get();
        is_null($arrayDate) ? $returnJson = false : $returnJson = true;
        is_null($arrayDate) ? $arrayDate = [date('Y-m-01'), date('Y-m-t')] : $arrayDate = [date($arrayDate.'-01'), date($arrayDate.'-t')];
        $contasReceber = (new ContasReceber())->contasReceberFluxoCaixa($arrayDate);
        $contasPagar = (new Processo())->contasPagarFluxoCaixa($arrayDate);
        // Combina as coleções e organiza por data
        $datas = $contasReceber->keys()->merge($contasPagar->keys())->unique()->sort();

        $fluxoCaixa = collect();

        $contasReceber->each(function($valor, $data) use ($contasPagar, $fluxoCaixa) {
            $pagar = $contasPagar->get($data, 0);
            $receber = $valor;
            $total = $receber - $pagar;

            $fluxoCaixa->put($data, [
                'saidas' => $pagar,
                'entradas' => $receber,
                'total' => $total,
            ]);
        });

        $contasPagar->each(function($valor, $data) use ($fluxoCaixa) {
            if (!$fluxoCaixa->has($data)) {
                $receber = 0;
                $pagar = $valor;
                $total = $receber - $pagar;

                $fluxoCaixa->put($data, [
                    'saidas' => $pagar,
                    'entradas' => $receber,
                    'total' => $total,
                ]);
            }
        });

        // Adiciona os dias que não retornaram no banco de dados com valores zerados
        $startDate = Carbon::parse($arrayDate[0]);
        $endDate = Carbon::parse($arrayDate[1]);
        $currentDate = $startDate->copy();

        while ($currentDate->lte($endDate)) {
            $data = $currentDate->format('Y-m-d');
            if (!$fluxoCaixa->has($data)) {
                $fluxoCaixa->put($data, [
                    'saidas' => 0,
                    'entradas' => 0,
                    'total' => 0,
                ]);
            }
            $currentDate->addDay();
        }

        // Ordena por data se necessário
        $fluxoCaixa = $fluxoCaixa->sortKeys();

        if($returnJson){
            return response()->json($fluxoCaixa);
        }
        return view('financeiro.fluxoCaixa.index', compact('fluxoCaixa', 'bancos', 'arrayDate'));
    }

    public function excelFluxoCaixa($data)
    {
        return Excel::download(new FluxoCaixaExports($data), 'fluxo-caixa.xlsx');
    }
}
