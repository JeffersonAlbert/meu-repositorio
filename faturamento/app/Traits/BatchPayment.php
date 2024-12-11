<?php

namespace App\Traits;

use App\Helpers\FormatUtils;
use App\Models\Processo;
use App\Models\ProcessoVencimentoValor;
use App\Services\Account;

trait BatchPayment
{
    public $processToPay = [];
    public function askMassPay()
    {
        $verification = $this->documentVerification();
        if($verification->isEmpty()) {
            return session()->flash('error', 'Não é possivel fazer pagamento em massa de fornecedores diferentes');
        }

        $processos = Processo::select('processo.id', 'pvv.id as pvvId', 'processo.id_fornecedor', 'f.nome as fornecedor', 'processo.trace_code',
            'pvv.price')
            ->leftJoin('processo_vencimento_valor as pvv', 'pvv.id_processo', '=', 'processo.id')
            ->leftJoin('fornecedor as f', 'processo.id_fornecedor', '=', 'f.id')
            ->whereIn('pvv.id', $this->pvvIdsToDeleteOrPay)
            ->get();

        $this->processToPay = $processos->toArray();
        $this->dispatch('showModal', 'modalMassPay');
    }


    public function massPay()
    {
        $account = new Account();
        //dd($this->processToPay);
        if($this->bankId == null){
            return session()->flash('error', 'Selecione um banco');
        }

        if($this->paymentMethodId == null){
            return session()->flash('error', 'Selecione uma forma de pagamento');
        }

        foreach($this->processToPay as $process){
            $result[] = $account->payAccount([
                'id_processo' => $process['id'],
                'id_processo_vencimento_valor' => $process['pvvId'],
                'valor_pago' => FormatUtils::formatMoney($process['price']),
                'juros' => 0,
                'multa' => 0,
                'desconto' => 0,
                'data_pagamento' => date('Y-m-d'),
                'forma_pagamento' => $this->paymentMethodId,
                'id_banco' => $this->bankId,
                'files' => [],
                'observacao' => 'Feito pagamento em massa',
            ]);
        }
        $this->bankId = null;
        $this->bankName = null;
        $this->pvvIdsToDeleteOrPay = [];
        $this->idsToDeleteOrPay = [];
        $this->dispatch('hideModal', 'modalMassPay');
        $this->dispatch('clearCheckboxes');
        $this->render();
    }

    public function cancelMassPay()
    {
        $this->bankId = null;
        $this->bankName = null;
        $this->pvvIdsToDeleteOrPay = [];
        $this->idsToDeleteOrPay = [];
        $this->dispatch('hideModal', 'modalMassPay');
        $this->dispatch('clearCheckboxes');
        $this->render();
    }
    public function documentVerification()
    {
        return Processo::leftJoin('processo_vencimento_valor as pvv', 'pvv.id_processo', '=', 'processo.id')
            ->leftJoin('fornecedor as f', 'processo.id_fornecedor', '=', 'f.id')
            ->whereIn('pvv.id', $this->pvvIdsToDeleteOrPay)
            ->groupBy('processo.id_fornecedor')
            ->havingRaw('COUNT(DISTINCT pvv.id) = ?', [count($this->pvvIdsToDeleteOrPay)])
            ->pluck('processo.id_fornecedor');
    }
}
