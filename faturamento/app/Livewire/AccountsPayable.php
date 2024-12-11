<?php

namespace App\Livewire;

use App\Exports\ExcelContasPagar;
use App\Helpers\FormatUtils;
use App\Helpers\UploadFiles;
use App\Helpers\Utils;
use App\Models\ApprovedProcesso;
use App\Models\CentroCusto;
use App\Models\Fornecedor;
use App\Models\GruposProcesso;
use App\Models\Pagamentos;
use App\Models\Processo;
use App\Models\ProcessoVencimentoValor;
use App\Models\Rateio;
use App\Models\TipoCobranca;
use App\Models\WorkFlow;
use App\QueryBuilder\ProcessQueryBuilder;
use App\Services\Account;
use App\Services\ApprovedAccounts;
use App\Services\Autocomplete;
use App\Services\CustomPdf;
use App\Services\DREService;
use App\Services\LogsNumber;
use App\Services\Periods;
use App\Services\ProcessService;
use App\Services\UploadService;
use App\Traits\Apportionment;
use App\Traits\ApportionmentList;
use App\Traits\Banks;
use App\Traits\BatchPayment;
use App\Traits\BillingTypeList;
use App\Traits\UpdateInstallments;
use Carbon\Carbon;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithPagination;
use Maatwebsite\Excel\Facades\Excel;
use Livewire\WithFileUploads;

class AccountsPayable extends Component
{
    use WithPagination;
    use WithFileUploads;
    use UpdateInstallments;
    use Banks;
    use BatchPayment;
    use Apportionment;

    public $page = 'payable';
    public $pay = false;
    public $tax = false;
    public $billingDateRangeStart = null;
    public $billingDateRangeEnd = null;
    public $paymentDateRangeStart = null;
    public $paymentDateRangeEnd = null;
    public $traceCode = null;
    public $supplier = null;
    public $fornecedorName = null;
    public $supplierName = null;
    public $billingType = null;
    public $bank = null;
    public $paymentMethod = null;
    public $centerCost = null;
    public $apportioned = null;
    public $queryPeriod = 'Este mês';
    public $showType = 'totalForPeriod';
    public $sortField = 'pvv_dtv';
    public string $sortDirection = 'asc';
    public $id;
    public string|null $f_doc;
    public float|null|string $valor;
    public string $created_at;
    public int $pvv_id;
    public $pvvId;
    public string $pvv_dtv;
    public float $vparcela;
    public string $u_name;
    public string|null $f_name;
    public string|null $f_id;
    public string $num_nota;
    public string $file;
    public string $p_emissao;
    public string $u_last_modification;
    public bool $pendencia;
    public string $updated_at;
    public int $p_workflow_id;
    public $trace_code;
    public bool $aprovado;
    public bool|null $pago;
    public string|null $tipo_cobranca;
    public string|null $banco;
    public string|null $forma_pagamento;
    public string|null $id_rateio;
    public string|null $id_centro_custo;
    public string|null $filial_nome;
    public string|null $contrato;
    public string|null $produto;
    public string|null $centro_custo;
    public string|null $rateio;
    public string|null $dre_categoria;
    public string|null $competencia;
    public string|null $observacao;
    public $parcelas;
    public $files_type_description;
    public float $valorPago;
    public float $valorPagar;
    public $newTraceCode;
    #[Validate]
    public string|null $fornecedorCpfCnpj;
    #[Validate]
    public string|null $fornecedorEmail;
    public string|null $fornecedorTelefone;
    public string|null $fornecedorEndereco;
    public string|null $fornecedorBairro;
    public string|null $fornecedorCidade;
    public string|null $fornecedorUf;
    public string|null $fornecedorCep;
    public string|null $fornecedorNumero;
    public string|null $fornecedorComplemento;
    #[Validate]
    public string|null $fornecedorRazaoSocial;
    public string|null $fornecedorNomeFantasia;
    public string|null $fornecedorInscricaoEstadual;
    public string|null $fornecedorInscricaoMunicipal;
    public string|null $fornecedorDadosPagamento;
    public string|null $fornecedorObservacao;
    public $objectAutocompleteSupplier;
    public $payableDre = null;
    public $typeDre = 'despesa';
    public $dreDescription = null;
    public $dreCategory = 'despesa';
    public $dreBond;
    public $billingTypeName = null;
    public $centerCostName = null;
    public $centerCostDescription = null;
    public $inputs = [];
    public $installmentDates = [];
    public $installmentValues = [];
    // variaveis para salvar a conta
    public $supplierId = null;
    public $numberNotaFiscal = null;
    public $emissionDate = null;
    public $competence = null;
    public $totalValue = null;
    public $paymentCondition = null;
    public $valueOfTheFirstInstallment = null;
    public $dateOfTheFirstInstallment = null;
    public $installments = null;
    public $financeCategoryId = null;
    public $billingTypeId = null;
    public $workflowId = null;
    public $centerCostId = null;
    public $apportionmentId = null;
    public $accountFiles = [];
    public $accountFilesType = [];
    public $accountFilesDescription = [];
    public $observation = null;
    public $saveWithoutfile = false;
    public $errorMessages = [];
    public $acceptSavePaidAccount = false;
    public $detailedAccountData;
    // variaveis para salvar a conta
    public $approval_progress = [];
    public $detailApprovalProgress = [];
    public $paymentDate = null;
    public $observations = null;
    public $approvedBy = [];
    public $jurosPago;
    public $multasPago;
    public $descontosPago;
    public $selectedSupplier = null;
    public $paymentMethodId;
    public $paymentMethodName;
    public $installmentIds = [];
    public $installmentPaymentStatus = [];
    public $dreType = 'despesa';
    public array $ufs = [
        'AC' => 'AC',
        'AL' => 'AL',
        'AP' => 'AP',
        'AM' => 'AM',
        'BA' => 'BA',
        'CE' => 'CE',
        'DF' => 'DF',
        'ES' => 'ES',
        'GO' => 'GO',
        'MA' => 'MA',
        'MT' => 'MT',
        'MS' => 'MS',
        'MG' => 'MG',
        'PA' => 'PA',
        'PB' => 'PB',
        'PR' => 'PR',
        'PE' => 'PE',
        'PI' => 'PI',
        'RJ' => 'RJ',
        'RN' => 'RN',
        'RS' => 'RS',
        'RO' => 'RO',
        'RR' => 'RR',
        'SC' => 'SC',
        'SP' => 'SP',
        'SE' => 'SE',
        'TO' => 'TO'
    ];

    public function mount()
    {
        if (!auth()->user()->financeiro) {
            return redirect()->route('payment-request.index');
        }
        $this->billingDateRangeStart = date('Y-m-01');
        $this->billingDateRangeEnd = date('Y-m-t');
    }

    public function render()
    {
        $resultAccountsPayable = $this->search();
        if ($resultAccountsPayable->count() > 0){
            $this->approval_progress = (new ApprovedAccounts())->calculateApprovedAccounts($resultAccountsPayable);
        }
        $this->searchBillingType();
        $this->searchWorkflow();
        $this->searchCenterCost();
        $this->searchApportionment();
        $payableByStatus = $this->categorizePayablesByStatus();
        $supplier = $this->searchSupplier();
        !$this->update ? $this->getInstallments($this->id) : null;
        $period = [
            'startDate' => $this->billingDateRangeStart ?? date('Y-m-01'),
            'endDate' => $this->billingDateRangeEnd ?? date('Y-m-t')
        ];
        $this->dispatch('calculatePage');
        return view('livewire.payable.accounts-payable', [
            'accountsPayable' => $resultAccountsPayable,
            'payableByStatus' => $payableByStatus,
            'period' => $period,
            'fornecedores' => $supplier,
            'queryPeriod' => $this->queryPeriod,
            'fornecedorName' => $this->supplierName,
            'showType' => $this->showType,
            'dre' => $this->searchDre(),
            'dreType' => $this->typeDre
        ]);
    }

