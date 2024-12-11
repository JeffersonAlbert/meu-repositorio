<?php

namespace App\Traits;

use App\Helpers\FormatUtils;
use App\Models\ContasReceber;
use App\QueryBuilder\ReceivableQueryBuilder;

trait ReceivableDetails
{
    public $detailId = null;
    public $traceCode = null;
    public $detailClientName = null;
    public $detailDreCategory = null;
    public $detailCompetence = null;
    public $detailCenterCost = null;
    public $detailVencimento = null;
    public $detailBillingType = null;
    public $detailBranch = null;
    public $detailRateio = null;
    public $detailValorTotal = null;
    public $detailObservation = null;
    public $detailParcelas = [];
    public $detailFiles = [];
    public $detailValor = null;
    public $detailJuros = null;
    public $detailMulta = null;
    public $detailDesconto = null;
    public $detailStatus = null;
    public $detailTotalPago = null;
    public $detailTotalParcela = null;
    public $detailTotalJuros = null;
    public $detailTotalMulta = null;
    public $detailTotalDesconto = null;
    public $detailTotalAberto = null;

    public function showDetails($id)
    {
        $receivableDetail = (new ReceivableQueryBuilder())->byId($id)->first();
        //dd(FormatUtils::formatMoney($receivableDetail->valor_total));
        $this->detailId = $receivableDetail->id;
        $this->traceCode = $receivableDetail->trace_code;
        $this->detailClientName = $receivableDetail->cliente;
        $this->detailDreCategory = $receivableDetail->dre_categoria;
        $this->detailCompetence = $receivableDetail->competencia;
        $this->detailCenterCost = $receivableDetail->centro_custo;
        $this->detailVencimento = $receivableDetail->vencimento;
        $this->detailBillingType = $receivableDetail->tipo_cobranca_nome;
        $this->detailBranch = $receivableDetail->filial_nome;
        $this->detailRateio = $receivableDetail->rateio;
        $this->detailValorTotal = $receivableDetail->valor_total;
        $this->detailObservation = $receivableDetail->observacao;
        $this->detailValor = $receivableDetail->valor;
        $this->detailFiles = json_decode($receivableDetail->files);
        $this->detailJuros = $receivableDetail->juros;
        $this->detailMulta = $receivableDetail->multa;
        $this->detailDesconto = $receivableDetail->desconto;
        $this->detailStatus = $receivableDetail->status;
        //dd($this->detailFiles);
        $parcelas =  (new ReceivableQueryBuilder())->byTraceCode($this->traceCode)->list();

        $detailTotalPagoArray[] = 0;
        $detailTotalParcelaArray[] = 0;
        $detailTotalJurosArray[] = 0;
        $detailTotalMultaArray[] = 0;
        $detailTotalDescontoArray[] = 0;
        $detailTotalAbertoArray[] = 0;

        foreach($parcelas as $parcela){
            if($parcela->status == 'pago') {
                $detailTotalPagoArray[] = $parcela->valor + $parcela->juros + $parcela->multa - $parcela->desconto;
                $detailTotalParcelaArray[] = $parcela->valor;
                $detailTotalJurosArray[] = $parcela->juros;
                $detailTotalMultaArray[] = $parcela->multa;
                $detailTotalDescontoArray[] = $parcela->desconto;
            }
            if($parcela->status == 'aberto' or $parcela->status == null) {
                $detailTotalAbertoArray[] = $parcela->valor;
            }
        }
        $this->detailTotalPago = array_sum($detailTotalPagoArray);
        $this->detailTotalParcela = array_sum($detailTotalParcelaArray);
        $this->detailTotalJuros = array_sum($detailTotalJurosArray);
        $this->detailTotalMulta = array_sum($detailTotalMultaArray);
        $this->detailTotalDesconto = array_sum($detailTotalDescontoArray);
        $this->detailTotalAberto = array_sum($detailTotalAbertoArray);

        $this->detailParcelas = $parcelas->toArray();
        $this->dispatch('showModal', 'modalShowAccount');
    }

}
