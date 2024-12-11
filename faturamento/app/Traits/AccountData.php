<?php

namespace App\Traits;

use App\Helpers\FormatUtils;
use App\Helpers\Utils;
use App\Models\Processo;
use App\Models\ProcessoVencimentoValor;
use App\QueryBuilder\ProcessQueryBuilder;
use App\Services\Account;
use App\Services\ProcessService;
use App\Services\UploadService;

trait AccountData
{
    public $numberNotaFiscal;
    public $totalValue;
    public $tax;
    public $valueOfTheFirstInstallment;
    public $dateOfTheFirstInstallment;
    public $installments;
    public $dreDescription;
    public $billingTypeName;
    public $centerCostName;
    public $rateio;
    public $supplierId;
    public $centerCostId;
    public $billingTypeId;
    public $financeCategoryId;
    public $apportionmentId;
    public $id;
    public $installmentStatus;
    public $pvv_id;
    //public $supplierName = null;


    public function setPropertiesAccountData($traceCode)
    {
        $this->traceCode = $traceCode;
        $processQueryBuilder = new ProcessQueryBuilder();
        $processService = new ProcessService($processQueryBuilder);
        $account = $processService->byTraceCode($traceCode)->first();
        $this->parcelas = $processService->byTraceCode($traceCode)->list();
        $this->id = $account->id;
        $this->f_doc = $account->f_doc;
        $this->supplierId = $account->f_id;
        $this->totalValue = FormatUtils::formatMoney($account->valor);
        $this->formatedTotalValue = FormatUtils::formatMoney($account->valor);
        $this->paymentCondition = $account->condicao;
        $this->tax = $account->f_doc == null ? true : false;
        $this->created_at = $account->created_at;
        $this->pvv_id = $account->pvv_id;
        $this->pvv_dtv = $account->pvv_dtv;
        $this->vparcela = $account->vparcela;
        $this->u_name = $account->u_name;
        $this->f_name = $account->f_name;
        $this->f_id = $account->f_id;
        $this->numberNotaFiscal = $account->num_nota;
        $this->file = $account->file;
        $this->p_emissao = $account->p_emissao;
        $this->u_last_modification = $account->u_last_modification;
        $this->pendencia = $account->pendencia;
        $this->updated_at = $account->updated_at;
        $this->p_workflow_id = $account->p_workflow_id;
        $this->workflowName = $account->workflow;
        $this->trace_code = $account->trace_code;
        $this->aprovado = $account->aprovado;
        $this->pay = $account->pago;
        $this->billingTypeName = $account->tipo_cobranca;
        $this->billingTypeId = $account->id_tipo_cobranca;
        $this->banco = $account->banco;
        $this->forma_pagamento = $account->forma_pagamento;
        $this->id_rateio = $account->id_rateio;
        $this->centerCostId = $account->id_centro_custo;
        $this->filial_nome = $account->filial_nome;
        $this->contrato = $account->contrato;
        $this->produto = $account->produto;
        $this->centerCostName = $account->centro_custo;
        $this->rateio = $account->rateio;
        $this->apportionmentId = $account->id_rateio;
        $this->dreDescription = $account->dre_categoria;
        $this->financeCategoryId = $account->id_sub_dre;
        $this->competencia = $account->competencia;
        $this->observacao = $account->observacao;
        $this->files_type_description = $account->file_type_description;
        $this->dt_parcelas = $account->dt_parcelas;
        $installments = $this->installmentAdjust($account->dt_parcelas);
        $this->valueOfTheFirstInstallment = is_float($installments[0]['valor0']) ?
            FormatUtils::formatMoney($installments[0]['valor0']) :
            FormatUtils::formatMoney(FormatUtils::formatMoneyDb($installments[0]['valor0']));
        $this->dateOfTheFirstInstallment = $installments[0]['data0'];
        $this->getInstallmets($installments);
        $this->installments = $account->parcelas;
        $calculatedDetail = Utils::calculateDetailPayment($this->parcelas);
        $this->valorPago = $calculatedDetail['valorPago'];
        $this->valorPagar = $calculatedDetail['valorPagar'];
        $this->jurosPago = $calculatedDetail['jurosPago'];
        $this->multasPago = $calculatedDetail['multasPago'];
        $this->descontosPago = $calculatedDetail['descontosPago'];
        $this->installmentStatus = Utils::identifyPayment($this->parcelas);
        $this->detailedAccountData = $account;
        $this->approval_progress = Utils::calculateApprovedAccounts($this->parcelas);
        $this->approvedBy = Utils::whoApproved($account);
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

    public $installmentDates = [];
    public $installmentValues = [];
    public $installmentIds = [];
    public $installmentPaymentStatus = [];
    public function getInstallmets()
    {
        $this->installmentIds = [];
        $this->installmentDates = [];
        $this->installmentValues = [];
        $this->installmentPaymentStatus = [];

        foreach(ProcessoVencimentoValor::where('id_processo', $this->processId)->get() as $key => $installment){
            $this->installmentIds[] = $installment->id;
            $this->installmentDates[] = date('Y-m-d', strtotime($installment->data_vencimento));
            $this->installmentValues[] = FormatUtils::formatMoney($installment->price);
            $this->installmentPaymentStatus[] = $installment->pago;
        }
        //dd($this->installmentPaymentStatus ,$this->installmentDates, $this->installmentValues, $this->installmentIds);
        return;
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

        $account->updateInstallments(
            count($this->installmentIds),
            $this->installmentIds,
            $this->installmentDates,
            $this->installmentValues,
            $this->processId
        );
        $result = $account->updateAccount($accountData);


        if (isset($result->errors)) {
            $this->errorMessages = $result->errors;
        }

        if(isset($result->success)){
            session()->flash('success', 'Conta atualizada com sucesso');
            $this->dispatch('updatedAccount', $this->id.$this->pvv_id);
            $this->render();
        }
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

    public function setInstallments()
    {
        $installmentsValues = [];
        foreach($this->installmentValues as $value){
            $installmentsValues[] = FormatUtils::formatMoneyDb($value);
        }

        if(array_sum($installmentsValues) != FormatUtils::formatMoneyDb($this->totalValue)){
            session()->flash('error', 'A soma dos valores das parcelas não é igual ao valor total da conta');
            return false;
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

    public function removeInstallment($id)
    {
        if(count($this->installmentIds) == 1){
            session()->flash('error', 'A conta deve ter ao mais de uma parcela');
            return;
        }
        $installmentValue = ProcessoVencimentoValor::where('id', $id)->first();

        Processo::where('id', $this->processId)
            ->update([
                'valor' => FormatUtils::formatMoneyDb(
                    FormatUtils::formatMoneyDb($this->totalValue) - $installmentValue->price)
            ]);

        ProcessoVencimentoValor::where('id', $id)->delete();

        if($this->pvvId == $id){
            return redirect()->route('payment-request');
        }
        $this->getInstallmets();
        $this->render();
    }
}