    public function calculateApprovedAccounts($accounts)
    {
        $this->approval_progress = [];
        foreach($accounts as $account){
            $distinctCombinations = DB::table('approved_processo')
                ->select('id_usuario', 'id_grupo')
                ->where('id_processo', $account->id)
                ->where('id_processo_vencimento_valor', $account->pvv_id)
                ->distinct();

            $accountApproved = DB::table(DB::raw("({$distinctCombinations->toSql()}) as distinct_combinations"))
                ->mergeBindings($distinctCombinations) // Necessário para mesclar os bindings da subquery
                ->count();

            $workflow = WorkFlow::select('id_grupos')
                ->where('id', $account->p_workflow_id)
                ->first();

            $accountWorkflowGroupsCount = count(json_decode($workflow->id_grupos));

            if ($accountWorkflowGroupsCount > 0) {
                $this->approval_progress[$account->id." ".date('Y-m-d', strtotime($account->pvv_dtv))] = [
                    'approved' => $accountApproved,
                    'total' => $accountWorkflowGroupsCount,
                    'percentual' => ($accountApproved / $accountWorkflowGroupsCount) * 100
                ];
            } else {
                $this->approval_progress[$account->id." ".date('Y-m-d', strtotime($account->pvv_dtv))] = [
                    'approved' => $accountApproved,
                    'total' => $accountWorkflowGroupsCount,
                    'percentual' => ($accountApproved / $accountWorkflowGroupsCount) * 100
                ];
            }
        }
    }

    public function searchBy()
    {
        $this->supplier = $this->supplierId ?? null;
        $this->traceCode = $this->traceCode ?? null;
        $this->billingType = $this->billingTypeId ?? null;
        $this->bank = $this->bankId ?? null;
        $this->paymentMethod = $this->paymentMethodId ?? null;
        $this->centerCost = $this->centerCostId ?? null;
        $this->apportioned = $this->apportionmentId ?? null;
        $this->search();
    }

    public $onlyOpened = null;

    public function setOnlyOpened()
    {
        $this->onlyOpened = $this->onlyOpened == true ? false : true;
    }

    public function search()
    {
        $processQueryBuilder = new ProcessQueryBuilder();
        $processService = new ProcessService($processQueryBuilder);

        if ($this->billingDateRangeStart == null && $this->billingDateRangeEnd == null && $this->queryPeriod == 'Este mês') {
            $processService = $processService->byBillingDateRange(date('Y-m-01'), date('Y-m-t'));
        } elseif ($this->queryPeriod == 'Todo o período') {
            $processService = $processService;
        } elseif ($this->showType == 'totalOverdue') {
            $processService = $processService->byBillingDateRange($this->billingDateRangeStart, date('Y-m-d', strtotime('-1 day')));
        } elseif($this->showType == 'totalDueToday'){
            $processService = $processService->byBillingDateRange(date('Y-m-d'), date('Y-m-d'));
        } elseif ($this->showType == 'totalUpcoming') {
            $processService = $processService->byBillingDateRange(date('Y-m-d', strtotime('+1 day')), $this->billingDateRangeEnd);

        } else {
            $processService = $processService->byBillingDateRange($this->billingDateRangeStart, $this->billingDateRangeEnd);
        }

        if ($this->supplier != null and $this->supplier != 'all') {
            $processService = $processService->bySupplier($this->supplier);
        }

        if ($this->traceCode != null) {
            $processService = $processService->byTraceCode($this->traceCode);
        }

        if ($this->paymentDateRangeStart != null && $this->paymentDateRangeEnd != null) {
            $processService = $processService->byPaymentDateRange($this->paymentDateRangeStart, $this->paymentDateRangeEnd);
        }

        if ($this->billingType != null) {
            $processService = $processService->byBillingType($this->billingType);
        }

        if ($this->bank != null) {
            $processService = $processService->byBank($this->bank);
        }

        if ($this->paymentMethod != null) {
            $processService = $processService->byPaymentMethod($this->paymentMethod);
        }

        if ($this->centerCost != null) {
            $processService = $processService->byCenterCost($this->centerCost);
        }

        if ($this->apportioned != null) {
            $processService = $processService->byApportioned($this->apportioned);
        }

        if($this->paid == false and $this->paid !== null){
            $processService = $processService->isNotPaid();
        }

        if($this->paid == true){
            $processService = $processService->isPaid();
        }

        if($this->onlyOpened == true){
            $processService = $processService->isNotPaid();
        }

        return $processService->sortBy($this->sortField, $this->sortDirection)
            ->isNotDisabled()
            ->paginate(auth()->user()->linhas_grid);
    }

    public function paginate($items, $perPage = 15, $page = null, $options = [])
    {
        $page = $page ?: (LengthAwarePaginator::resolveCurrentPage() ?: 1);
        $itemsForCurrentPage = $items->forPage($page, $perPage);
        return new LengthAwarePaginator(
            $itemsForCurrentPage,
            count($items),
            $perPage,
            $page,
            $options
        );
    }

    public function sortBy($field)
    {
        if ($field == $this->sortField) {
            $this->sortDirection = $this->sortDirection == 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortDirection = 'asc';
        }
        $this->sortField = $field;
    }

    public function categorizePayablesByStatus()
    {
        $account = new Account();
        $openedOnly = $this->onlyOpened == true ? true : false;
        $accountsPayable = $account->getAccountsPayable($this->billingDateRangeStart, $this->billingDateRangeEnd, $openedOnly);

        $totalDoPeriodo = $accountsPayable->totalGeral; // Supondo que há um campo 'valor'
        $totalVencidos = $accountsPayable->totalVencidos;
        $totalVencemHoje = $accountsPayable->totalVencemHoje;
        $totalAVencer = $accountsPayable->totalAVencer;
        $totalPagos = $accountsPayable->totalPagos;
        return collect([
            'totalForPeriod' => $totalDoPeriodo,
            'totalOverdue' => $totalVencidos,
            'totalDueToday' => $totalVencemHoje,
            'totalUpcoming' => $totalAVencer,
            'totalPaid' => $totalPagos,
        ]);
    }



    public function calculateDetailPayment($parcelas)
    {
        foreach ($parcelas as $parcela) {
            $valorPago[] = $parcela->pago ? $parcela->vparcela : 0;
            $valorPagar[] = $parcela->pago ? 0 : $parcela->vparcela;
            $jurosPago[] = $parcela->juros ?? 0;
            $multasPago[] = $parcela->multas ?? 0;
            $descontosPago[] = $parcela->descontos ?? 0;
        }

        $this->valorPago = array_sum($valorPago);
        $this->valorPagar = array_sum($valorPagar);
        $this->jurosPago = array_sum($jurosPago);
        $this->multasPago = array_sum($multasPago);
        $this->descontosPago = array_sum($descontosPago);
    }

    public function setRows($rows)
    {
        auth()->user()->linhas_grid = $rows;
        auth()->user()->save();
        $this->render();
    }



    public function setPeriod($period)
    {
        $periodArray = (new Periods())->setPeriod($period);

        $this->billingDateRangeStart = $periodArray['startDate'];
        $this->billingDateRangeEnd = $periodArray['endDate'];
        $this->queryPeriod = $periodArray['queryPeriod'];

        $this->resetPage();

        $this->render();
    }
    public $paid = null;
    public function setType($period)
    {

        if ($period == 'totalForPeriod') {
            $this->showType = 'totalForPeriod';
            $this->paid = null;
        }

        if ($period == 'totalPaid') {
            $this->showType = 'totalPaid';
            $this->paid = true;
        }

        if ($period == 'totalUpcoming') {
            $this->showType = 'totalUpcoming';
            $this->paid = false;
        }

        if ($period == 'totalDueToday') {
            $this->showType = 'totalDueToday';
            $this->paid = false;
        }

        if ($period == 'totalOverdue') {
            $this->showType = 'totalOverdue';
            $this->paid = false;
        }
        $this->resetPage();

        $this->render();

    }

