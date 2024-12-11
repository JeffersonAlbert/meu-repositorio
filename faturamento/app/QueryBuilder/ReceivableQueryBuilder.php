<?php

namespace App\QueryBuilder;

use Illuminate\Support\Facades\DB;

class ReceivableQueryBuilder
{
    protected $query;
    protected $user;

    public function __construct()
    {
        $this->query = DB::table('contas_receber')
            ->select(
                'contas_receber.id as id',
                'contas_receber.trace_code as trace_code',
                'clientes.nome as cliente',
                'clientes.id as cliente_id',
                'contas_receber.id as codigo_referencia',
                'contratos.nome as contrato',
                'produtos.produto as produto',
                'servicos.servico as servico',
                'contas_receber.valor_vencimento as valor',
                'contas_receber.vencimento as vencimento',
                'recebimento.status as status',
                'rateio.nome as rateio',
                'centro_custos.nome as centro_custo',
                'contas_receber.id_centro_custo as id_centro_custo',
                'filial.nome as filial_nome',
                'sub_categoria_dre as dre_categoria',
                'sub_categoria_dre.descricao as descricao',
                'contas_receber.competencia as competencia',
                'tipo_cobranca.nome as tipo_cobranca_nome',
                'contas_receber.valor_total as valor_total',
                'contas_receber.observacao as observacao',
                'contas_receber.files_types_desc as files',
                'recebimento.juros',
                'recebimento.multa',
                'recebimento.desconto',
                'contas_receber.condicao as condicao',
                'contas_receber.data_emissao as data_emissao',
                'contas_receber.id_categoria as id_categoria',
                'contas_receber.observacao as observacao'
            )
            ->leftJoin('clientes', 'clientes.id', 'contas_receber.id_cliente')
            ->leftJoin('contratos', 'contratos.id', 'contas_receber.id_contrato')
            ->leftJoin('produtos', 'produtos.id', 'contas_receber.id_produto')
            ->leftJoin('servicos', 'servicos.id', 'contas_receber.id_servico')
            ->leftJoin('filial', 'filial.id', 'contas_receber.id_filial')
            ->leftJoin('receber_vencimento_valor as recebimento', 'recebimento.id_contas_receber', 'contas_receber.id')
            ->leftJoin('rateio_setup  as rateio', 'rateio.id', 'contas_receber.rateio')
            ->leftJoin('centro_custos', 'centro_custos.id', 'contas_receber.id_centro_custo')
            ->leftJoin('categorias_receber as categoria', 'categoria.id', 'contas_receber.id_categoria')
            ->leftJoin('sub_categoria_dre', 'sub_categoria_dre.id', 'contas_receber.sub_categoria_dre')
            ->leftJoin('tipo_cobranca', 'tipo_cobranca.id', 'contas_receber.id_categoria');
    }

    public function byId($id)
    {
        $this->query->where('contas_receber.id', $id);
        return $this;
    }

    public function byTraceCode($traceCode)
    {
        $this->query->where('contas_receber.trace_code', $traceCode);
        return $this;
    }

    public function byCompany()
    {
        $this->query->where('contas_receber.id_empresa', auth()->user()->id_empresa);
        return $this;
    }

    public function byBillingDateRange($startDate, $endDate)
    {
        $this->query->whereBetween('contas_receber.vencimento', [$startDate, $endDate]);
        return $this;
    }

    public function list()
    {
        $this->byCompany();
        return $this->query->get();
    }

    public function first()
    {
        $this->byCompany();
        return $this->query->first();
    }

    public function dd()
    {
        $this->byCompany();
        return $this->query->dd();
    }
}
