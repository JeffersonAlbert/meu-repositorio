<?php

namespace App\Livewire;

use App\Helpers\FormatUtils;
use App\Models\ContasReceber;
use App\Models\ReceberVencimentoValor;
use App\QueryBuilder\ReceivableQueryBuilder;
use App\Services\Periods;
use App\Services\UploadService;
use App\Traits\ApportionmentList;
use App\Traits\BillingTypeList;
use App\Traits\CenterCostList;
use App\Traits\ClientsList;
use App\Traits\DREList;
use App\Traits\Files;
use App\Traits\Installments;
use App\Traits\ReceivableDetails;
use App\Traits\WorkflowList;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;

class AccountsReceivable extends Component
{
    use ClientsList;
    use DREList;
    use BillingTypeList;
    use WorkflowList;
    use CenterCostList;
    use ApportionmentList;
    use Files;
    use Installments;
    use WithFileUploads;
    use WithPagination;
    use ReceivableDetails;
    public $queryPeriod = null;
    public $period = null;
    public $receivableByStatus = [];
    public $billingDateRangeStart = null;
    public $billingDateRangeEnd = null;
    public $newTraceCode = null;
    public $dreType = 'receita';
    public $financeCategoryId = null;
    public $dreDescription = null;
    public $billingTypeId = null;
    public $billingTypeName = null;
    public $centerCostId = null;
    public $centerCostName = null;
    public $clientId = null;
    public $clientName = null;
    public $numberNotaFiscal = null;
    public $emissionDate = null;
    public $competence = null;
    public $paymentCondition = null;
    public $installments = [];
    public $installmentsQtd = null;
    public $totalValue = null;
    public $pay = false;
    public $askInstallments = false;
    public $observation = null;
    public $bankId = null;
    public $bankName = null;
    public $showType = 'totalForPeriod';
    public $update = false;

    public function render()
    {
        $this->getReceivableByStatus();
        $this->searchClient();
        $this->searchDre();
        $this->searchBillingType();
        $this->searchCenterCost();
        if($this->queryPeriod == null) {
            $this->setPeriod('thisMonth');
        }
        $this->period = [
            'startDate' => $this->billingDateRangeStart ?? date('Y-m-01'),
            'endDate' => $this->billingDateRangeEnd ?? date('Y-m-t')
        ];
        ///$this->searchReceivable();
        return view('livewire.receivable.accounts-receivable',[
            'receivableList' => $this->searchReceivable()
        ]);
    }

    public function generateTraceCodeAndCleanForm()
    {
        $this->reset();
        $this->update = true;
        $this->generateNewTraceCode();
    }

    public function generateNewTraceCode()
    {
        $this->newTraceCode = FormatUtils::traceCode();
    }

    public function getReceivableByStatus()
    {
        $this->receivableByStatus = [
            'totalOverdue' => 1000,
            'totalDueToday' => 500,
            'totalDue' => 500,
            'totalApproved' => 500,
            'total' => 500,
        ];
    }

    public function setPeriod($period)
    {
        $periodArray = (new Periods())->setPeriod($period);

        $this->billingDateRangeStart = $periodArray['startDate'];
        $this->billingDateRangeEnd = $periodArray['endDate'];
        $this->queryPeriod = $periodArray['queryPeriod'];
    }