    public function showDetails($account)
    {
        $processQueryBuilder = new ProcessQueryBuilder();
        $processService = new ProcessService($processQueryBuilder);
        $this->parcelas = $processService->byTraceCode($account['trace_code'])->list();
        $this->id = $account['id'];
        $this->f_doc = $account['f_doc'];
        $this->valor = $account['valor'];
        $this->formatedTotalValue = FormatUtils::formatMoney($account['valor']);
        $this->created_at = $account['created_at'];
        $this->pvv_id = $account['pvv_id'];
        $this->pvv_dtv = $account['pvv_dtv'];
        $this->vparcela = $account['vparcela'];
        $this->u_name = $account['u_name'];
        $this->f_name = $account['f_name'];
        $this->f_id = $account['f_id'];
        $this->num_nota = $account['num_nota'];
        $this->file = $account['file'];
        $this->p_emissao = $account['p_emissao'];
        $this->u_last_modification = $account['u_last_modification'];
        $this->pendencia = $account['pendencia'];
        $this->updated_at = $account['updated_at'];
        $this->p_workflow_id = $account['p_workflow_id'];
        $this->trace_code = $account['trace_code'];
        $this->aprovado = $account['aprovado'];
        $this->pago = $account['pago'];
        $this->tipo_cobranca = $account['tipo_cobranca'];
        $this->banco = $account['banco'];
        $this->forma_pagamento = $account['forma_pagamento'];
        $this->id_rateio = $account['id_rateio'];
        $this->id_centro_custo = $account['id_centro_custo'];
        $this->filial_nome = $account['filial_nome'];
        $this->contrato = $account['contrato'];
        $this->produto = $account['produto'];
        $this->centro_custo = $account['centro_custo'];
        $this->rateio = $account['rateio'];
        $this->dre_categoria = $account['dre_categoria'];
        $this->competencia = $account['competencia'];
        $this->observacao = $account['observacao'];
        $this->files_type_description = $account['file_type_description'];
        $this->dt_parcelas = $account['dt_parcelas'];
        $this->qtdParcelas = $account['parcelas'];
        $this->calculateDetailPayment($this->parcelas);
        $this->installmentStatus = $this->identifyPayment($this->parcelas);
        $this->detailedAccountData = $account;
        $this->calculateApprovedAccounts(collect([json_decode(json_encode($account))]));
        $this->approvedBy = $this->whoApproved($account);
        //dd($this->parcelas);
    }

    public $formatedTotalValue = null;
    public $qtdParcelas = null;
    public $indexToDelete = null;
    public function markToDelete($index, $parcela)
    {
        $valorParcela = FormatUtils::formatMoneyDb($parcela);
        $this->indexToDelete = $index;
        $this->installmentValToDelete = $valorParcela;
        $this->installmentStatus[$index]['markedToDelete'] = true;
        $valorTotal = FormatUtils::formatMoney(($this->valor)-($valorParcela));
        $message = "Caso exclua a parcela o valor do processo se tornará R$ {$valorTotal}.
            Deseja realmente excluir a parcela?";
        $this->dispatch('deleteInstallment', $message);
    }

    public function cancelDeleteInstallment()
    {
        $this->installmentStatus[$this->indexToDelete]['markedToDelete'] = false;
    }
    public $installmentValToDelete = null;
    public function deleteInstallment()
    {
        $valorParcela = $this->installmentValToDelete;
        $this->installmentValToDelete = null;
        $valorTotal = FormatUtils::formatMoney(($this->valor)-($valorParcela));
        $installments = [];

        foreach($this->installmentStatus as $key => $installment )
        {
            if($installment['markedToDelete'] == "true" and $installment['id'] != null){
                ProcessoVencimentoValor::where('id', $installment['id'])->delete();
                (new LogsNumber())->saveLog([
                    'id_empresa' => auth()->user()->id_empresa,
                    'message' => 'Parcela do processo '.$this->id.' tracecode '.$this->trace_code.' foi deletada pelo usuario '.auth()->user()->name,
                ]);
            }
            if($installment['markedToDelete'] == "false"){
                $installments[] = [
                    'data' => $installment['data'],
                    'valor' => $installment['valor'],
                    'status' => $installment['status'],
                    'id' => $installment['id'],
                    'markedToDelete' => $installment['markedToDelete']
                ];
            }
        }
        Processo::where('id', $this->id)->update(['valor' => FormatUtils::formatMoneyDb($valorTotal)]);
        $this->installmentStatus = $installments;
        $this->valor = FormatUtils::formatMoneyDb($valorTotal);
    }
    public function updateInstallments()
    {
        $qtdeParcelas = (int) $this->qtdParcelas;
        if($qtdeParcelas !== count($this->installmentStatus)){
            $this->errorMessages['qtdeParcelas'] = [
                'A quantidade de parcelas esta diferente das parcelas lançadas, favor ajustar'
            ];
            return;
        }

        $valorTotal = is_float($this->formatedTotalValue) ? $this->formatedTotalValue : FormatUtils::formatMoneyDb($this->formatedTotalValue);

        foreach($this->installmentStatus as $installmentValue){
            $valuesArray[] = FormatUtils::formatMoneyDb($installmentValue['valor']);
        }

        if($valorTotal != array_sum($valuesArray)){
            session()->flash('error', 'A soma dos valores das parcelas não é igual ao valor total da conta. Por favor, corrija o valor total.');
            $this->errorMessages['valorTotal'] = [
                'A soma dos valores das parcelas não é igual ao valor total da conta. Por favor, corrija o valor total.'
            ];
            return;
        }

        foreach ($this->installmentStatus as $key => $installment) {
            if($installment['data'] == ""){
                $errorMessages["data$key"] = [
                    "A data da parcela é obrigatória"
                ];
            }
            if($installment['valor'] == "" || $installment['valor'] == 0){
                $errorMessages["valor$key"] = [
                    "O valor da parcela é obrigatório"
                ];
            }
        }

        if(isset($errorMessages)){
            $this->errorMessages = $errorMessages;
            return;
        }

        foreach($this->installmentStatus as $key => $installment )
        {
            if($installment['id'] == null){
                ProcessoVencimentoValor::create([
                    'data_vencimento' => $installment['data'],
                    'price' => FormatUtils::formatMoneyDb($installment['valor']),
                    'id_processo' => $this->id,
                    'pago' => false,
                    'aprovado'=> false,
                ]);
            }else{
                ProcessoVencimentoValor::where('id', $installment['id'])
                    ->update([
                    'data_vencimento' => $installment['data'],
                    'price' => FormatUtils::formatMoneyDb($installment['valor']),
                    'aprovado'=> false,
                    'pago' => false,
                ]);
            }
            $dt_parcelas[] =[
                'data'.$key => $installment['data'],
                'valor'.$key => FormatUtils::formatMoneyDb($installment['valor'])
            ];
        }

        Processo::where('id', $this->id)->update([
            'valor' => $valorTotal,
            'dt_parcelas' => json_encode($dt_parcelas),
            'parcelas' => $this->qtdParcelas
        ]);

        session()->flash('success', 'Parcelas atualizadas com sucesso');
        $this->dispatch('closeModal', 'modalEditInstallment');
        $this->showDetails($this->detailedAccountData);
    }
    public $installmentStatus = [];

