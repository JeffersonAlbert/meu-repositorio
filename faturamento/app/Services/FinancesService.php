<?php

namespace App\Services;

use App\Helpers\Utils;
use App\Models\ContasReceber;
use App\Models\Processo;
use App\Models\Vendas;
use Illuminate\Support\Facades\DB;

class FinancesService
{
    protected $idEnterprise;

    public function __construct($idEnterprise)
    {
        $this->idEnterprise = $idEnterprise;
    }

    public function calculateReceivePaymentsByPeriod($start, $end)
    {
       return ContasReceber::select('contas_receber.valor_vencimento as valor')
           ->leftJoin('receber_vencimento_valor', 'receber_vencimento_valor.id_contas_receber', 'contas_receber.id')
           ->where('id_empresa', $this->idEnterprise)
           ->whereBetween('receber_vencimento_valor.vencimento', [$start, $end])
           ->whereNotNull('status')
           ->sum('valor');
    }

    public function calculateFixedExpensesByPeriod($start, $end)
    {
        return Processo::leftJoin('processo_vencimento_valor','processo_vencimento_valor.id_processo','processo.id')
            ->leftJoin('sub_categoria_dre', 'processo.id_sub_dre', 'sub_categoria_dre.id')
            ->leftJoin('vinculo_dre', 'sub_categoria_dre.vinculo_dre', 'vinculo_dre.id')
            ->where('processo.id_empresa', $this->idEnterprise)
            ->whereBetween('data_vencimento', [$start, $end])
            ->where('processo_vencimento_valor.pago', true)
            ->whereIn('vinculo_dre.id', [8, 9, 10])
            ->sum('processo.valor');
    }

    public function calculateOperationalCostsByPeriod($start, $end)
    {
        return Processo::leftJoin('processo_vencimento_valor','processo_vencimento_valor.id_processo','processo.id')
            ->leftJoin('sub_categoria_dre', 'processo.id_sub_dre', 'sub_categoria_dre.id')
            ->leftJoin('vinculo_dre', 'sub_categoria_dre.vinculo_dre', 'vinculo_dre.id')
            ->where('processo.id_empresa', $this->idEnterprise)
            ->whereBetween('data_vencimento', [$start, $end])
            ->where('processo_vencimento_valor.pago', true)
            ->whereIn('vinculo_dre.id', [7])
            ->sum('processo.valor');
    }

    public function calculateTaxDeductionsByPeriod($start, $end)
    {
        return Processo::leftJoin('processo_vencimento_valor','processo_vencimento_valor.id_processo','processo.id')
            ->leftJoin('sub_categoria_dre', 'processo.id_sub_dre', 'sub_categoria_dre.id')
            ->leftJoin('vinculo_dre', 'sub_categoria_dre.vinculo_dre', 'vinculo_dre.id')
            ->where('processo.id_empresa', $this->idEnterprise)
            ->whereBetween('data_vencimento', [$start, $end])
            ->where('processo_vencimento_valor.pago', true)
            ->whereIn('vinculo_dre.id', [5, 6])
            ->sum('processo.valor');
    }

    public function invoiceHistoryAnual()
    {
        $result = ContasReceber::leftJoin('sub_categoria_dre', 'contas_receber.sub_categoria_dre', 'sub_categoria_dre.id')
            ->leftJoin('vinculo_dre', 'sub_categoria_dre.vinculo_dre', 'vinculo_dre.id')
            ->where('contas_receber.id_empresa', $this->idEnterprise)
            ->leftJoin('receber_vencimento_valor', 'receber_vencimento_valor.id_contas_receber', 'contas_receber.id')
            ->whereNotNull('status')
            ->whereBetween('receber_vencimento_valor.vencimento', [date('Y-01-01'), date('Y-12-31')])
            ->selectRaw('DATE_FORMAT(receber_vencimento_valor.vencimento, "%Y-%m") as mes,
                SUM(contas_receber.valor_vencimento) as valor')
            ->groupBy('mes')
            ->get()
            ->pluck('valor', 'mes');
        return Utils::monthNoValueList($result);
    }

    public function expensesHistoryAnual()
    {
        $result = Processo::leftJoin('processo_vencimento_valor','processo_vencimento_valor.id_processo','processo.id')
            ->leftJoin('pagamentos', 'processo_vencimento_valor.id', 'pagamentos.id_processo_vencimento_valor')
            ->where('processo.id_empresa', $this->idEnterprise)
            ->whereBetween('pagamentos.data_pagamento', [date('Y-01-01'), date('Y-12-31')])
            ->where('processo_vencimento_valor.pago', true)
            ->selectRaw('DATE_FORMAT(pagamentos.data_pagamento, "%Y-%m") as mes,
                SUM(processo_vencimento_valor.price) as valor')
            ->groupBy('mes')
            ->get()
            ->pluck('valor', 'mes');
        return Utils::monthNoValueList($result);
    }

    public function expensesQuartelyHistoryBySupplierId($supplierId)
    {
        $result = Processo::leftJoin('processo_vencimento_valor','processo_vencimento_valor.id_processo','processo.id')
            ->where('processo.id_empresa', $this->idEnterprise)
            ->whereBetween('processo_vencimento_valor.data_vencimento', [date('Y-01-01'), date('Y-12-31')])
            ->where('processo.id_fornecedor', $supplierId)
            ->where('processo.deletado', '!=' ,true)
            ->selectRaw('DATE_FORMAT(processo_vencimento_valor.data_vencimento, "%Y-%m") as mes,
                SUM(processo_vencimento_valor.price) as valor')
            ->groupBy('mes')
            ->get()
            ->pluck('valor', 'mes');
        return Utils::monthNoValueList($result);
    }

    public function paidExpensesQuartelyHistoryBySupplierId($supplierId)
    {
        $result = Processo::leftJoin('processo_vencimento_valor','processo_vencimento_valor.id_processo','processo.id')
            ->where('processo.id_empresa', $this->idEnterprise)
            ->whereBetween('processo_vencimento_valor.data_vencimento', [date('Y-01-01'), date('Y-12-31')])
            ->where('processo.id_fornecedor', $supplierId)
            ->where('processo_vencimento_valor.pago', true)
            ->selectRaw('DATE_FORMAT(processo_vencimento_valor.data_vencimento, "%Y-%m") as mes,
                SUM(processo_vencimento_valor.price) as valor')
            ->groupBy('mes')
            ->get()
            ->pluck('valor', 'mes');
        return Utils::monthNoValueList($result);
    }
}
