<?php

namespace App\Http\Controllers\SYS;

use App\Helpers\FormatUtils;
use App\Helpers\UploadFiles;
use App\Http\Controllers\Controller;
use App\Jobs\ProcessPdf;
use App\Models\ApprovedProcesso;
use App\Models\Bancos;
use App\Models\CentroCusto;
use App\Models\DRE;
use App\Models\Empresa;
use App\Models\Filial;
use App\Models\FormasPagamento;
use App\Models\Fornecedor;
use App\Models\GruposProcesso;
use App\Models\ObservacaoProcesso;
use App\Models\Pagamentos;
use App\Models\PendenciaProcesso;
use App\Models\Processo;
use App\Models\ProcessoHistorico;
use App\Models\ProcessoVencimentoValor;
use App\Models\Rateio;
use App\Models\SubCategoriaDRE;
use App\Models\TipoCobranca;
use App\Models\User;
use App\Models\WorkFlow;
use App\QueryBuilder\ProcessQueryBuilder;
use App\Services\ProcessService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\View\View;
use Nette\Utils\Random;

use function PHPUnit\Framework\isTrue;

class ProcessoController extends Controller
{
    /**
     * Exige a autenticacao do usuario
     */
    public function __construct()
    {
        $this->middleware('auth');

        set_time_limit(200);
    }

    /**
     * Display a listing of the resource.
     *
     * @return view
     */
    public function index(Request $request)
    {
        return redirect()->route('payment-request.index');

        if ($request->has('page')) {
            return $this->consultaProcesso($request);
        }

        $autocomplete = new AutocompleteController();
        $processoModel = new Processo();
        $processos = $processoModel->getProcessosPaginated();
        $processos = $this->dadosGruposPendentes($processos);
        $pendentes = $processoModel->getPendentesPaginated();
        $aprovados = $processoModel->getProcessosAprovadosPaginated();
        $processoFinanceiro = $aprovados->total();
        $qtdeNovosProcessos = $processos->total();
        $qtdeProcessosPendentes = $pendentes->total();
        $processosPagos = $processoModel->getProcessosCompletosPaginated();
        $processoPago = $processosPagos->total();
        $centrosCustos = CentroCusto::where('id_empresa', auth()->user()->id_empresa)->get();
        $rateios = Rateio::where('id_empresa', auth()->user()->id_empresa)->get();
        $filiais = Filial::where('id_empresa', auth()->user()->id_empresa)->get();
        $pendencia = false;
        return view(
            'processo.gridAllProcessos',
            compact(
                'processos',
                'pendencia',
                'qtdeNovosProcessos',
                'qtdeProcessosPendentes',
                'processoFinanceiro',
                'processoPago',
                'centrosCustos',
                'rateios',
                'filiais'
            )
        );
    }

    /**
     * Acessa dadod dos grupos que ainda não aprovaram seus processos
     *
     * @param $processos recebe um object|array do banco com os processos
     *
     * @return array|object
     */
    public function dadosGruposPendentes($processos): array|object
    {
        foreach ($processos as &$processo) {
            $autocomplete = new AutocompleteController();
            $novosGrupos = '';
            foreach (json_decode(auth()->user()->id_grupos) as $grupos) {
                $result = $autocomplete->findGrupoById(
                    [
                        'id' => $grupos,
                        'id_processo_vencimento_valor' => $processo->pvv_id,
                        'id_processo' => $processo->id
                    ]
                );
                $status = $result ? 'true' : 'false';
                $novosGrupos .= $status;
            }
            $processo->status_aprovacao = stristr($novosGrupos, 'false') ? false : true;
            unset($novosGrupos);
        }
        return $processos;
    }

    /**
     * Procura no banco prrocessos para exibir para o usuario com o requesito
     * que o usuario informar no formulario
     *
     * @param $request recebe formularrio do grid.processo
     *
     * @return view
     */
    public function processoSearch(Request $request): View
    {
        $processosModel = new Processo();
        $qtdeNovosProcessos = $processosModel->getProcessosPaginated()->total();
        $qtdeProcessosPendentes = $processosModel->getPendentesPaginated()->total();
        $processoFinanceiro = $processosModel
            ->getProcessosAprovadosPaginated()
            ->total();
        $processos = $processosModel->getProcessoByString($request->all());
        $processoPago = $processosModel->getProcessosCompletosPaginated()->total();
        $search = true;
        $pendencia = $request->get('pendencia') !== null ? true : false;
        $searchAprovado = $request->get('financeiro') !== null ? true : false;
        $novosProcessos = $request->get('pendencia') == null &&
            $request->get('financeiro') == null &&
            $request->get('finalizado') == null ? true : false;
        $qtdePago = $request->get('finalizado') !== null ? $processoPago : null;
        $nada = null;
        $centrosCustos = CentroCusto::where('id_empresa', auth()->user()->id_empresa)->get();
        $rateios = Rateio::where('id_empresa', auth()->user()->id_empresa)->get();
        $filiais = Filial::where('id_empresa', auth()->user()->id_empresa)->get();
        return view(
            'processo.grid',
            compact(
                'processos',
                'qtdeNovosProcessos',
                'qtdeProcessosPendentes',
                'processoFinanceiro',
                'processoPago',
                'search',
                'pendencia',
                'searchAprovado',
                'novosProcessos',
                $request->get('finalizado') !== null ? 'qtdePago' : 'nada',
                'centrosCustos',
                'rateios',
                'filiais'
            )
        );
    }

    public function consultaProcesso(Request $request)
    {
        $processosModel = new Processo();
        parse_str($request->get('dataForm'), $data);

        if ($request->get('tipo') == 'pendentes') {
            $data['pendencia'] = true;
        }

        if ($request->get('tipo') == 'financeiro') {
            $data['financeiro'] = true;
        }

        if ($request->get('tipo') == 'finalizado') {
            $data['finalizado'] = true;
        }

        foreach ($data as $key => $dados) {
            if ($dados == '') {
                unset($data[$key]);
            }
        }
        $processos = $processosModel->getProcessoByString($data);
        return response()->json($processos->appends($request->all()));
    }

    /**
     * Consulta os processos pendentes de aprovação
     *
     * @return view
     */
    public function pendentes(): View
    {
        $processoModel = new Processo();
        $processos = $processoModel->getPendentesPaginated();
        $novosProcessos = $processoModel->getProcessosPaginated();
        $qtdeNovosProcessos = $novosProcessos->total();
        $qtdeProcessosPendentes = $processos->total();
        $aprovados = $processoModel->getProcessosAprovadosPaginated();
        $processoFinanceiro = $aprovados->total();
        $processosPagos = $processoModel->getProcessosCompletosPaginated();
        $processoPago = $processosPagos->total();
        $centrosCustos = CentroCusto::where('id_empresa', auth()->user()->id_empresa)->get();
        $rateios = Rateio::where('id_empresa', auth()->user()->id_empresa)->get();
        $pendencia = true;
        $filiais = Filial::where('id_empresa', auth()->user()->id_empresa)->get();
        return view(
            'processo.gridPendentesProcessos',
            compact(
                'processos',
                'pendencia',
                'qtdeNovosProcessos',
                'qtdeProcessosPendentes',
                'processoFinanceiro',
                'processoPago',
                'centrosCustos',
                'rateios',
                'filiais'
            )
        );
    }