    public function identifyPayment($parcelas)
    {
        $installments = [];
        foreach($parcelas as $key => $parcela){
            $installments[$key] = [
                'data' => date('Y-m-d', strtotime($parcela->pvv_dtv)),
                'valor' => FormatUtils::formatMoney($parcela->vparcela),
                'status' => $parcela->pago ?? false,
                'id' => $parcela->pvv_id,
                'markedToDelete' => false
            ];
        }
        return $installments;
    }

    public function whoApproved($account)
    {
        $usersAndGroups = ApprovedProcesso::select('users.name as user_name', 'grupo_processos.nome as group_name')
            ->leftJoin('users', 'users.id', 'approved_processo.id_usuario')
            ->leftJoin('grupo_processos', 'grupo_processos.id', 'approved_processo.id_grupo')
            ->where('id_processo', $account['id'])
            ->where('id_processo_vencimento_valor', $account['pvv_id'])
            ->distinct()
            ->get();
        return $usersAndGroups;
    }

    public function report($type)
    {
        $html = view('pdf.contas-pagar', [
            'relatorio' => $this->search(),
        ])->render();

        if ($type == 'pdf') {
            $pdf = new CustomPdf();
            $pdfContent = $pdf->setPaper('A4', 'landscape')
                ->generatePdfLivewire($html, '', true);
            return response()->streamDownload(
                fn() => print($pdfContent),
                "filename.pdf"
            );

        }

        if ($type == 'excel') {
            return Excel::download(new ExcelContasPagar($this->search()), 'contas-pagar.xlsx');
        }

        if ($type == 'graphs') {
            return $this->redirect(route('financeiro.dashboard'));
        }

        return;
    }

    public function generateNewTraceCode()
    {
        $this->newTraceCode = FormatUtils::traceCode();
    }

    public function searchSupplierCpfCnpj()
    {
        $autocomplete = new Autocomplete();

        if (Utils::isCnpj($this->fornecedorCpfCnpj)) {
            $result = $autocomplete->autocompleteSupplierData($this->fornecedorCpfCnpj);
        } elseif (Utils::isCpf($this->fornecedorCpfCnpj)) {
            $result = $autocomplete->autocompleteSupplierData($this->fornecedorCpfCnpj);
            session()->flash('error', json_decode($result->content())->message);
        } else {
            session()->flash('error', 'Documento inválido');
            return;
        }

        if ($result->status() == 402) {
            session()->flash('error', json_decode($result->content())->message);
        }

        if ($result->status() == 200) {
            $dataJson = json_decode($result->content())->data;
            $this->objectAutocompleteSupplier = json_decode($dataJson);
            $this->fornecedorEmail = $this->objectAutocompleteSupplier->email;
            $this->fornecedorTelefone = $this->objectAutocompleteSupplier->telefone;
            $this->fornecedorEndereco = $this->objectAutocompleteSupplier->endereco;
            $this->fornecedorBairro = $this->objectAutocompleteSupplier->bairro;
            $this->fornecedorCidade = $this->objectAutocompleteSupplier->cidade;
            $this->fornecedorUf = $this->objectAutocompleteSupplier->estado;
            $this->fornecedorCep = $this->objectAutocompleteSupplier->cep;
            $this->fornecedorNumero = $this->objectAutocompleteSupplier->numero;
            $this->fornecedorComplemento = $this->objectAutocompleteSupplier->complemento;
            $this->fornecedorRazaoSocial = $this->objectAutocompleteSupplier->razao_social;
            $this->fornecedorNomeFantasia = $this->objectAutocompleteSupplier->nome_fantasia;
            $this->fornecedorInscricaoEstadual = $this->objectAutocompleteSupplier->inscricao_estadual;
            $this->fornecedorInscricaoMunicipal = $this->objectAutocompleteSupplier->inscricao_municipal;
        }
    }

    public function rules()
    {
        return [
            'fornecedorCpfCnpj' => 'required',
            //'fornecedorEmail' => 'email',
            'fornecedorRazaoSocial' => 'required',
        ];
    }

    public function messages()
    {
        return [
            'fornecedorCpfCnpj.required' => 'O campo CPF/CNPJ é obrigatório',
            //'fornecedorEmail.email' => 'O campo email deve ser um email válido',
            'fornecedorRazaoSocial.required' => 'O campo razão social é obrigatório'
        ];
    }

    public function saveWithoutFiles()
    {
        $this->saveWithoutfile = true;
        $this->dispatch('sendClick', '#sendSaveFormAccount');
    }

    public function savePaidAccount()
    {
        $this->acceptSavePaidAccount = true;
    }

    public function withoutFile()
    {
        $this->saveWithoutfile = false;
    }

    protected $listeners = ['generateTraceCodeAndCleanForm'];

    public function generateTraceCodeAndCleanForm()
    {
        $this->reset();
        $this->update = true;
        $this->generateNewTraceCode();
    }

    public function uploadFiles()
    {
        $filesArray = [];
        foreach ($this->accountFiles as $index => $file)
        {
            $fileName = time().$file->getClientOriginalName();
            $filesArray[] = [
                'fileName' => $fileName,
                'fileType' => $this->accountFilesType[$index],
                'fileDesc' => $this->accountFilesDescription[$index] ?? null
            ];
            $r2 = new UploadFiles();
            $r2->uploadToR2(
                'uploads/'.$fileName,
                file_get_contents($file->getRealPath())
            );
        }
        return $filesArray;
    }

    public function setInstallments()
    {
        $installmentsValues = [];
        foreach($this->installmentValues as $value){
            $installmentsValues[] = FormatUtils::formatMoneyDb($value);
        }

        if(!$this->update){
            if(array_sum($installmentsValues) != FormatUtils::formatMoneyDb($this->totalValue)){
                session()->flash('error', 'A soma dos valores das parcelas não é igual ao valor total da conta');
                return false;
            }
        }

        if($this->update){
            if((FormatUtils::formatMoneyDb($this->valueOfTheFirstInstallment) + array_sum($installmentsValues)) != FormatUtils::formatMoneyDb($this->totalValue)){
                session()->flash('error', 'A soma dos valores das parcelas não é igual ao valor total da conta');
                return false;
            }
        }


        $installments[] = [
            'data0' => $this->dateOfTheFirstInstallment,
            'valor0' => FormatUtils::formatMoneyDb($this->valueOfTheFirstInstallment)
        ];
        foreach($this->installmentDates as $index => $dateOfInstallment){
            $installments[] = [
                'data'.$index => $dateOfInstallment,
                'valor'.$index => FormatUtils::formatMoneyDb($this->installmentValues[$index])
            ];
        }
        return $installments;
    }

    public function accountDbData($filesArray, $installments, $update = false)
    {
        $accountData = [
            'pay' => $this->pay,
            'impostos' => $this->tax,
            'trace_code' => $update == false ? $this->newTraceCode : $this->trace_code,
            'id_usuario' => auth()->user()->id,
            'id_empresa' => auth()->user()->id_empresa,
            'id_fornecedor' => $this->supplierId,
            'id_workflow' => $this->workflowId,
            'id_centro_custos' => $this->centerCostId,
            'numero_nota' => $this->numberNotaFiscal,
            'emissao_nota' => $this->emissionDate,
            'valor' => FormatUtils::formatMoneyDb($this->totalValue),
            'tipo_cobranca' => $this->billingTypeId,
            'condicao' => $this->paymentCondition,
            'parcelas' => $this->installments,
            'observacao' => $this->observation,
            'id_sub_dre' => $this->financeCategoryId,
            'competencia' => $this->competence,
            'id_rateio' => $this->apportionmentId,
            'files_types_desc' => count($filesArray) > 0 ? json_encode($filesArray) : null,
            'dt_parcelas' => count($installments) > 0 ? json_encode($installments) : null,
        ];

        //isset($this->id) ? $accountData['id'] = $this->id : null;
        if($update == true){
            $accountData['id'] = $this->id;
            $accountData['installmentStatus'] = json_encode($this->installmentStatus);
        }
        return $accountData;
    }