    public function searchReceivable()
    {
        $receivable = new ReceivableQueryBuilder();
        $receivableList = $receivable->byBillingDateRange($this->billingDateRangeStart, $this->billingDateRangeEnd)
            ->list();
        //dd($receivableList);
        return $receivableList;
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

    public function selectBank($id, $name)
    {
        $this->bankId = $id;
        $this->bankName = $name;
    }

    public function selectCenterCost($id, $name)
    {
        $this->centerCostId = $id;
        $this->centerCostName = $name;
    }

    public function selectClient($id, $name)
    {
        $this->clientId = $id;
        $this->clientName = $name;
    }

    public function setAskInstallmentTrue()
    {
        $this->askInstallments = true;
    }
    public function saveAccount()
    {
        if($this->paymentCondition == 'prazo' and $this->askInstallments == false){
            $this->dispatch('showModal', 'modalInstallments');
            return;
        }
        $rules = [
            'clientId' => 'required',
            'numberNotaFiscal' => 'required',
            'emissionDate' => 'required|date',
            'competence' => 'required|date',
            'paymentCondition' => 'required',
            'financeCategoryId' => 'required',
            'billingTypeId' => 'required',
            'installments.0.value' => 'required',
            'installments.0.date' => 'required',
            'totalValue' => 'required',
        ];

        $messages = [
            'clientId.required' => 'Cliente é obrigatório',
            'numberNotaFiscal.required' => 'Número da nota fiscal é obrigatório',
            'emissionDate.required' => 'Data de emissão é obrigatória',
            'competence.required' => 'Data da competência é obrigatória',
            'paymentCondition.required' => 'Condição de pagamento é obrigatória',
            'financeCategoryId.required' => 'Categoria financeira é obrigatória',
            'billingTypeId.required' => 'Tipo de faturamento é obrigatório',
            'installments.0.value.required' => 'Valor da parcela é obrigatório',
            'installments.0.date.required' => 'Data da parcela é obrigatória',
            'totalValue.required' => 'Valor total é obrigatório',
        ];

        foreach($this->installmentsInputs as $key => $installment){
            $index = $key + 1;
            $rules["installments.{$index}.value"] = 'required';
            $rules["installments.{$index}.date"] = 'required';
            $messages["installments.{$index}.value.required"] = 'Valor da parcela é obrigatório';
            $messages["installments.{$index}.date.required"] = 'Data da parcela é obrigatória';
        }

        $validatedData = $this->validate($rules, $messages);

        $upload = new UploadService();
        $this->filesArray = $upload->uploadFiles($this->accountFiles, $this->accountFilesType, $this->accountFilesDescription);

        foreach ($this->installments as $key => $installment) {
            $receivable = ContasReceber::create([
                'id_empresa' => auth()->user()->id_empresa,
                'id_usuario' => auth()->user()->id,
                'sub_categoria_dre' => $this->financeCategoryId,
                'id_centro_custo' => $this->centerCostId,
                'id_cliente' => $this->clientId,
                'vencimento' => $installment['date'],
                'data_emissao' => $this->emissionDate,
                'valor_total' => FormatUtils::formatMoneyDb($this->totalValue),
                'trace_code' => $this->newTraceCode,
                'valor_vencimento' => FormatUtils::formatMoneyDb($installment['value']),
                'tipo' => 'receita',
                'competencia' => $this->competence,
                'descricao' => $this->numberNotaFiscal,
                'id_categoria' => $this->billingTypeId,
                'codigo_referencia' => '0',
                'condicao' => $this->paymentCondition,
                'files_types_desc' => json_encode($this->filesArray),
                'observacao' => $this->observation,
            ]);

            if($this->pay == true){
                ReceberVencimentoValor::create([
                    'id_contas_receber' => $receivable->id,
                    'id_usuario' => auth()->user()->id,
                    'vencimento' => $installment['date'],
                    'valor' => FormatUtils::formatMoneyDb($installment['value']),
                    'status' => 'pago',
                ]);
            }
        }

        $this->reset();
        $this->dispatch('receivableAdded');
        $this->generateNewTraceCode();
    }

    public $paymentValue = null;
    public $paymentDate = null;
    public $paymentMethodId = null;
    public $paymentFees = null;
    public $paymentFine = null;
    public $paymentDiscount = null;
    public $amountWithLateInterestAndDiscount = '0,00';
    public $paymentObservation = null;
    public $paymentFiles = [];
    public $paymentId = null;
    public $paymentValuePaid = null;
    public function toReceive($id)
    {
        $receivableDetail = (new ReceivableQueryBuilder())->byId($id)->first();
        $this->paymentId = $receivableDetail->id;
        $this->paymentValue = FormatUtils::formatMoney($receivableDetail->valor);
        $this->paymentDate = $receivableDetail->vencimento;
        $this->paymentFiles = json_decode($receivableDetail->files);
        $this->dispatch('showModal', 'modalToReceive');
    }

    public function receiveAccount($id)
    {
        $rules = [
            'paymentValuePaid' => 'required',
            'paymentDate' => 'required',
            'paymentMethodId' => 'required',
            'bankId' => 'required',
        ];

        $messages = [
            'paymentValuePaid.required' => 'Valor é obrigatório',
            'paymentDate.required' => 'Data é obrigatória',
            'paymentMethodId.required' => 'Método de pagamento é obrigatório',
            'bankId.required' => 'Banco é obrigatório',
        ];

        $this->validate($rules, $messages);

        $upload = new UploadService();
        $this->filesArray = $upload->uploadFiles($this->accountFiles, $this->accountFilesType, $this->accountFilesDescription);
        $filesArray = $upload->mergeFiles(json_encode($this->paymentFiles), $this->filesArray);

        $receiveAccount = ReceberVencimentoValor::create([
            'id_contas_receber' => $id,
            'id_usuario' => auth()->user()->id,
            'vencimento' => $this->paymentDate,
            'valor' => FormatUtils::formatMoneyDb($this->amountWithLateInterestAndDiscount),
            'observacao' => $this->paymentObservation,
            'status' => 'pago',
            'juros' => FormatUtils::formatMoneyDb($this->paymentFees),
            'multa' => FormatUtils::formatMoneyDb($this->paymentFine),
            'desconto' => FormatUtils::formatMoneyDb($this->paymentDiscount),
            'id_bank' => $this->bankId,
        ]);

        ContasReceber::where('id', $id)->update([
            'files_types_desc' => json_encode($filesArray)
        ]);

        $this->paymentValue = null;
        $this->paymentDate = null;
        $this->paymentMethodId = null;
        $this->paymentFees = null;
        $this->paymentFine = null;
        $this->paymentDiscount = null;
        $this->paymentObservation = null;
        $this->paymentFiles = [];
        $this->paymentId = null;
        $this->paymentValuePaid = null;
        $this->amountWithLateInterestAndDiscount = '0,00';
        $this->dispatch('hideModal', 'modalToReceive');
        $this->showDetails($id);
        return session()->flash('success', 'Conta recebida com sucesso!');
    }

    public function caluculateAmountWithLateInterestAndDiscount()
    {
        $this->amountWithLateInterestAndDiscount = FormatUtils::formatMoney(
            FormatUtils::formatMoneyDb($this->paymentValuePaid) +
            FormatUtils::formatMoneyDb($this->paymentFees) +
            FormatUtils::formatMoneyDb($this->paymentFine) -
            FormatUtils::formatMoneyDb($this->paymentDiscount)
        );
    }
    public $files = [];
    public $editableId = null;
    public function toEdit($id)
    {
        $this->update = true;
        $receivableDetail = (new ReceivableQueryBuilder())->byId($id)->first();
        $this->editableId = $id;
        $this->clientName = $receivableDetail->cliente;
        $this->clientId = $receivableDetail->cliente_id;
        $this->numberNotaFiscal = $receivableDetail->codigo_referencia;
        $this->emissionDate = $receivableDetail->data_emissao;
        $this->competence = $receivableDetail->competencia;
        $this->paymentCondition = $receivableDetail->condicao;
        $this->financeCategoryId = $receivableDetail->dre_categoria;
        $this->dreDescription = $receivableDetail->descricao;
        $this->billingTypeId = $receivableDetail->id_categoria;
        $this->billingTypeName = $receivableDetail->tipo_cobranca_nome;
        $this->centerCostId = $receivableDetail->id_centro_custo;
        $this->centerCostName = $receivableDetail->centro_custo;
        $this->totalValue = FormatUtils::formatMoney($receivableDetail->valor_total);
        $this->observation = $receivableDetail->observacao;
        $this->traceCode = $receivableDetail->trace_code;
        $this->files = $receivableDetail->files == null ? [] : json_decode($receivableDetail->files);
        $this->installments[] = [
            'date' => $receivableDetail->vencimento,
            'value' => FormatUtils::formatMoney($receivableDetail->valor)
        ];
        $this->traceCode = $receivableDetail->trace_code;
        $this->dispatch('showModal', 'modalEditAccount');
    }

    public function sendUpdateAccount($id)
    {
        $rules = [
            'clientId' => 'required',
            'numberNotaFiscal' => 'required',
            'emissionDate' => 'required|date',
            'competence' => 'required|date',
            'paymentCondition' => 'required',
            'financeCategoryId' => 'required',
            'billingTypeId' => 'required',
            'installments.0.value' => 'required',
            'installments.0.date' => 'required',
            'totalValue' => 'required',
        ];

        $messages = [
            'clientId.required' => 'Cliente é obrigatório',
            'numberNotaFiscal.required' => 'Número da nota fiscal é obrigatório',
            'emissionDate.required' => 'Data de emissão é obrigatória',
            'competence.required' => 'Data da competência é obrigatória',
            'paymentCondition.required' => 'Condição de pagamento é obrigatória',
            'financeCategoryId.required' => 'Categoria financeira é obrigatória',
            'billingTypeId.required' => 'Tipo de faturamento é obrigatório',
            'installments.0.value.required' => 'Valor da parcela é obrigatório',
            'installments.0.date.required' => 'Data da parcela é obrigatória',
            'totalValue.required' => 'Valor total é obrigatório',
        ];

        foreach($this->installmentsInputs as $key => $installment){
            $index = $key + 1;
            $rules["installments.{$index}.value"] = 'required';
            $rules["installments.{$index}.date"] = 'required';
            $messages["installments.{$index}.value.required"] = 'Valor da parcela é obrigatório';
            $messages["installments.{$index}.date.required"] = 'Data da parcela é obrigatória';
        }

        $validatedData = $this->validate($rules, $messages);

        $upload = new UploadService();
        $this->filesArray = $upload->uploadFiles($this->accountFiles, $this->accountFilesType, $this->accountFilesDescription);
        $filesArray = $upload->mergeFiles(json_encode($this->files), $this->filesArray);

        ContasReceber::where('id', $id)->update([
            'id_empresa' => auth()->user()->id_empresa,
            'id_usuario' => auth()->user()->id,
            'sub_categoria_dre' => $this->financeCategoryId,
            'id_centro_custo' => $this->centerCostId,
            'id_cliente' => $this->clientId,
            'vencimento' => $this->installments[0]['date'],
            'data_emissao' => $this->emissionDate,
            'valor_total' => FormatUtils::formatMoneyDb($this->totalValue),
            'trace_code' => $this->traceCode,
            'valor_vencimento' => FormatUtils::formatMoneyDb($this->installments[0]['value']),
            'tipo' => 'receita',
            'competencia' => $this->competence,
            'descricao' => $this->numberNotaFiscal,
            'id_categoria' => $this->billingTypeId,
            'codigo_referencia' => '0',
            'condicao' => $this->paymentCondition,
            'files_types_desc' => json_encode($filesArray),
            'observacao' => $this->observation,
        ]);

        $this->paymentValue = null;
        $this->paymentDate = null;
        $this->paymentMethodId = null;
        $this->paymentFees = null;
        $this->paymentFine = null;
        $this->paymentDiscount = null;
        $this->paymentObservation = null;
        $this->paymentFiles = [];
        $this->paymentId = null;
        $this->paymentValuePaid = null;
        $this->amountWithLateInterestAndDiscount = '0,00';
        $this->accountFiles = [];
        $this->accountFilesType = [];
        $this->accountFilesDescription = [];
        $this->dispatch('hideModal', 'modalEditAccount');
        $this->showDetails($id);
        return session()->flash('success', 'Conta alterada com sucesso!');
    }
}