    /**
     * Consulta processos finalizados e pagos
     *
     * @return view
     */
    public function processoCompleto(Request $request)
    {
        if ($request->has('page')) {
            return $this->consultaProcesso($request);
        }
        $processoInstance = new Processo();
        $processos = $processoInstance->getProcessosCompletosPaginated();
        $qtdePago = $processos->total();
        $processosNovos = $processoInstance->getProcessosPaginated();
        $pendentes = $processoInstance->getPendentesPaginated();
        $qtdeNovosProcessos = $processosNovos->total();
        $qtdeProcessosPendentes = $pendentes->total();
        $processoAprovados = $processoInstance->getProcessosAprovadosPaginated();
        $processoFinanceiro = $processoAprovados->total();
        $centrosCustos = CentroCusto::where('id_empresa', auth()->user()->id_empresa)->get();
        $rateios = Rateio::where('id_empresa', auth()->user()->id_empresa)->get();
        $filiais = Filial::where('id_empresa', auth()->user()->id_empresa)->get();
        return view(
            'processo.gridFinalizadoProcessos',
            compact(
                'processos',
                'qtdeNovosProcessos',
                'qtdeProcessosPendentes',
                'processoFinanceiro',
                'qtdePago',
                'centrosCustos',
                'rateios',
                'filiais'
            )
        );
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return view
     */
    public function create()
    {
        $instaceWorkflow = new WorkFlow();
        $empresas = [];
        $user = User::find(auth()->user()->id);
        $ccsChecked = [];
        $ccs = CentroCusto::where('id_empresa', auth()->user()->id_empresa)->get();
        $centrosCusto = $ccs;
        $workflow = $instaceWorkflow->getWorkFlow();
        $rateios = Rateio::where('id_empresa', auth()->user()->id_empresa)->get();
        $tipo_cobranca = $user->master == true ? TipoCobranca::all() : TipoCobranca::where('id_empresa', auth()->user()->id_empresa)->get();
        $subDre = SubCategoriaDRE::select('sub_categoria_dre.id as sub_id', 'sub_categoria_dre.descricao as sub_desc')
            ->leftJoin('dre', 'sub_categoria_dre.id_dre', 'dre.id')
            ->where('sub_categoria_dre.id_empresa', auth()->user()->id_empresa)
            ->where('dre.tipo', 'despesa')
            ->orWhere('dre.tipo', 'despesa')
            ->whereNull('sub_categoria_dre.id_empresa')
            ->where('tipo', 'despesa')
            ->get();
        $dres = DRE::where('id_empresa', auth()->user()->id_empresa)->where('tipo', 'despesa')->orWhereNull('id_empresa')->where('tipo', 'despesa')->get();

        $empresas = $user->master == true ? Empresa::all() : Empresa::find(auth()->user()->id_empresa);

        return view(
            'processo.form',
            compact(
                'ccs',
                'ccsChecked',
                'workflow',
                'empresas',
                'tipo_cobranca',
                'centrosCusto',
                'rateios',
                'subDre',
                'dres'
            )
        );
    }

    public function storeImposto($request)
    {
        $messages = [
            'numero_nota.required' => 'Verificar na nota o numero da nota, campo obrigatorio',
            'emissao_nota.required' => 'Data da emissão da nota é obrigatorio',
            'valor.required' => 'Valor da nota precisa ser preenchido',
            'parcela.required' => 'Quantidade de parcelas precisa de um minimo de numero 1',
            'categoria_financeira.required' => 'Precisa escolher alguma categoria financeira'
        ];

        $validator = Validator::make(
            $request->all(),
            [
                'numero_nota' => 'required',
                'emissao_nota' => 'required',
                'valor' => 'required',
                'parcela' => 'required',
                'categoria_financeira' => 'required'
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

        if ($request->file('files') == null and $request->get('enviarSemArquivos') == null) {
            return response()->json(
                [
                    'errors' => [
                        'mensagem' => [
                            'Sem arquivos'
                        ]
                    ]
                ],
                422
            );
        }

        $vencimento2valor = $this->dataVencimentoValor($request);

        if ($request->file('files') !== null && count($request->file('files')) > 0) {
            foreach ($request->file('files') as $file) {
                $fileRenamed = time() . $file->getClientOriginalName();
                $files[] = $fileRenamed;
            }
            $upload = $this->uploadRenamed($files, $request->file('files'));
            if (isset($upload['error'])) {
                return redirect()->back()->with('error', 'não foi possivel salvar o arquivo fale com o administrador');
            }
        }

        $data['id_empresa'] = auth()->user()->id_empresa;
        $data['doc_name'] = isset($files) ? json_encode($files) : json_encode(['teste.pdf']);
        $data['id_fornecedor'] = 0;
        $data['id_user'] = auth()->user()->id;
        $data['user_ultima_alteracao'] = $data['id_user'];
        $data['created_user'] = $data['id_user'];
        $data['logs'] = json_encode(['data']);
        $data['trace_code'] = $this->traceCode();
        $data['id_centro_custo'] = $request->get('id_centro_custo') == 'cadCentroCusto' ? null : $request->get('id_centro_custo');
        $data['id_rateio'] = $request->get('id_rateio') == 'addRateioCentroCusto' ? null : $request->get('id_rateio');
        $data['id_filial'] = preg_match('/^\d+$/', $request->get('id_filial')) === 1 ? $request->get('id_filial') : null;
        $data['id_sub_dre'] = $request->get('categoria_financeira');
        $data['competencia'] = $request->get('competencia');
        $data['imposto'] = true;
        $data['id_workflow'] = $request->get('flow');
        $data['valor'] = FormatUtils::formatMoneyDb($request->get('valor'));
        $data['numero_nota'] = $request->get('numero_nota');
        $data['emissao_nota'] = $request->get('emissao_nota');
        $data['tipo_cobranca'] = $request->get('tipo_cobranca');
        $data['condicao'] = $request->get('condicao');
        $data['parcelas'] = $request->get('parcela');
        $data['dt_parcelas'] = json_encode($vencimento2valor);

        $processo = Processo::create($data);

        for ($i = 0; $i < count($vencimento2valor) / 2; $i++) {
            $dataKey = 'data' . $i;
            $valorKey = 'valor' . $i;

            // Verificar se existem as chaves correspondentes "data" e "valor"
            if (array_key_exists($dataKey, $vencimento2valor) && array_key_exists($valorKey, $vencimento2valor)) {
                $data = $vencimento2valor[$dataKey];
                $valor = FormatUtils::formatMoneyDb($vencimento2valor[$valorKey]);
                // Adicionar o par [data, valor] à matriz
                $pares = [
                    'id_processo' => $processo->id,
                    'data_vencimento' => $data,
                    'price' => $valor
                ];
                $processoVencimentoValor = ProcessoVencimentoValor::create($pares);
            }
        }

        if(isset($upload['success'])){
            ProcessPdf::dispatch($processo->id);
        }

        if ($processo) {
            return redirect('processo')->with(
                'success',
                "Salvo com sucesso processo n: {$processo->id}"
            );
        }
        return redirect()->back()->with(
            'error',
            'Não foi possivel salvar, favor tratar com o administrador'
        );
    }

    public function dataVencimentoValor($request)
    {
        $vencimentoValor = [];
        foreach ($request->all() as $key => $teste) {
            if (stristr($key, 'data')) {
                $vencimentoValor[$key] = $teste;
            }
            if (preg_match('/valor\d+$/', $key)) {
                $vencimentoValor[$key] = $teste;
            }
        }
        return $vencimentoValor;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param $request recebe formulario create
     *
     * @return RedirectResponse|JsonResponse
     */
    public function store(Request $request): RedirectResponse|JsonResponse
    {
        if ($request->has('imposto')) {
            return $this->storeImposto($request);
        }

        $messages = [
            'name.required' => 'Precisa pesquisar e inserir os dados do fornecedor, pode pesquisar por CPF/CNPJ ou nome',
            'numero_nota.required' => 'Verificar na nota o numero da nota, campo obrigatorio',
            'emissao_nota.required' => 'Data da emissão da nota é obrigatorio',
            'valor.required' => 'Valor da nota precisa ser preenchido',
            'parcela.required' => 'Quantidade de parcelas precisa de um minimo de numero 1',
            'categoria_financeira.required' => 'Precisa escolher alguma categoria financeira'
        ];

        $validator = Validator::make(
            $request->all(),
            [
                'name' => 'required',
                'numero_nota' => 'required',
                'emissao_nota' => 'required',
                'valor' => 'required',
                'parcela' => 'required',
                'categoria_financeira' => 'required'
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

        $fornecedor = explode('-', $request->get('name'));
        $id_fornecedor = trim($fornecedor[0]);

        if (!Fornecedor::where('id', $id_fornecedor)->where('id_empresa', auth()->user()->id_empresa)->exists()) {
            return response()->json(
                [
                    'errors' => [
                        'mensagem' => [
                            'O fornecedor não existe no banco de dados'
                        ]
                    ]
                ],
                422
            );
        }

        if ($request->file('files') == null and $request->get('enviarSemArquivos') == null) {
            return response()->json(
                [
                    'errors' => [
                        'mensagem' => [
                            'Sem arquivos'
                        ]
                    ]
                ],
                422
            );
        }

        $data = $request->only('numero_nota', 'emissao_nota', 'condicao', 'tipo_cobranca', 'observacao');
        $data['parcelas'] = $request->get('parcela');
        $data['id_workflow'] = $request->get('flow');
        $data['valor'] = FormatUtils::formatMoneyDb($request->get('valor'));

        $data['dt_parcelas'] = json_encode(
            $request->except(
                '_token',
                'name',
                'numero_nota',
                'emissao_nota',
                'competencia',
                'valor',
                'condicao',
                'parcela',
                'tipo_cobranca',
                'flow',
                'observacao',
                'filial',
                'categoria_financeira'
            )
        );

        if ($request->file('files') !== null && count($request->file('files')) > 0) {
            foreach ($request->file('files') as $file) {
                $fileRenamed = time() . $file->getClientOriginalName();
                $files[] = $fileRenamed;
            }
            $upload = $this->uploadRenamed($files, $request->file('files'));
            if (isset($upload['error'])) {
                return redirect()->back()->with('error', 'não foi possivel salvar o arquivo fale com o administrador');
            }
            $filesTypesAndDesc = $this->fileDescAndType($files, $request->get('tipo_anexo'), $request->get('descricao_arquivo'));
        }

        $data['id_empresa'] = auth()->user()->id_empresa;
        $data['doc_name'] = isset($files) ? json_encode($files) : json_encode(['teste.pdf']);
        $data['id_fornecedor'] = $id_fornecedor;
        $data['id_user'] = auth()->user()->id;
        $data['user_ultima_alteracao'] = $data['id_user'];
        $data['created_user'] = $data['id_user'];
        $data['logs'] = json_encode(['data']);
        $data['trace_code'] = $this->traceCode();
        $data['id_centro_custo'] = $request->get('id_centro_custo') == 'cadCentroCusto' ? null : $request->get('id_centro_custo');
        $data['id_rateio'] = $request->get('id_rateio') == 'addRateioCentroCusto' ? null : $request->get('id_rateio');
        $data['id_filial'] = preg_match('/^\d+$/', $request->get('id_filial')) === 1 ? $request->get('id_filial') : null;
        $data['id_sub_dre'] = $request->get('categoria_financeira');
        $data['competencia'] = $request->get('competencia');
        $data['files_types_desc'] = isset($filesTypesAndDesc) ? json_encode($filesTypesAndDesc) : null;

        $processo = Processo::create($data);

        $obj = json_decode($data['dt_parcelas'], true);

        for ($i = 0; $i < count($obj) / 2; $i++) {
            $dataKey = 'data' . $i;
            $valorKey = 'valor' . $i;

            // Verificar se existem as chaves correspondentes "data" e "valor"
            if (array_key_exists($dataKey, $obj) && array_key_exists($valorKey, $obj)) {
                $data = $obj[$dataKey];
                $valor = FormatUtils::formatMoneyDb($obj[$valorKey]);
                // Adicionar o par [data, valor] à matriz
                $pares = [
                    'id_processo' => $processo->id,
                    'data_vencimento' => $data,
                    'price' => $valor
                ];
                $processoVencimentoValor = ProcessoVencimentoValor::create($pares);
            }
        }

        if(isset($upload['success'])){
            ProcessPdf::dispatch($processo->id);
        }

        if ($processo and $processoVencimentoValor) {
            return redirect('processo')->with(
                'success',
                "Salvo com sucesso processo n: {$processo->id}"
            );
        }
        return redirect()->back()->with(
            'error',
            'Não foi possivel salvar, favor tratar com o administrador'
        );
    }

    public function fileDescAndType($files, $type, $desc)
    {
        $i = 0;
        $teste = [];
        foreach ($files as $file) {
            $teste[] = [
                'fileName' => $file,
                'fileType' => $type[$i],
                'fileDesc' => $desc[$i]
            ];
            $i++;
        }
        return $teste;
    }

    /**
     * Cria o rastreio para informar na tabela.
     *
     * @return string
     */
    public function traceCode(): string
    {
        $empresa = Empresa::find(auth()->user()->id_empresa);
        $filial = '00';
        $random = Random::generate(7);
        return substr($empresa->nome, 0, 2) . $filial . strtoupper($random);
    }

    /**
     * Faz upload do aquivo para o processo
     *
     * @param $nameFiles    nomes dos arquivos
     * @param $requestFiles arquivos vindos do formulario
     *
     * @return array
     */
    public function uploadRenamed(array|object $nameFiles, array|object $requestFiles): array
    {
        $upload = new UploadFiles();
        $i = 0;
        foreach ($requestFiles as $requestFile) {
            $base64 =file_get_contents($requestFile->getRealPath());
            $statusUpload[] = $upload->uploadToR2("uploads/$nameFiles[$i]", $base64);
            $i++;
        }
        foreach($statusUpload as $status){
            if(!$status){
                return [
                    'error' => 'Erro ao salvar arquivo'
                ];
            }
        }
        return [
            'success' => 'Arquivos salvos com sucesso',
        ];
    }

    /**
     * Faz upload do aquivo para o processo
     *
     * @param $request nomes dos arquivos
     *
     * @return void|array
     */
    public function uploadComprovante(Request $request): array
    {
        $files = [];
        if ($request->has('files')) {
            foreach ($request->file('files') as $file) {
                $fileRenamed = time() . $file->getClientOriginalName();
                $files[] = $fileRenamed;
            }

            $this->atualizarArquivosProcesso($request->get('id_processo'), $files);

            $upload = $this->uploadRenamed($files, $request->file('files'));
            if (isset($upload['success'])) {
                return $files;
            }
            return $upload;
        }

        if ($request->has('_files')) {
            foreach ($request->file('_files') as $file) {
                $fileRenamed = time() . $file->getClientOriginalName();
                $files[] = $fileRenamed;
            }

            $this->atualizarArquivosProcesso($request->get('id_processo'), $files);

            $upload = $this->uploadRenamed($files, $request->file('_files'));
            if (isset($upload['success'])) {
                return $files;
            }
            return $upload;
        }
        return [];
    }

    /**
     * Faz upload do aquivo para o processo
     *
     * @param $request request de formulario com arquivos
     *
     * @return void|array
     */
    public function upload(Request $request): array
    {
        $files = [];
        foreach ($request->file('files') as $file) {
            $fileRenamed = time() . $file->getClientOriginalName();
            $files[] = $fileRenamed;
        }

        $upload = $this->uploadRenamed($files, $request->file('files'));

        $history = new ProcessoHistorico();
        $history->createHistory($request->get('id_processo'), 'Inseriu novos arquivos');
        $this->removerAprovacao($request);

        $resultAtualizarArquivosProcesso = $this->atualizarArquivosProcesso($request->get('id_processo'), $files);
        return array_merge($upload, $resultAtualizarArquivosProcesso);
    }

    /**
     * Atualiza arquivos no banco quando inserido um novo arquivo
     *
     * @param $id    id do processo
     * @param $files arquivos do processo
     *
     * @return void|array
     */
    public function atualizarArquivosProcesso(string $id, array $files): array
    {
        $processo = Processo::find($id);
        $doc_name = json_decode($processo->doc_name, true);
        if ($doc_name[0] == 'teste.pdf') {
            unset($doc_name[0]);
        }
        $result = array_merge($doc_name, $files);
        Processo::where('id', $id)->update(['doc_name' => json_encode($result)]);
        return $result;
    }

    public function show(string $id)
    {
    }

    /**
     * Atualiza arquivos no banco quando inserido um novo arquivo
     *
     * @return bool
     */
    public function validarVizualizacao(): bool
    {
        $user = User::find(auth()->user()->id);
        return ($user->id_grupos == null && $user->master == false) ? ($user->financeiro == true ? true : false) : true;
    }

    /**
     * Verifica permissao do usuario para adicionar a sessão
     *
     * @return array
     */
    public function processoUserPermission()
    {
        User::userPermissions();
        $permissoes = null;
        foreach (session('permissions') as $item) {
            // Verifique cada permissão individualmente
            if ($item->grupo_criar_usuario) {
                $permissoes['grupo_criar_usuario'] = true;
            }
            if ($item->grupo_move_processo) {
                $permissoes['grupo_move_processo'] = true;
            }
            if ($item->grupo_deleta_processo) {
                $permissoes['grupo_deleta_processo'] = true;
            }
            if ($item->grupo_criar_fluxo) {
                $permissoes['grupo_criar_fluxo'] = true;
            }
        }
        return $permissoes = isset($permissoes) ? $permissoes : null;
    }

    /**
     * Display the specified resource.
     *
     * @param $id   id do processo
     * @param $data data de vencimento do processo
     *
     * @return view
     */
    public function showAprovacao(string $id, string $data): View|RedirectResponse
    {
        if (!$this->validarVizualizacao()) {
            return redirect()
                ->back()
                ->with('error', 'Seu usuario não pode acessar esse processo');
        }

        $getProcesso = new Processo();
        $observacaoProcesso = new ObservacaoProcesso();
        $pendenciaProcesso = new PendenciaProcesso();
        $processo = $getProcesso->getProcessoShow($id, $data);
        $userPermission = $this->processoUserPermission();
        $vencimentosPagoProcesso = $getProcesso->getDadosVencimentoPago($id, 3);
        $vencimentosAbertoProcesso = $getProcesso->getDadosVencimentoAberto($id, 3);
        $combinedArray = array_merge(
            json_decode($vencimentosPagoProcesso, true),
            json_decode($vencimentosAbertoProcesso, true)
        );
        $meses = json_encode(array_keys($combinedArray));
        $maiorValor = max($combinedArray);
        $documentos = array();
        foreach (json_decode($processo->doc_name) as $doc_name) {
            array_push(
                $documentos,
                [
                    'url' => asset("uploads/{$doc_name}"), 'pageNum' => 1
                ]
            );
        }
        $history = new ProcessoHistorico();
        $history->createHistory($id, 'Vizualizou o processo');
        $historico = $history->getHistory($id);
        $observacao = $observacaoProcesso->getObservacaoProcessoByProcessoId($id);
        $pendencias = $pendenciaProcesso->getObservacaoPendenciaByProcessoId($id);
        $workflow = WorkFlow::select('id', 'nome')->get();
        $empresas = Empresa::all();
        $tipo_cobranca = TipoCobranca::all();
        $originalDocs = json_decode($processo->doc_name);
        if($processo->number_of_pages == null || $processo->number_of_pages == "null"){
            ProcessPdf::dispatch($processo->id);
        }
        $processQueryBuilder = new ProcessQueryBuilder();
        $processService = new ProcessService($processQueryBuilder);
        $arrayFiles = (new PdfController($processService))->pagesPerPdf($processo->number_of_pages);
        $bancos = Bancos::where('id_empresa', auth()->user()->id_empresa)
            ->where('deletado', false)
            ->get();
        $formas_pagamento = FormasPagamento::all();
        if(auth()->user()->id_empresa == $processo->e_id){
            return view(
                'processo.show',
                compact(
                    'processo',
                    'documentos',
                    'historico',
                    'observacao',
                    'vencimentosPagoProcesso',
                    'vencimentosAbertoProcesso',
                    'maiorValor',
                    'pendencias',
                    'workflow',
                    'empresas',
                    'tipo_cobranca',
                    'arrayFiles',
                    'bancos',
                    'userPermission',
                    'formas_pagamento',
                    'meses',
                    'originalDocs'
                )
            );
        }

        return redirect()->route('processo.index');

    }

    public function pdf2png($array)
    {
        $files = array();
        foreach ($array as $data) {
            for ($i = 0; $i < $data['pages']; $i++) {
                $file = substr($data['file'], 0, -4);
                $page = $i + 1;
                array_push($files, "page_{$page}_{$file}.png");
            }
        }
        return $files;
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $instanceWorkflow = new WorkFlow();
        $user = User::find(auth()->user()->id);

        $processo = Processo::select(
            'processo.id as id',
            'processo.id_fornecedor as id_fornecedor',
            'fornecedor.nome as f_nome',
            'fornecedor.cpf_cnpj as cpf_cnpj',
            'processo.id_workflow as id_workflow',
            'processo.numero_nota as numero_nota',
            'processo.emissao_nota as emissao_nota',
            'processo.valor as valor',
            'processo.tipo_cobranca as tipo_cobranca',
            'processo.condicao as condicao',
            'processo.parcelas as parcelas',
            'processo.dt_parcelas as dt_parcelas',
            'processo.user_ultima_alteracao as user_ultima_alteracao',
            'processo.doc_name as doc_name',
            'processo.observacao as observacao',
            'processo.dt_parcelas as dt_parcelas',
            'tipo_cobranca.id as tc_id',
            'tipo_cobranca.nome as tc_nome',
            'sub_categoria_dre.id as sub_dre_id',
            'sub_categoria_dre.descricao as sub_dre_desc'
        )
            ->leftJoin('fornecedor', 'processo.id_fornecedor', 'fornecedor.id')
            ->leftJoin('tipo_cobranca', 'tipo_cobranca.id', 'processo.tipo_cobranca')
            ->leftJoin('sub_categoria_dre', 'sub_categoria_dre.id', 'processo.id_sub_dre')
            ->find($id);

        if (!$this->validarVizualizacao()) {
            return redirect()->back()->with('error', 'Seu usuario não pode acessar esse processo');
        }

        $workflow = $instanceWorkflow->find($processo->id_workflow);

        $tipo_cobranca = TipoCobranca::all();

        $empresas = $user->master == true ? Empresa::all() : null;

        return view('processo.form', compact('processo', 'workflow', 'empresas', 'tipo_cobranca'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {

        $messages = [
            'name.required' => 'Precisa pesquisar e inserir os dados do fornecedor, pode pesquisar por CPF/CNPJ ou nome',
            'numero_nota.required' => 'Verificar na nota o numero da nota, campo obrigatorio',
            'emissao_nota.required' => 'Data da emissão da nota é obrigatorio',
            'valor.required' => 'Valor da nota precisa ser preenchido',
            'parcela.required' => 'Quantidad de parcelas precisa de um minimo de numero 1',
            'competencia.required' => 'Precisa colocar a competencia',
            'categoria_financeira.required' => 'Precisa selecionar a categoria financeira'
        ];

        $rules = [
            'numero_nota' => 'required',
            'emissao_nota' => 'required',
            'valor' => 'required',
            'parcela' => 'required',
            'categoria_financeira' => 'required',
            'competencia' => 'required'
        ];

        if (!$request->has('imposto') && isTrue($request->get('imposto'))) {
            $rules['name'] = 'required';
        }

        $validator = Validator::make(
            $request->all(),
            $rules,
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

        $fornecedor = explode('-', $request->get('name'));
        $id_fornecedor = trim($fornecedor[0]);
        if (!$request->has('imposto') && isTrue($request->get('imposto'))) {
            if (!Fornecedor::where('id', $id_fornecedor)->where('id_empresa', auth()->user()->id_empresa)->exists()) {
                return response()->json(
                    [
                        'errors' => [
                            'mensagem' => [
                                'O fornecedor não existe no banco de dados'
                            ]
                        ]
                    ],
                    422
                );
            }
        }

        if ($request->file('files') == null and $request->get('enviarSemArquivos') == null) {
            return response()->json(
                [
                    'errors' => [
                        'mensagem' => [
                            'Sem arquivos'
                        ]
                    ]
                ],
                422
            );
        }

        $data = $request->only('numero_nota', 'emissao_nota', 'condicao', 'tipo_cobranca', 'observacao');
        $data['parcelas'] = $request->get('parcela');
        $data['id_workflow'] = $request->get('flow');
        $data['valor'] = FormatUtils::formatMoneyDb($request->get('valor'));

        $data['dt_parcelas'] = json_encode(
            $request->except(
                '_token',
                'name',
                'numero_nota',
                'emissao_nota',
                'competencia',
                'valor',
                'condicao',
                'parcela',
                'tipo_cobranca',
                'flow',
                'observacao',
                '_method',
                'competencia',
                'filial',
                'categoria_financeira'
            )
        );

        if ($request->file('files') !== null && count($request->file('files')) > 0) {
            foreach ($request->file('files') as $file) {
                $fileRenamed = time() . $file->getClientOriginalName();
                $files[] = $fileRenamed;
            }
            $upload = $this->uploadRenamed($files, $request->file('files'));
            if (isset($upload['error'])) {
                return redirect()->back()->with('error', 'não foi possivel salvar o arquivo fale com o administrador');
            }
            $filesTypesAndDesc = $this->fileDescAndType($files, $request->get('tipo_anexo'), $request->get('descricao_arquivo'));
            $resultAtualizarArquivosProcesso = $this->atualizarArquivosProcesso($id, $files);
        }

        if ($request->has('tableData')) {
            $oldFiles = json_decode($request->get('tableData'));
            $newFiles = isset($filesTypesAndDesc) ? $filesTypesAndDesc : [];
            $filesTypesAndDesc = array_merge($oldFiles, $newFiles);
        }

        $processo = Processo::where('id', $id)->update(
            [
                'id_workflow' => $request->get('flow'),
                'numero_nota' => $request->get('numero_nota'),
                'emissao_nota' => $request->get('emissao_nota'),
                'valor' => FormatUtils::formatMoneyDb($request->get('valor')),
                'tipo_cobranca' => $request->get('tipo_cobranca'),
                'condicao' => $request->get('condicao'),
                'parcelas' => $request->get('parcela'),
                'dt_parcelas' => $data['dt_parcelas'],
                'user_ultima_alteracao' => auth()->user()->id,
                'observacao' => $request->get('observacao'),
                'id_filial' => $request->get('filial'),
                'id_sub_dre' => $request->get('categoria_financeira'),
                'competencia' => $request->get('competencia'),
                'files_types_desc' => isset($filesTypesAndDesc) ? json_encode($filesTypesAndDesc) : null
            ]
        );

        $obj = json_decode($data['dt_parcelas'], true);
        ProcessoVencimentoValor::where('id_processo', $id)->whereNull('pago')->delete();

        for ($i = 0; $i < count($obj) / 2; $i++) {
            $dataKey = 'data' . $i;
            $valorKey = 'valor' . $i;

            // Verificar se existem as chaves correspondentes "data" e "valor"
            if (array_key_exists($dataKey, $obj) && array_key_exists($valorKey, $obj)) {
                $data = $obj[$dataKey];
                $valor = FormatUtils::formatMoneyDb($obj[$valorKey]);
                // Adicionar o par [data, valor] à matriz
                $pares = [
                    'id_processo' => $id,
                    'data_vencimento' => $data,
                    'price' => $valor
                ];
                $validaProcessoVencimentoValor = ProcessoVencimentoValor::where('id_processo', $id)
                    ->where('data_vencimento', $data)
                    ->first();

                if ($validaProcessoVencimentoValor == null) {
                    $processoVencimentoValor = ProcessoVencimentoValor::create($pares);
                }
            }
        }

        if ($processo) {
            return redirect('processo')->with(
                'success',
                "Salvo com sucesso processo n: {$id}"
            );
        }
        return redirect()->back()->with(
            'error',
            'Não foi possivel salvar, favor tratar com o administrador'
        );
    }

    public function updateProcessoVencimentoValor(Request $request)
    {
        $data = explode('&', $request->get('data'));
        foreach ($data as $value) {
            $elements = explode('=', $value);
            $array[$elements[0]] = urldecode($elements[1]);  // str_replace(['%2C','%20'],[',', ' '], $elements[1]);
        }

        $array['id'] = $request->get('id');

        $messages = [
            'numero_nota.required' => 'Verificar na nota o numero da nota, campo obrigatorio',
            'emissao_nota.required' => 'Data da emissão da nota é obrigatorio',
            'valor.required' => 'Valor da nota precisa ser preenchido',
            'parcela.required' => 'Quantidade de parcelas precisa de um minimo de numero 1',
            'condicao.required' => 'Selecione a quantidade'
        ];

        $validator = Validator::make($array, [
            'numero_nota' => 'required',
            'emissao_nota' => 'required',
            'valor' => 'required',
            'parcela' => 'required',
            'condicao' => 'required'
        ], $messages);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $dataVencimentoValor = FormatUtils::except($array, [
            '_method',
            '_token',
            'numero_nota',
            'emissao_nota',
            'tipo_cobranca',
            'flow', 'observacao',
            'valor',
            'parcela',
            'condicao',
            'id'
        ]);

        if ((count($dataVencimentoValor) / 2) !== (int) $array['parcela']) {
            $qtdeVencimentoValor = count($dataVencimentoValor) / 2;
            return response()->json(['errors' => ['parcelas' => ['Numero de datas e valores não corresponde com o numero de parcelas']]], 422);
        }

        $array['dt_parcelas'] = json_encode($dataVencimentoValor);

        Processo::where('id', $array['id'])->update([
            'id_workflow' => $array['flow'],
            'numero_nota' => $array['numero_nota'],
            'emissao_nota' => $array['emissao_nota'],
            'valor' => FormatUtils::formatMoneyDb($array['valor']),
            'tipo_cobranca' => $array['tipo_cobranca'],
            'condicao' => $array['condicao'],
            'parcelas' => $array['parcela'],
            'dt_parcelas' => $array['dt_parcelas'],
            'user_ultima_alteracao' => auth()->user()->id,
            'observacao' => $array['observacao'],
        ]);

        ProcessoVencimentoValor::where('id_processo', $array['id'])->delete();

        for ($i = 0; $i < count($dataVencimentoValor) / 2; $i++) {
            $dataKey = 'data' . $i;
            $valorKey = 'valor' . $i;

            // Verificar se existem as chaves correspondentes "data" e "valor"
            if (array_key_exists($dataKey, $dataVencimentoValor) && array_key_exists($valorKey, $dataVencimentoValor)) {
                $data = $dataVencimentoValor[$dataKey];
                $valor = $dataVencimentoValor[$valorKey];
                // Adicionar o par [data, valor] à matriz
                $pares = [
                    'id_processo' => $array['id'],
                    'data_vencimento' => $data,
                    'price' => FormatUtils::formatMoneyDb($valor)
                ];
                $processoVencimentoValor = ProcessoVencimentoValor::create($pares);
            }
        }

        $history = new ProcessoHistorico();
        $history->createHistory($array['id'], 'Editou campos do processo');
        return response()->json(['success' => 'success', 'msg' => 'Atualizado com sucesso']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, string $id)
    {
        Processo::where('id', $id)->update([
            'deletado' => true,
            'observacao' => $request->get('observacao')
        ]);

        $history = new ProcessoHistorico();
        $history->createHistory($id, "Deletou o processo: com a observação {$request->get('observacao')}");
        return response()->json(json_encode(['success' => true, 'msg' => 'Deletado com sucesso']));
    }

    public function observacao(Request $request)
    {
        $observacao = new ObservacaoProcesso();
        $insertObservacao = $observacao->createObservacaoProcesso(
            $request->get('id'),
            auth()->user()->id,
            $request->get('observacao')
        );
        $result = $observacao->getObservacaoProcesso($insertObservacao->id);
        $history = new ProcessoHistorico();
        $history->createHistory($request->get('id'), "Adicionou o comentario id: {$insertObservacao->id}");

        return $result;
    }

    public function validaDocumento(Request $request)
    {
        $users = User::where('id', auth()->user()->id)->whereJsonContains('id_grupos', $request->get('id_grupo'))->get();
        if ($users->count() == 0) {
            return ['error' => 'Seu usuario não pode validar esse grupo'];
        }

        ApprovedProcesso::create([
            'id_processo' => $request->get('id_processo'),
            'id_usuario' => auth()->user()->id,
            'id_grupo' => $request->get('id_grupo'),
            'id_processo_vencimento_valor' => $request->get('id_processo_vencimento_valor')
        ]);
        $grupo = GruposProcesso::find($request->get('id_grupo'));
        $historico = new ProcessoHistorico();
        $historico->createHistory($request->get('id_processo'), "Aprovacao do grupo {$grupo->nome}");
        $this->aprovaProcesso($request->get('id_processo'), $request->get('id_processo_vencimento_valor'));
        return ['success' => 'Atualizado com sucesso'];
    }

    public function aprovaProcesso($id, $id_processo_vencimento_valor)
    {
        $processo = Processo::find($id);
        $workflow = WorkFlow::find($processo->id_workflow);
        $aprovacoesNecessarias = count(json_decode($workflow->id_grupos));
        $approved = 0;

        foreach (json_decode($workflow->id_grupos) as $idGrupos) {

            $gruposAprovados = ApprovedProcesso::where('id_processo', $id)
                ->where('id_processo_vencimento_valor', $id_processo_vencimento_valor)
                ->where('id_grupo', $idGrupos)
                ->get();

            if ($gruposAprovados->count() == 0) {
                $approved--;
            } else {
                $approved++;
            }
        }

        if ($approved == $aprovacoesNecessarias) {
            ProcessoVencimentoValor::where('id', $id_processo_vencimento_valor)->update([
                'aprovado' => true
            ]);
            return;
        }
        return;
    }

    public function removerAprovacao(Request $request)
    {
        $processoPago = ProcessoVencimentoValor::select('pago')
            ->where('id', $request->get('id_processo_vencimento_valor'))
            ->first();
        if ($processoPago->pago) {
            return;
        }
        return ApprovedProcesso::where('id_processo', $request->get('id_processo'))
            ->where('id_processo_vencimento_valor', $request->get('id_processo_vencimento_valor'))
            ->delete();
    }

    public function pendenciaProcesso(Request $request)
    {
        $messages = [
            'observacao.required' => 'Observação campo obrigatorio',
            'observacao.min' => 'O minimo de caracteres para observação é 20',
            'id_usuario_email.required' => 'É nescessario enviar pelo menos um email'
        ];

        $validator = Validator::make($request->all(), [
            'observacao' => 'required|min:20',
            'id_usuario_email' => 'required',
        ], $messages);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $historico = new ProcessoHistorico();

        $processo = Processo::where('id', $request->get('id_processo'))->update([
            'pendencia' => true
        ]);

        ProcessoVencimentoValor::where('id_processo', $request->get('id_processo'))->whereNull('pago')->update([
            'aprovado' => false
        ]);

        if (!$processo) {
            return response()->json(json_encode(['error', 'Não foi possivel salvar no banco de dados favor tratar com o administrador']));
        }

        $approvedRemoval = ApprovedProcesso::where('id_processo', $request->get('id_processo'))
            ->where('id_processo_vencimento_valor', $request->get('id_processo_vencimento_valor'))
            ->delete();

        if (!$approvedRemoval) {
            // return response()->json(json_encode(['error', 'Não foi possivel mudar as aprovaçoes no banco de dados']));
        }
        $data = $request->except('_token');
        foreach ($data['id_usuario_email'] as $jsonString) {
            $object[] = json_decode($jsonString);
        }
        $data['id_usuario_email'] = json_encode($object);
        $data['id_usuario'] = auth()->user()->id;
        $pendenciaProcesso = PendenciaProcesso::create($data);

        if (!$pendenciaProcesso) {
            return response()->json(json_encode(['error', 'Não foi possivel criar a pendencia na tabela']));
        }

        $historico->createHistory($request->get('id_processo'), 'Esta pendenciando este processo');

        return response()->json(json_encode(['success', 'Feita as alteracoes para avisar os usuarios']));
    }

    public function retiraPendenciaProcesso(Request $request)
    {
        $historico = new ProcessoHistorico();
        $processo = Processo::where('id', $request->get('id_processo'))->update([
            'pendencia' => false
        ]);

        if (!$processo) {
            return response()->json(json_encode(['error', 'Não foi possivel salvar no banco de dados favor tratar com o administrado']));
        }

        $data = $request->except('_token');
        foreach ($data['id_usuario_email'] as $jsonString) {
            $object[] = json_decode($jsonString);
        }
        $data['id_usuario_email'] = json_encode($object);
        $data['id_usuario'] = auth()->user()->id;
        $pendenciaProcesso = PendenciaProcesso::create($data);

        if (!$pendenciaProcesso) {
            return response()->json(json_encode(['error', 'Não foi possivel criar a poendencia na tabela']));
        }

        $historico->createHistory($request->get('id_processo'), 'Esta pendenciando este processo');

        return response()->json(json_encode(['success', 'Feita as alteracoes para avisar os usuarios']));
    }

    public function inserirPagamentoNaTabelaPagamento($data, $comprovantes)
    {
        $data['valor_pago'] = FormatUtils::formatMoneyDb($data['valor_pago']);
        $data['id_banco'] = $data['id_banco'] == 'especie' ? 0 : $data['id_banco'];
        $data['comprovantes'] = json_encode($comprovantes);
        $pagamento = Pagamentos::create($data);
        if (!$pagamento) {
            return response()->json(['errors' => ['mensagem' => ['Erro ao adicionar dados no banco, favor informar o administrador']]], 422);
        }
        return;
    }

    public function processoEditPagamento(Request $request, $id, $id_pvv)
    {
        if ($request->get('forma_pagamento') == 0) {
            return response()->json(['errors' => ['mensagem' => ['Selecione uma forma de pagamento']]], 422);
        }

        $upload = $this->uploadComprovante($request);

        if (isset($upload['error'])) {
            return response()->json(['errors' => ['mensagem' => [$upload['error']]]], 422);
        }
        $data = $request->except('_token', '_files', '_method');

        $data['valor_pago'] = FormatUtils::formatMoneyDb($data['valor_pago']);
        $data['id_banco'] = $data['id_banco'] == 'especie' ? 0 : $data['id_banco'];
        $data['comprovantes'] = json_encode($upload);
        $pagamento = Pagamentos::where('id_processo', $id)->where('id_processo_vencimento_valor', $id_pvv)->update($data);

        if (!$pagamento) {
            return response()->json(['errors' => ['mensagem' => ['Erro ao adicionar dados no banco, favor informar o administrador']]], 422);
        }
        $history = new ProcessoHistorico();
        $history->createHistory($id, "Alterado informações de pagamento do processo id da parcela {$id_pvv}");

        return response()->json(['success' => 'Aprovado a ultima etapa do processo']);
    }

    public function aprovaPagamento(Request $request)
    {
        if ($request->get('forma_pagamento') == 0) {
            return response()->json(['errors' => ['mensagem' => ['Selecione uma forma de pagamento']]], 422);
        }

        if ($request->get('forma_pagamento') == 'especie' && $request->get('id_banco') !== 'especie') {
            return response()->json(['errors' => ['mensagem' => ['Forma de pagamento em especie não pode ter banco selecionado']]], 422);
        }

        /*if(count($request->allFiles()) == 0){
            return response()->json(['errors' => ['mensagem' => ['Sem arquivos']]]);
        }*/

        $upload = $this->uploadComprovante($request);

        if (isset($upload['error'])) {
            return response()->json(['errors' => ['mensagem' => [$upload['error']]]], 422);
        }
        $pagamento = $this->inserirPagamentoNaTabelaPagamento($request->except('_token', 'files'), $upload);

        if ($pagamento !== null) {
            return $pagamento;
        }

        ProcessoVencimentoValor::where('id', $request->get('id_processo_vencimento_valor'))
            ->where('id_processo', $request->get('id_processo'))
            ->update([
                'pago' => true
            ]);

        Processo::where('id', $request->get('id_processo'))->update([
            'user_ultima_alteracao' => auth()->user()->id
        ]);

        $history = new ProcessoHistorico();
        $history->createHistory($request->get('id_processo'), 'Finalizando processo para pagamento');

        return response()->json(['success' => 'Aprovado a ultima etapa do processo']);
    }

    public function processoProtocolo(string $id)
    {
        $processo = Processo::find($id);
        $usuario = User::find($processo->id_user);
        $fornecedor = Fornecedor::find($processo->id_fornecedor);
        $tipo_cobranca = TipoCobranca::find($processo->tipo_cobranca);
        $empresa = Empresa::find($usuario->id_empresa);
        $pdf = new PdfController();
        $pdf->generatePdf(
            "{$usuario->name} {$usuario->last_name}",
            "{$fornecedor->nome}",
            FormatUtils::formatMoney($processo->valor),
            isset($processo->condicao) ? $processo->condicao : 'SEM NOME',
            "{$processo->numero_nota}",
            date('d/m/Y H:i:s', strtotime($processo->created_at)),
            "{$processo->doc_name}",
            $processo->trace_code,
            $empresa->nome
        );
    }

    public function processoExcluiArquivo(Request $request)
    {
        $processo = Processo::find($request->get('id_processo'));
        $decodedArray = json_decode($processo->doc_name, true);
        $fileToRemove = $request->get('file');
        if (($key = array_search($fileToRemove, $decodedArray)) !== false) {
            unset($decodedArray[$key]);  // Remove o arquivo do array
        }
        if (count($decodedArray) < 1) {
            $decodedArray = ['teste.pdf'];
        }
        $processo->doc_name = json_encode(array_values($decodedArray));
        $processo->save();
        $history = new ProcessoHistorico();
        $history->createHistory($request->get('id_processo'), "Removendo o arquivo {$fileToRemove}");
        return;
    }

    public function processoBaixarArquivo(Request $request)
    {
        $fileName = $request->input('file');

        if (file_exists(public_path("uploads/{$fileName}"))) {
            $filePath = public_path("uploads/{$fileName}");

            // Determine o tipo MIME com base na extensão do arquivo
            $fileExtension = pathinfo($filePath, PATHINFO_EXTENSION);

            // Defina o tipo MIME com base na extensão do arquivo
            $mimeType = mime_content_type($filePath);

            // Defina os cabeçalhos apropriados para o download
            return response()->download($filePath, $fileName, [
                'Content-Type' => $mimeType,
                'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
                'Content-Disposition' => 'attachment; filename=' . $fileName,
                'Expires' => '0',
                'Pragma' => 'public'
            ]);
        } else {
            // Caso o arquivo não seja encontrado, você pode tratar o erro aqui.
            return response()->json(['error' => 'Arquivo não encontrado'], 404);
        }
    }

    public function processoEdit($id, $data)
    {
        $instaceWorkflow = new WorkFlow();
        $user = auth()->user();
        $workflow = $instaceWorkflow->getWorkFlow();
        $centroCustos = new CentroCusto();
        $centrosCusto = $centroCustos->where('id_empresa', $user->id_empresa)->get();
        $rateios = new Rateio();
        $rateios = $rateios->where('id_empresa', $user->id_empresa)->get();
        $tipo_cobranca = $user->master == true ? TipoCobranca::all() : TipoCobranca::where('id_empresa', auth()->user()->id_empresa)->get();
        $processo = new Processo();
        $processo = $processo->getProcessoShow($id, $data);
        $subDre = SubCategoriaDRE::select('sub_categoria_dre.id as sub_id', 'sub_categoria_dre.descricao as sub_desc')
            ->leftJoin('dre', 'sub_categoria_dre.id_dre', 'dre.id')
            ->where('sub_categoria_dre.id_empresa', auth()->user()->id_empresa)
            ->where('dre.tipo', 'despesa')
            ->orWhere('dre.tipo', 'despesa')
            ->whereNull('sub_categoria_dre.id_empresa')
            ->where('tipo', 'despesa')
            ->get();
        $dres = DRE::where('id_empresa', auth()->user()->id_empresa)->where('tipo', 'despesa')->orWhereNull('id_empresa')->where('tipo', 'despesa')->get();

        return view('processo.form', compact(
            'workflow',
            'processo',
            'centrosCusto',
            'rateios',
            'tipo_cobranca',
            'subDre',
            'dres'
        ));
    }

    public function destroyFile(Request $request)
    {
        $processo = Processo::select('files_types_desc')
            ->where('id', $request->get('id'))
            ->first();
        $files = $this->removeFile(json_decode($processo->files_types_desc, true), $request->all());
        $updateProcesso = Processo::where('id', $request->get('id'))->update(
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

    public function removeFile($array, $fileToRemove)
    {
        return array_filter($array, function ($file) use ($fileToRemove) {
            return !(
                $file['fileName'] === $fileToRemove['fileName'] &&
                $file['fileType'] === $fileToRemove['fileType'] &&
                $file['fileDesc'] === $fileToRemove['fileDesc']
            );
        });
    }

    public function storeAccount(array $data)
    {
        $rules = [
            'trace_code' => 'required',
            'id_usuario' => 'required',
            'id_empresa' => 'required',
            'id_workflow' => 'required',
            'numero_nota' => 'required',
            'emissao_nota' => 'required',
            'valor' => 'required',
            'tipo_cobranca' => 'required',
            'condicao' => 'required',
            'parcelas' => 'required',
            'id_sub_dre' => 'required',
            'competencia' => 'required',
        ];

        $messages = [
            'trace_code.required' => 'O campo trace_code é obrigatório',
            'id_usuario.required' => 'O campo id_usuario é obrigatório',
            'id_empresa.required' => 'O campo id_empresa é obrigatório',
            'id_workflow.required' => 'O campo Workflow é obrigatório',
            'numero_nota.required' => 'O campo Numero nota fiscal é obrigatório',
            'emissao_nota.required' => 'O campo Emissão nota é obrigatório',
            'valor.required' => 'O campo Valor total da nota é obrigatório',
            'tipo_cobranca.required' => 'O campo Tipo de cobrança é obrigatório',
            'condicao.required' => 'O campo Condição é obrigatório',
            'parcelas.required' => 'O campo Valor da 1ª parcela é obrigatório',
            'id_sub_dre.required' => 'O campo Categoria financeira é obrigatório',
            'competencia.required' => 'O campo Competência é obrigatório',
        ];

        $validator = Validator::make($data, $rules, $messages);

        if ($validator->fails()) {
            return (object)[
                'errors' => $validator->errors()->toArray()
            ];
        }

        return (object)[
            'success' => true
        ];
    }
}