    public function mergeFiles($filesArray)
    {
        if(!isset($this->files_type_description) or $this->files_type_description == 'null'){
            return $filesArray;
        }

        foreach(json_decode($this->files_type_description, true) as $index => $file){
            $filesArray[] = [
                'fileName' => $file['fileName'],
                'fileType' => $file['fileType'],
                'fileDesc' => $file['fileDesc']
            ];
        }
        return $filesArray;
    }

    public function updateAccount()
    {
        $upload = new UploadService();
        $filesArray = $upload->uploadFiles(
            $this->accountFiles,
            $this->accountFilesType,
            $this->accountFilesDescription
        );
        $filesArray = $upload->mergeFiles($this->files_type_description, $filesArray);

        $installments = $this->setInstallments();

        if(!$installments){
            session()->flash('error', 'A soma dos valores das parcelas não é igual ao valor total da conta');
            return;
        }

        $accountData = $this->accountDbData($filesArray, $installments, true);

        $account = new Account();
        $result = $account->updateAccount($accountData);
        $account->updateInstallments(
            count($this->installmentIds),
            $this->installmentIds,
            $this->installmentDates,
            $this->installmentValues,
            $this->id
        );
        if (isset($result->errors)) {
            $this->errorMessages = $result->errors;
        }

        if(isset($result->success)){
            session()->flash('success', 'Conta atualizada com sucesso');
            $this->dispatch('updatedAccount', $this->id.$this->pvv_id);
            $this->render();
        }
    }

    public function saveAccount()
    {
        if(is_null($this->dateOfTheFirstInstallment) or empty($this->dateOfTheFirstInstallment)){
            $this->errorMessages['dateOfTheFirstInstallment'] = [
                'A data da primeira parcela é obrigatória'
            ];
            return;
        }

        if(is_null($this->valueOfTheFirstInstallment) or empty($this->valueOfTheFirstInstallment)){
            $this->errorMessages['valueOfTheFirstInstallment'] = [
                'O valor da primeira parcela é obrigatório'
            ];
            return;
        }

        if(count($this->accountFiles) == null and $this->saveWithoutfile == false){
            $this->dispatch('saveWithoutfile');
            return;
        }

        if($this->pay and !$this->acceptSavePaidAccount){
            $this->dispatch('paidAccount');
            return;
        }

        $upload = new UploadService();
        $filesArray = $upload->uploadFiles(
            $this->accountFiles,
            $this->accountFilesType,
            $this->accountFilesDescription
        );

        $installments = $this->setInstallments();
        if(!$installments){
            return;
        }

        $accountData = $this->accountDbData($filesArray, $installments);

        $account = new Account();

        $result = $account->storeAccount($accountData);

        if (isset($result->errors)) {
            $this->errorMessages = $result->errors;
            return;
        }

        if(isset($result->success)){
            session()->flash('success', 'Conta cadastrada com sucesso');
            $this->dispatch('dataSaved');
            $this->render();
        }

        $this->dispatch('closeModal', 'modalAddAccount');
    }

    public function saveSupplier()
    {
        $this->validate();
        if (Utils::existsSupplier($this->fornecedorCpfCnpj)) {
            session()->flash('error', 'Fornecedor já cadastrado');
            return;
        }
        $fornecedor = new Fornecedor();
        $fornecedor->razao_social = $this->fornecedorRazaoSocial;
        $fornecedor->nome = $this->fornecedorNomeFantasia ?? $this->fornecedorRazaoSocial;
        $fornecedor->cpf_cnpj = $this->fornecedorCpfCnpj;
        $fornecedor->inscrica_estadual = $this->fornecedorInscricaoEstadual ?? null;
        $fornecedor->inscricao_municipal = $this->fornecedorInscricaoMunicipal ?? null;
        $fornecedor->cep = $this->fornecedorCep ?? null;
        $fornecedor->endereco = $this->fornecedorEndereco ?? null;
        $fornecedor->numero = $this->fornecedorNumero ?? null;
        $fornecedor->complemento = $this->fornecedorComplemento ?? null;
        $fornecedor->bairro = $this->fornecedorBairro ?? null;
        $fornecedor->cidade = $this->fornecedorCidade ?? null;
        $fornecedor->uf = $this->fornecedorUf ?? null;
        $fornecedor->telefone = $this->fornecedorTelefone ?? null;
        $fornecedor->email = $this->fornecedorEmail ?? null;
        $fornecedor->forma_pagamento = $this->fornecedorDadosPagamento ?? null;
        $fornecedor->observacao = $this->fornecedorObservacao ?? null;
        $fornecedor->id_empresa = auth()->user()->id_empresa;
        $fornecedor->save();
        session()->flash('success', 'Fornecedor cadastrado com sucesso');
        if(isset($this->f_id) and !is_null($this->f_id)){
            $this->dispatch('dataSaved', $this->f_id, $this->f_name);
        }else{
            $this->dispatch('dataSaved');
        }
        $this->clearSupplierForm();
        $this->reset();
    }

    public function clearSupplierForm()
    {
        $this->fornecedorCpfCnpj = null;
        $this->fornecedorRazaoSocial = null;
        $this->fornecedorNomeFantasia = null;
        $this->fornecedorInscricaoEstadual = null;
        $this->fornecedorInscricaoMunicipal = null;
        $this->fornecedorCep = null;
        $this->fornecedorEndereco = null;
        $this->fornecedorNumero = null;
        $this->fornecedorComplemento = null;
        $this->fornecedorBairro = null;
        $this->fornecedorCidade = null;
        $this->fornecedorUf = null;
        $this->fornecedorTelefone = null;
        $this->fornecedorEmail = null;
        $this->fornecedorDadosPagamento = null;
        $this->fornecedorObservacao = null;
    }

    public function saveDRE()
    {
        if ($this->dreCategory == 'despesa') {
            $dreServiceResult = (new DREService())->storeDRE([
                'descricao' => $this->dreDescription,
                'tipo' => $this->dreCategory,
                'codigo' => '0',
                'id_empresa' => auth()->user()->id_empresa,
                'id_usuario' => auth()->user()->id,
                'editable' => true
            ]);
        }

        if ($this->dreCategory !== 'despesa') {
            $dreServiceResult = (new DREService())->storeSubDRE([
                'descricao' => $this->dreDescription,
                'id_dre' => $this->dreCategory,
                'id_empresa' => auth()->user()->id_empresa,
                'id_usuario' => auth()->user()->id,
                'editable' => true
            ]);
        }
        if (!$dreServiceResult->success) {
            session()->flash('error', $dreServiceResult->message);
            return;
        }
        session()->flash('success', 'DRE cadastrado com sucesso');
        $this->dispatch('dataSaved');
        $this->reset();
    }

    public function saveBillingType()
    {
        if ($this->billingTypeName == null) {
            session()->flash('error', 'O campo tipo de cobrança é obrigatório');
            return;
        }

        TipoCobranca::create([
            'nome' => strtoupper($this->billingTypeName),
            'id_empresa' => auth()->user()->id_empresa,
            'md5_nome' => md5(strtoupper(substr($this->billingType, 0, 6)))
        ]);
        session()->flash('success', 'Tipo de cobrança cadastrado com sucesso');
        $this->dispatch('dataSaved');
        $this->reset();
    }

    public function saveCenterCost()
    {
        if ($this->centerCostName == null) {
            session()->flash('error', 'O campo centro de custo é obrigatório');
            return;
        }

        CentroCusto::create([
            'nome' => strtoupper($this->centerCostName),
            'descricao' => $this->centerCostDescription,
            'id_empresa' => auth()->user()->id_empresa,
        ]);

        session()->flash('success', 'Centro de custo cadastrado com sucesso');
        $this->dispatch('dataSaved');
        $this->reset();
    }

