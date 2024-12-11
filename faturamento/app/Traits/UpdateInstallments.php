<?php

namespace App\Traits;

use App\Helpers\FormatUtils;
use App\Models\Processo;
use App\Models\ProcessoVencimentoValor;

trait UpdateInstallments
{
    public function getInstallments($processId)
    {
        $this->installmentIds = [];
        $this->installmentDates = [];
        $this->installmentValues = [];
        $this->installmentPaymentStatus = [];

        foreach(ProcessoVencimentoValor::where('id_processo', $processId)->get() as $key => $installment){
            $this->installmentIds[] = $installment->id;
            $this->installmentDates[] = date('Y-m-d', strtotime($installment->data_vencimento));
            $this->installmentValues[] = FormatUtils::formatMoney($installment->price);
            $this->installmentPaymentStatus[] = $installment->pago;
        }

        if($this->installments > 1 and $this->paymentCondition == 'prazo'){
            if($this->installments > count($this->installmentIds)) {
                foreach (range(1, ($this->installments-count($this->installmentIds))) as $key) {
                    $this->installmentIds[] = null;
                    $this->installmentDates[] = date('Y-m-d', strtotime($this->pvv_dtv . " + $key month"));
                    $this->installmentValues[] = null;
                    $this->installmentPaymentStatus[] = 0;
                }
            }
        }

        //dd($this->installmentPaymentStatus ,$this->installmentDates, $this->installmentValues, $this->installmentIds);
        return;
    }

    public function removeInstallment($id)
    {
        if(count($this->installmentIds) == 1){
            session()->flash('error', 'A conta deve ter ao mais de uma parcela');
            return;
        }
        $installmentValue = ProcessoVencimentoValor::where('id', $id)->first();

        Processo::where('id', $this->id)
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
