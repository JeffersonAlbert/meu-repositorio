<?php

namespace App\QueryBuilder;

use App\Models\GrupoOrderFluxo;
use App\Models\Processo;
use Illuminate\Support\Facades\DB;

class RequestPaymentQueryBuilder
{
    protected $query;
    public function __construct()
    {
        $authGroupIds = json_decode(auth()->user()->id_grupos);

        $subQuery = DB::table('approved_processo')
            ->select('id_processo', 'id_processo_vencimento_valor', DB::raw('MIN(id_grupo) as id_grupo'))
            ->whereIn('id_grupo', $authGroupIds)
            ->groupBy('id_processo', 'id_processo_vencimento_valor');

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
                'processo.valor',
                'processo_vencimento_valor.aprovado as aprovado',
                'processo_vencimento_valor.pago as pago',
                'tipo_cobranca.nome as tipo_cobranca',
                'user_processo.name as u_name',
                DB::raw('CASE WHEN processo.id_fornecedor = 0 THEN NULL ELSE fornecedor.nome END AS f_name'),
                'processo.numero_nota as num_nota',
                'processo.doc_name as file',
                'processo.emissao_nota as p_emissao',
                'user_alteracao.name as u_last_modification',
                'processo.pendencia as pendencia',
                'processo.updated_at as updated_at',
                'processo.id_workflow as p_workflow_id',
                'processo.trace_code as trace_code',
                'workflow.id_grupos as w_grupos',
                'filial.nome as filial_nome',
                'sub_categoria_dre.descricao as sub_categoria_dre',
                'fornecedor.id as f_id',
                'bancos.nome as banco',
                'bancos.id as banco_id',
                'formas_pagamento.nome as forma_pagamento_nome',
                'pagamentos.forma_pagamento as forma_pagamento',
                'processo.id_rateio as id_rateio',
                'processo.id_centro_custo as id_centro_custo',
                'contratos.nome as contrato',
                'produtos.produto as produto',
                'centro_custos.nome as centro_custo',
                'rateio.nome as rateio',
                'sub_categoria_dre.descricao as dre_categoria',
                'sub_categoria_dre.id as id_sub_dre',
                'processo.competencia as competencia',
                'processo.observacao as observacao',
                'processo.files_types_desc as file_type_description',
                'processo.dt_parcelas as dt_parcelas',
                'processo.parcelas as parcelas',
                'workflow.nome as workflow',
                'processo.tipo_cobranca as id_tipo_cobranca',
            )
            ->leftJoin('processo_vencimento_valor', 'processo_vencimento_valor.id_processo', 'processo.id')
            ->leftJoin('fornecedor', 'fornecedor.id', 'processo.id_fornecedor')
            ->join('users as user_processo', 'user_processo.id', 'processo.id_user')
            ->leftJoin('workflow', 'workflow.id', '=', 'processo.id_workflow')
            ->join('users as user_alteracao', 'user_alteracao.id', 'processo.user_ultima_alteracao')
            ->leftJoin('filial', 'filial.id', 'processo.id_filial')
            ->leftJoin('sub_categoria_dre', 'sub_categoria_dre.id', 'processo.id_sub_dre')
            ->leftJoinSub($subQuery, 'approved_processo_distinct', function ($join) {
                $join->on('approved_processo_distinct.id_processo', '=', 'processo.id')
                    ->on('approved_processo_distinct.id_processo_vencimento_valor', '=', 'processo_vencimento_valor.id');
            })
            ->leftJoin('pagamentos', function ($join) {
                $join
                    ->on('pagamentos.id_processo', '=', 'processo.id')
                    ->on('pagamentos.id_processo_vencimento_valor', '=', 'processo_vencimento_valor.id');
            })
            ->leftJoin('tipo_cobranca', 'tipo_cobranca.id', 'processo.tipo_cobranca')
            ->leftJoin('bancos', 'bancos.id', 'pagamentos.id_banco')
            ->leftJoin('formas_pagamento', 'formas_pagamento.id', 'pagamentos.forma_pagamento')
            ->leftJoin('contratos', 'contratos.id', 'processo.id_contrato')
            ->leftJoin('produtos', 'produtos.id', 'processo.id_produto')
            ->leftJoin('centro_custos', 'centro_custos.id', 'processo.id_centro_custos')
            ->leftJoin('rateio_setup as rateio', 'rateio.id', 'processo.id_rateio');
    }

    public function sortBy($column, $order)
    {
        $this->query->orderBy($column, $order);
        return $this;
    }

    public function isDisabled()
    {
        $this->query->where('processo.deletado', true);
        return $this;
    }

    public function isNotDisabled()
    {
        $this->query->where('processo.deletado','!=' ,true);
        return $this;
    }
    public function isPending()
    {
        $this->query->where('processo.pendencia', true);
        return $this;
    }

    public function isNotPending()
    {
        $this->query->where('processo.pendencia', false);
        return $this;
    }

    private function userUser()
    {
        $this->query->where('user_processo.id_empresa', auth()->user()->id_empresa);
        return $this;
    }

    private function getGroups()
    {
        $id_grupos = auth()->user()->id_grupos !== null ? json_decode(auth()->user()->id_grupos) : [0];
        $this->query->where(function ($query) use ($id_grupos) {
            foreach ($id_grupos as $value) {
                $query->orWhereJsonContains('workflow.id_grupos', $value);
            }
        });
        return $this;
    }

    public function isApproved()
    {
        $this->query->where('processo_vencimento_valor.aprovado', true);
        return $this;
    }

    public function needsMyApproval()
    {
        $this->query->where(function($query){
            $query->whereNull('approved_processo_distinct.id_grupo')
                ->orWhereNotIn(
                    'approved_processo_distinct.id_grupo',
                    json_decode(auth()->user()->id_grupos)
                );
        });
        return $this;
    }

    public function isNotApproved()
    {
        $this->query->where('processo_vencimento_valor.aprovado', false);
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

    public function billingDateRange($start, $end)
    {
        $this->query->whereBetween('processo_vencimento_valor.data_vencimento', [$start, $end]);
        return $this;
    }

    public function byTraceCode($traceCode)
    {
        $this->query->where('processo.trace_code', $traceCode);
        return $this;
    }

    public function list()
    {
        $this->getGroups();
        $this->userUser();
        return $this->query->get();
    }

    public function paginate($perPage = 10)
    {
        $this->getGroups();
        $this->userUser();
        return $this->query->paginate($perPage);
    }

    public function bySupplier($supplier)
    {
        $this->query->where('processo.id_fornecedor', $supplier);
        return $this;
    }

    public function showQuery()
    {
        $this->getGroups();
        $this->userUser();
        $this->query->dd();
    }

    public function count()
    {
        $this->getGroups();
        $this->userUser();
        return $this->query->count();
    }
}