    public function addInput()
    {
        $this->inputs[] = '';
    }

    public function removeInput($index)
    {
        unset($this->inputs[$index]);
        unset($this->accountFilesDescription[$index+1]);
        $this->inputs = array_values($this->inputs);
        $this->accountFilesDescription = array_values($this->accountFilesDescription);
    }

    public function updatedDateOfTheFirstInstallment()
    {
        $this->calculateInstallments();
    }

    public function updatedTotalValue()
    {
        $this->calculateInstallments();
    }

    public function updatedInstallments()
    {
        $this->calculateInstallments();
    }

    public function updatedPaymentCondition()
    {
        $this->calculateInstallments();
    }

    public $calculatedInstallment = false;

    public function calculateInstallments()
    {
        //$this->calculatedInstallment = true;
        $this->installmentDates = [];
        $this->installmentValues = [];
        if ($this->paymentCondition === 'prazo' && $this->installments >= 1) {
            $this->installmentDates = [];
            $this->installmentValues = [];
            $totalValue = FormatUtils::formatMoneyDb($this->totalValue);
            $valueOfTheFirstInstallment = FormatUtils::formatMoneyDb($this->valueOfTheFirstInstallment);
            $totalValue = $totalValue - $valueOfTheFirstInstallment;
            $installmentValue = $totalValue / $this->installments;
            $firstInstallmentDate = Carbon::parse($this->dateOfTheFirstInstallment);

            for ($i = 1; $i < ($this->installments + 1); $i++) {
                $this->installmentIds[$i] = null;
                $this->installmentDates[$i] = $firstInstallmentDate->copy()->addMonth($i)->format('Y-m-d');
                $this->installmentValues[$i] = number_format($installmentValue, 2, ',', '');
            }
        }
        //dd($this->installmentIds, $this->installmentDates, $this->installmentValues);
    }

    public function selectSupplier($id, $name)
    {
        $this->supplierName = $name;
        $this->supplierId = $id;
    }

    public function selectFinanceCategory($id, $name)
    {
        $this->financeCategoryId = $id;
        $this->dreDescription = $name;
    }

    public function selectBillingType($id, $name)
    {
        $this->billingTypeId = $id;
        $this->billingTypeName = $name;
    }
    public $workflowName = null;
    public function selectWorkflow($id, $name)
    {
        $this->workflowId = $id;
        $this->workflowName = $name;
    }

    public function selectCenterCost($id, $name)
    {
        $this->centerCostId = $id;
        $this->centerCostName = $name;
    }

    public $update = false;
    public function edit($id)
    {
       $this->update = false;
       $this->id = $id;
       $this->pvvId = $this->detailedAccountData['pvv_id'];
       $this->supplierName = $this->detailedAccountData['f_name'];
       $this->tax = $this->detailedAccountData['f_name'] == null ? true : false;
       $this->supplierId = $this->detailedAccountData['f_id'];
       $this->numberNotaFiscal = $this->detailedAccountData['num_nota'];
       $this->emissionDate = $this->detailedAccountData['p_emissao'];
       $this->competence = $this->detailedAccountData['competencia'];
       $this->totalValue = $this->totalValue ?? FormatUtils::formatMoney($this->detailedAccountData['valor']);
       $installments = $this->installmentAdjust($this->detailedAccountData['dt_parcelas']);
       $this->paymentCondition = $this->detailedAccountData['condicao'];
       $this->valueOfTheFirstInstallment = is_float($installments[0]['valor0']) ?
           FormatUtils::formatMoney($installments[0]['valor0']) :
           FormatUtils::formatMoney(FormatUtils::formatMoneyDb($installments[0]['valor0']));
       $this->dateOfTheFirstInstallment = $installments[0]['data0'];
       $this->installments = $this->detailedAccountData['condicao'] == 'prazo' ? $this->detailedAccountData['parcelas'] : 0;
       $this->financeCategoryId = $this->detailedAccountData['id_sub_dre'];
       $this->dreDescription = $this->detailedAccountData['dre_categoria'];
       $this->billingTypeName = $this->detailedAccountData['tipo_cobranca'];
       $this->billingTypeId = $this->detailedAccountData['id_tipo_cobranca'];
       $this->workflowName = $this->detailedAccountData['workflow'];
       $this->workflowId = $this->detailedAccountData['p_workflow_id'];
       $this->centerCostName = $this->detailedAccountData['centro_custo'];
       $this->centerCostId = $this->detailedAccountData['id_centro_custo'];
       $this->apportionmentId = $this->detailedAccountData['id_rateio'];
       $this->observation = $this->detailedAccountData['observacao'];
       $this->files_type_description = $this->detailedAccountData['file_type_description'];
       $this->pay = $this->detailedAccountData['pago'] == 1 ? true : false;
       $this->separateInstallments($installments);
       $this->dispatch('editAccount', $this->supplierId, $this->supplierName);
    }

    public function separateInstallments($installments)
    {
        $this->installmentDates = [];
        $this->installmentValues = [];
        foreach($installments as $key => $installment){
            if(array_key_exists('data'.$key, $installment) and $key != 0){
                $this->installmentDates[] = $installment['data'.$key];
                $this->installmentValues[] = is_float($installment['valor'.$key]) ? FormatUtils::formatMoney($installment['valor'.$key]) : FormatUtils::formatMoney(FormatUtils::formatMoneyDb($installment['valor'.$key]));
            }
        }
        return;
    }

    public function installmentAdjust($value)
    {
        $installments = json_decode($value, true);
        for($i = 0 ; $i < count($installments); $i++){
            if(array_key_exists('valor'.$i, $installments) and $installments['data'.$i] != 0){
                $data[$i] = [
                    "data{$i}" => $installments['data'.$i],
                    "valor{$i}" => $installments['valor'.$i]
                ];
            }
        }

        return isset($data) ? $data : $installments;
    }

    public function deleteWarning(
        $id,
        $pvv_id,
        $pago,
        $valor,
        $vparcela,
        $parcelas
    )
    {
        $valorTotal = FormatUtils::formatMoney(($valor)-($vparcela));
        $parcelas = !is_numeric($parcelas) ?
            count(json_decode(json_encode($parcelas), true)) :
            $parcelas;

        if($parcelas == 1 and ($pago == false or $pago == null)){
            $text = 'Deseja realmente excluir a conta?';
            $this->dispatch(
                'deleteAccount',
                $text
            );
            return;
        }

        if($pago == 1){
            $text = 'Não é possível excluir uma conta paga';
            $this->dispatch(
                'deleteAccount',
                $text, 'pago'
            );
            return;
        }

        if($parcelas > 1){
            $this->deleteInstallmentPvvId = $pvv_id;
            $text = "Caso exclua a parcela o valor do processo se tornará R$ {$valorTotal}.
            Deseja realmente excluir a parcela?";
            $this->dispatch(
                'deleteAccount',
                $text
            );
            return;
        }
        return;
    }

    public $deleteInstallmentPvvId = null;
    public function deleteAccount()
    {
        $account = new Account();

        if(count($this->parcelas) == 1){
            $result = $account->deleteAccount($this->id);
            $message = 'Conta excluída com sucesso';
        }

        if(count($this->parcelas) > 1){
            $result = $account->deleteInstallment($this->id, $this->deleteInstallmentPvvId);
            $message = 'Parcela excluída com sucesso';
        }

        if (isset($result->errors)) {
            $this->errorMessages = $result->errors;
        }

        if(isset($result->success)){
            $this->dispatch('successDelete', $message);
            $this->reset();
        }
    }
    public $fileNameToDelete = null;
    public $fileDeleteLineId = null;
    public function deleteFile()
    {
        $filesArray = json_decode($this->files_type_description, true);
        foreach($filesArray as $index => $file){
            if($file['fileName'] == $this->fileNameToDelete){
                unset($filesArray[$index]);
            }
        }
        $this->files_type_description = json_encode(array_values($filesArray));
        Processo::where('id', $this->id)->where('id_empresa', auth()->user()->id_empresa)->update([
            'files_types_desc' => $this->files_type_description
        ]);
        $this->dispatch('fileDeleted', $this->fileDeleteLineId);
    }

