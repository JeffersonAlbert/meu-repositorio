<?php

namespace App\QueryBuilder;

use Illuminate\Support\Facades\DB;

class ProcessQueryBuilder
{
    protected $query;
    protected $user;

    public function __construct()
    {
        $this->query = DB::table('processo')
            ->select(
                'processo.id as id',
                DB::raw('CASE WHEN processo.id_fornecedor = 0 THEN NULL ELSE fornecedor.cpf_cnpj END AS f_doc'),
                'processo.valor as valor',
                'processo.created_at',
                'processo.condicao',
                'processo_vencimento_valor.id as pvv_id',
                'processo_vencimento_valor.data_vencimento as pvv_dtv',
                'processo_vencimento_valor.price as vparcela',
                'user_processo.name as u_name',
                DB::raw('CASE WHEN processo.id_fornecedor = 0 THEN NULL ELSE fornecedor.nome END AS f_name'),
                'fornecedor.id as f_id',
                'processo.numero_nota as num_nota',
                'processo.doc_name as file',
                'processo.emissao_nota as p_emissao',
                'user_alteracao.name as u_last_modification',
                'processo.pendencia as pendencia',
                'processo.updated_at as updated_at',
                'processo.id_workflow as p_workflow_id',
                'processo.trace_code as trace_code',
                'processo_vencimento_valor.aprovado as aprovado',
                'processo_vencimento_valor.pago as pago',
                'tipo_cobranca.nome as tipo_cobranca',
                'bancos.nome as banco',
                'bancos.id as banco_id',
                'pagamentos.id as id_pagamento',
                'pagamentos.forma_pagamento as forma_pagamento',
                'pagamentos.valor_pago as valor_pago',
                'pagamentos.data_pagamento as data_pagamento',
                'pagamentos.juros as juros',
                'pagamentos.multa as multas',
                'pagamentos.desconto as descontos',
                'pagamentos.observacao as observacao_pagamento',
                'formas_pagamento.nome as forma_pagamento_nome',
                'processo.id_rateio as id_rateio',
                'processo.id_centro_custo as id_centro_custo',
                'filial.nome as filial_nome',
                'contratos.nome as contrato',
                'produtos.produto as produto',
                'centro_custos.nome as centro_custo',
                'rateio.nome as rateio',
                'sub_categoria_dre.descricao as dre_categoria',
                'sub_categoria_dre.id as id_sub_dre',
                'tipo_cobranca.id as id_tipo_cobranca',
                'processo.competencia as competencia',
                'processo.observacao as observacao',
                'processo.files_types_desc as file_type_description',
                'processo.dt_parcelas as dt_parcelas',
                'processo.parcelas as parcelas',
                'workflow.nome as workflow',
            )
            ->leftJoin('processo_vencimento_valor', 'processo_vencimento_valor.id_processo', 'processo.id')
            ->leftJoin('fornecedor', 'fornecedor.id', 'processo.id_fornecedor')
            ->leftJoin('filial', 'filial.id', 'processo.id_filial')
            ->join('users as user_processo', 'user_processo.id', 'processo.id_user')
            ->leftJoin('workflow', 'workflow.id', '=', 'processo.id_workflow')
            ->join('users as user_alteracao', 'user_alteracao.id', 'processo.user_ultima_alteracao')
            ->leftJoin('tipo_cobranca', 'tipo_cobranca.id', 'processo.tipo_cobranca')
            ->leftJoin('contratos', 'contratos.id', 'processo.id_contrato')
            ->leftJoin(
                'pagamentos',
                function ($join) {
                    $join
                        ->on('pagamentos.id_processo', '=', 'processo.id')
                        ->on('pagamentos.id_processo_vencimento_valor', '=', 'processo_vencimento_valor.id');
                }
            )
            ->leftJoin('bancos', 'bancos.id', 'pagamentos.id_banco')
            ->leftJoin('produtos', 'produtos.id', 'processo.id_produto')
            ->leftJoin('centro_custos', 'centro_custos.id', 'processo.id_centro_custos')
            ->leftJoin('rateio_setup as rateio', 'rateio.id', 'processo.id_rateio')
            ->leftJoin('sub_categoria_dre', 'sub_categoria_dre.id', 'processo.id_sub_dre')
            ->leftJoin('formas_pagamento', 'formas_pagamento.id', 'pagamentos.forma_pagamento');
    }

    public function useUser()
    {
        $this->query->where('processo.id_empresa', auth()->user()->id_empresa);
        return $this;
    }

    public function sortBy($field, $direction)
    {
        $this->query->orderBy($field, $direction);
        return $this;
    }

    public function setUser($user)
    {
        $this->user = $user;
    }

    public function byTraceCode($traceCode)
    {
        $this->query->where('processo.trace_code', 'like', "%$traceCode%");
        return $this;
    }

    public function isNotDisabled()
    {
        $this->query->where('processo.deletado', '!=', true);
        return $this;
    }

    public function isDisabled()
    {
        $this->query->where('processo.deletado', true);
        return $this;
    }

    public function isApproved()
    {
        $this->query->where('processo_vencimento_valor.aprovado', true);
        return $this;
    }

    public function inProcess()
    {
        $this->query->where('processo_vencimento_valor.aprovado', '!=', null);
        return $this;
    }

    public function isPendent()
    {
        $this->query
            ->where('processo_vencimento_valor.aprovado', true)
            ->where('processo_vencimento_valor.pago', null);
        return $this;
    }

    public function isPaid()
    {
        $this->query->where('processo_vencimento_valor.pago', true);
        return $this;
    }

    public function isNotPaid()
    {
        $this->query->where(
            function ($query) {
                $query
                    ->where('processo_vencimento_valor.pago', false)
                    ->orWhereNull('processo_vencimento_valor.pago');
            }
        );
        return $this;
    }

    public function bySupplier($supplierId)
    {
        $this->query->where('fornecedor.id', $supplierId);
        return $this;
    }

    public function byBillingType($billingTypeId)
    {
        $this->query->where('tipo_cobranca.id', $billingTypeId);
        return $this;
    }

    public function byBank($bankId)
    {
        $this->query->where('bancos.id', $bankId);
        return $this;
    }

    public function byPaymentMethod($paymentMethodId)
    {
        $this->query->where('pagamentos.forma_pagamento', $paymentMethodId);
        return $this;
    }

    public function byPaymentDateRange($startDate, $endDate)
    {
        $this->query->whereBetween('processo_vencimento_valor.data_pagamento', [$startDate, $endDate]);
        return $this;
    }

    public function byBillingDateRange($startDate, $endDate)
    {
        $this->query->whereBetween('processo_vencimento_valor.data_vencimento', [$startDate, $endDate]);
        return $this;
    }

    public function byApportioned($apporitionedId)
    {
        $this->query->where('processo.id_rateio', $apporitionedId);
        return $this;
    }

    public function byCenterCost($centerCostId)
    {
        $this->query->where('processo.id_centro_custo', $centerCostId);
        return $this;
    }

    public function get()
    {
        $this->useUser();
        return $this->query->get();
    }

    public function paginate($perPage)
    {
        $this->useUser();
        return $this->query->paginate($perPage);
    }

    public function list()
    {
        $this->useUser();
        return $this->query->get();
    }

    public function count()
    {
        return $this->query->count();
    }

    public function showQuery()
    {
        $this->useUser();
        return $this->query->dd();
    }

    public function first()
    {
        $this->useUser();
        return $this->query->first();
    }

    public function byId($id)
    {
        $this->query->where('processo.id', $id);
        return $this;
    }
}