    public function askDeleteFile($fileName, $lineId)
    {
        $this->fileDeleteLineId = $lineId;
        $this->fileNameToDelete = $fileName;
        $this->dispatch('deleteFile');
    }

    public $paymentValue = null;
    public $installmentValue = null;
    public $interest = null;
    public $fine = null;
    public $discount = null;

    public function payBilling()
    {
        $this->installmentValue = FormatUtils::formatMoney($this->vparcela);
        $this->paymentDate = date('Y-m-d', strtotime($this->pvv_dtv));
        $this->paymentValue = FormatUtils::formatMoney($this->vparcela);
        $this->setAmountWithLateInterestAndDiscount();
        $this->dispatch('payAccount', $this->id, $this->pvv_id);
    }

    public function payAccount()
    {
        if($this->paymentMethodId == null){
            $this->errorMessages['emptyPaymentMethod'] = [
                'Favor selecionar uma forma de pagamento'
            ];
            return;
        }

        $upload = new UploadService();
        $filesArray = $upload->uploadFiles(
            $this->accountFiles,
            $this->accountFilesType,
            $this->accountFilesDescription
        );
        $filesArray = $upload->mergeFiles($this->files_type_description, $filesArray);
        $account = new Account();
        $result = $account->payAccount([
            'id_processo' => $this->id,
            'id_processo_vencimento_valor' => $this->pvv_id,
            'valor_pago' => $this->amountWithLateInterestAndDiscount,
            'juros' => $this->interest,
            'multa' => $this->fine,
            'desconto' => $this->discount,
            'data_pagamento' => $this->paymentDate,
            'forma_pagamento' => $this->paymentMethodId,
            'id_banco' => $this->bankId,
            'files' => $filesArray,
            'observacao' => $this->observation
        ]);

        if (isset($result->errors)) {
            $this->errorMessages = $result->errors;
        }

        if(isset($result->success)){
            session()->flash('success', 'Conta paga com sucesso');
            $this->dispatch('successPay', $this->id.$this->pvv_id);
            $this->render();
        }
    }
    public $bankName = null;
    public $bankId = null;

    public function selectBank($bankId, $bankName)
    {
        $this->bankName = $bankName;
        $this->bankId = $bankId;
    }
    public $pagamento_id = null;
    public function editPayment()
    {
        $account = $this->detailedAccountData;
        $this->pagamento_id = $account['id_pagamento'];
        $this->installmentValue = FormatUtils::formatMoney($account['vparcela']);
        $this->paymentDate = date('Y-m-d', strtotime($account['pvv_dtv']));
        $this->paymentMethodId = $account['forma_pagamento'];
        $this->paymentMethodName = $account['forma_pagamento_nome'];
        $this->bankName = $account['banco'];
        $this->bankId = $account['banco_id'];
        $this->paymentValue = FormatUtils::formatMoney($account['valor_pago']);
        $this->interest = $account['juros'];
        $this->fine = $account['multas'];
        $this->discount = $account['descontos'];
        $this->observation = $account['observacao_pagamento'];
        $this->pay = $account['pago'];
        $this->dispatch('editPayment');
    }

    public function updatePayment()
    {
        $upload = new UploadService();
        $filesArray = $upload->uploadFiles(
            $this->accountFiles,
            $this->accountFilesType,
            $this->accountFilesDescription
        );
        $filesArray = $upload->mergeFiles($this->files_type_description, $filesArray);
        $account = new Account();
        $result = $account->updatePayment([
            'id_pagamento' => $this->pagamento_id,
            'id_processo' => $this->id,
            'id_processo_vencimento_valor' => $this->pvv_id,
            'valor_pago' => $this->amountWithLateInterestAndDiscount,
            'juros' => $this->interest,
            'multa' => $this->fine,
            'desconto' => $this->discount,
            'data_pagamento' => $this->paymentDate,
            'forma_pagamento' => $this->paymentMethodId,
            'id_banco' => $this->bankId,
            'files' => $filesArray,
            'observacao' => $this->observation
        ]);

        if (isset($result->errors)) {
            $this->errorMessages = $result->errors;
        }

        if(isset($result->success)){
            $this->pay = null;
            session()->flash('success', 'Pagamento atualizado com sucesso');
            $this->dispatch('successPay', $this->id.$this->pvv_id);
            $this->reset();
            $this->render();
        }
    }

    public function payAccountDetail($account)
    {
        $account = json_decode(json_encode($account));
        $this->installmentValue = FormatUtils::formatMoney($account->vparcela);
        $this->paymentDate = date('Y-m-d', strtotime($account->pvv_dtv));
        $this->paymentValue = FormatUtils::formatMoney($this->vparcela);
        $this->dispatch('payAccount', $this->id, $this->pvv_id);
    }

    public function editPaymentDetail($account)
    {
        $account = json_decode(json_encode($account));
        $this->pagamento_id = $account->id_pagamento;
        $this->installmentValue = FormatUtils::formatMoney($account->vparcela);
        $this->paymentDate = date('Y-m-d', strtotime($account->pvv_dtv));
        $this->paymentMethodId = $account->forma_pagamento;
        $this->paymentMethodName = $account->forma_pagamento_nome;
        //dd($this->paymentMethodName, $this->paymentMethodId);
        $this->bankName = $account->banco;
        $this->bankId = $account->banco_id;
        $this->paymentValue = FormatUtils::formatMoney($account->valor_pago);
        $this->interest = $account->juros;
        $this->fine = $account->multas;
        $this->discount = $account->descontos;
        $this->observation = $account->observacao_pagamento;
        $this->pay = $account->pago;
        $this->dispatch('editPayment');
    }

    public $fornecedoresDropdownList = [];
    public function showSupplierList()
    {
        $fornecedores = Fornecedor::select('id', 'nome')
            ->where('id_empresa', auth()->user()->id_empresa)
            ->get();
        $this->fornecedoresDropdownList = $fornecedores->toArray();
    }

    public $supplierList = [];
    public $querySupplier = null;
    public $pageSupplierSearch = 1;

    public $limitSuppliers = 10;

    public function loadMoreSupplier()
    {
        $this->pageSupplierSearch++;
        $oldSupplierList = $this->supplierList;
        return $this->supplierList = array_merge($oldSupplierList, $this->searchSupplier());
    }

    public function searchSupplierByString()
    {
        $this->pageSupplierSearch = 1;
        $this->supplierList = $this->searchSupplier();
    }
    public function searchSupplier()
    {
        return $this->supplierList = (new Fornecedor())->searchSupplier(
            $this->querySupplier,
            $this->pageSupplierSearch,
            $this->limitSuppliers
        );
    }

    public $dreList = [];
    public $queryDre = null;
    public $pageDreSearch = 1;
    public $limitDre = 10;

    public function searchDreByString()
    {
        $this->pageDreSearch = 1;
        $this->dreList = $this->searchDre();
    }

    public function loadMoreDre()
    {
        $this->pageDreSearch++;
        $oldDreList = $this->dreList;
        return $this->dreList = array_merge($oldDreList, $this->searchDre());
    }

    public function searchDre()
    {
        $dre = (new DREService())->getAllDREByString('despesa',
            $this->queryDre,
            $this->limitDre,
            $this->pageDreSearch);

        if($dre->count() == 0){
            return $this->dreList = [['sub_id' => null, 'sub_desc' => 'Nenhum dre encontrado']];
        }

        if($dre->count() >= 1 and $this->pageDreSearch == 1){
            return $this->dreList = $dre->toArray();
        }

        if($dre->count() >= 1 and $this->pageDreSearch > 1){
            return $dre->toArray();
        }
        return;
    }

    public $billingTypeList = [];
    public $queryBillingType = null;
    public $pageBillingTypeSearch = 1;
    public $limitBillingType = 10;

    public function searchBillingTypeByString()
    {
        $this->pageBillingTypeSearch = 1;
        $this->billingTypeList = $this->searchBillingType();
    }

    public function loadMoreBillingType()
    {
        $this->pageBillingTypeSearch++;
        $oldBillingTypeList = $this->billingTypeList;
        return $this->billingTypeList = array_merge($oldBillingTypeList, $this->searchBillingType());
    }

    public function searchBillingType()
    {
        return $this->billingTypeList = (new TipoCobranca())->getBillingTypeList(
            $this->queryBillingType,
            $this->pageBillingTypeSearch,
            $this->limitBillingType
        );
    }

    public $workflowList = [];
    public $queryWorkflow = null;
    public $pageWorkflowSearch = 1;
    public $limitWorkflow = 10;

    public function searchWorkflowByString()
    {
        $this->pageWorkflowSearch = 1;
        $this->workflowList = $this->searchWorkflow();
    }

    public function loadMoreWorkflow()
    {
        $this->pageWorkflowSearch++;
        $oldWorkflowList = $this->workflowList;
        return $this->workflowList = array_merge($oldWorkflowList, $this->searchWorkflow());
    }

    public function searchWorkflow()
    {
        return $this->workflowList = (new Workflow())->getWorkFlowList(
            $this->queryWorkflow,
            $this->pageWorkflowSearch,
            $this->limitWorkflow
        );
    }

    public $centerCostList = [];
    public $queryCenterCost = null;
    public $pageCenterCostSearch = 1;
    public $limitCenterCost = 10;

    public function searchCenterCostByString()
    {
        $this->pageCenterCostSearch = 1;
        $this->centerCostList = $this->searchCenterCost();
    }

    public function loadMoreCenterCost()
    {
        $this->pageCenterCostSearch++;
        $oldCenterCostList = $this->centerCostList;
        return $this->centerCostList = array_merge($oldCenterCostList, $this->searchCenterCost());
    }

    public function searchCenterCost()
    {
        return $this->centerCostList = (new CentroCusto())->getCenterCostList(
            $this->queryCenterCost,
            $this->pageCenterCostSearch,
            $this->limitCenterCost
        );
    }

    public $apportionmentList = [];
    public $queryApportionment = null;
    public $pageApportionmentSearch = 1;
    public $limitApportionment = 10;

    public function searchApportionmentByString()
    {
        $this->pageApportionmentSearch = 1;
        $this->apportionmentList = $this->searchApportionment();
    }

    public function loadMoreApportionment()
    {
        $this->pageApportionmentSearch++;
        $oldApportionmentList = $this->apportionmentList;
        return $this->apportionmentList = array_merge($oldApportionmentList, $this->searchApportionment());
    }

    public function searchApportionment()
    {
        return $this->apportionmentList = (new Rateio())->getApportionmentList(
            $this->queryApportionment,
            $this->pageApportionmentSearch,
            $this->limitApportionment
        );
    }

    public function addInstallment()
    {
        $this->installmentStatus[] = [
            'status' => "",
            'data' => null,
            'valor' => 0.00,
            'id' => null,
            "markedToDelete" => false
        ];
    }
    public $idsToDeleteOrPay = [];
    public $showActionButton = false;
    public $sumChecked = [];
    public $pvvIdsToDeleteOrPay = [];
    public function sumToDelete($id, $value)
    {
        if(key_exists($id, $this->sumChecked)) {
            unset($this->sumChecked[$id]);
        }else{
            $this->sumChecked[$id] = $value;
        }
        $this->dispatch('showSelectedValue', array_sum($this->sumChecked));
    }
    public function markIdToDeleteOrPay($id, $pvvId)
    {
        if (in_array($id, $this->idsToDeleteOrPay)) {
            // Remove o ID se já estiver na lista
            $this->idsToDeleteOrPay = array_diff($this->idsToDeleteOrPay, [$id]);
            $this->pvvIdsToDeleteOrPay = array_diff($this->pvvIdsToDeleteOrPay, [$pvvId]);
        } else {
            // Adiciona o ID se não estiver na lista
            $this->idsToDeleteOrPay[] = $id;
            $this->pvvIdsToDeleteOrPay[] = $pvvId;
        }
    }

    public function rmToSum()
    {
        $this->sumChecked = [];
        $this->dispatch('showSelectedValue', 0);
    }

    public $processToDelete = [];
    public function askMassDelete()
    {
        foreach($this->idsToDeleteOrPay as $id){
            $processQueryBuilder = new ProcessQueryBuilder();
            $processService = new ProcessService($processQueryBuilder);
            $teste = $processService->byId($id)->list();
            $processos[] = $teste->toArray();
        }
        $this->processToDelete = $processos;
        $this->dispatch('showModal', 'modalMassDelete');
    }
    public function massDelete()
    {
        foreach ($this->idsToDeleteOrPay as $process) {
            if($process[0]->pago == 0){
                $account = new Account();
                $account->deleteAccount($process[0]->id);
            }
        }
        $this->processToDelete = [];
        $this->idsToDeleteOrPay = [];
        $this->pvvIdsToDeleteOrPay = [];
        $this->dispatch('clearCheckboxes');
    }

    public function cancelMassDelete()
    {
        foreach ($this->idsToDelete as $id) {
            $this->idsToDelete = array_diff($this->idsToDelete, [$id]);
        }
        $this->dispatch('clearCheckboxes');
    }
    public $setToOpenId = null;
    public $setToOpenPvvId = null;
    public function askSetToOpen($id, $pvvId)
    {
        $this->setToOpenId = $id;
        $this->setToOpenPvvId = $pvvId;
        $this->dispatch('showModal', 'modalSetToOpen');
    }

    public function cancelAskSetToOpen()
    {
        $this->setToOpenId = null;
        $this->setToOpenPvvId = null;
    }
    public function setToOpen()
    {
       $account = new Account();
       $account->setToOpen($this->setToOpenId, $this->setToOpenPvvId);
        (new LogsNumber())->saveLog([
            'id_empresa' => auth()->user()->id_empresa,
            'message' => 'Usuario '.auth()->user()->name.' removeu pagamento do processo ' . $this->setToOpenId,
        ]);
        $this->dispatch('closeModal', 'modalShowAccount');
         $this->dispatch('clickRefresh', $this->setToOpenId, $this->setToOpenPvvId);
    }

    public function createHistory($acao)
    {
        ProcessoHistorico::create(
            [
                'id_usuario' => auth()->user()->id,
                'id_processo' => $this->id,
                'acao' => $acao,
            ]
        );
    }

    public $amountWithLateInterestAndDiscount = null;

    public function setAmountWithLateInterestAndDiscount()
    {
        $this->calculateAmountWithLateInterestAndDiscount();
    }
    public function calculateAmountWithLateInterestAndDiscount()
    {
        $amount = FormatUtils::formatMoneyDb($this->paymentValue);
        $lateInterest = FormatUtils::formatMoneyDb($this->interest);
        $fine = FormatUtils::formatMoneyDb($this->fine);
        $discount = FormatUtils::formatMoneyDb($this->discount);
        $amountWithLateInterestAndDiscount = $amount + $lateInterest + $fine - $discount;
        $this->amountWithLateInterestAndDiscount = FormatUtils::formatMoney($amountWithLateInterestAndDiscount);
    }
}
