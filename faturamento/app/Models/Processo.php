<?php

namespace App\Models;

use App\Helpers\FormatUtils;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redis;

class Processo extends Model
{
    use HasFactory;

    protected $table = 'processo';

    protected $fillable = [
        'id_user',
        'id_fornecedor',
        'doc_name',
        'dt_parcelas',
        'valor',
        'id_workflow',
        'parcelas',
        'tipo_cobranca',
        'condicao',
        'emissao_nota',
        'numero_nota',
        'user_ultima_alteracao',
        'created_user',
        'logs',
        'approved_by',
        'pendencia',
        'trace_code',
        'delete',
        'obs_delete',
        'observacao',
        'id_rateio',
        'id_centro_custo',
        'id_filial',
        'id_empresa',
        'competencia',
        'id_sub_dre',
        'files_types_desc',
        'number_of_pages',
    ];

    protected $user;

    public function __construct()
    {
        $this->user = auth()->user();
    }

    public function getProcessosPaginated()
    {
        return $this->getPaginated(false);
    }

    public function getProcessoShow(string $id, string $data)
    {
        return Processo::select(
            'processo.id as id',
            'fornecedor.cpf_cnpj as f_doc',
            'fornecedor.forma_pagamento as fornecedor_forma_pagamento',
            'processo.created_at',
            'processo_vencimento_valor.id as pvv_id',
            'processo_vencimento_valor.data_vencimento as pvv_dtv',
            'processo_vencimento_valor.price as vparcela',
            'processo.valor',
            'user_processo.name as u_name',
            'processo.id_workflow as p_workflow',
            'fornecedor.nome as fornecedor_name',
            'fornecedor.id as f_id',
            'processo.numero_nota as num_nota',
            'processo.doc_name as doc_name',
            'processo.emissao_nota as p_emissao',
            'user_alteracao.name as u_last_modification',
            'workflow.nome as workflow_nome',
            'empresa.razao_social as e_name',
            'empresa.cpf_cnpj as e_doc',
            'filial.id as id_filial',
            'filial.razao_social as f_name',
            'filial.nome as nome_filial',
            'filial.cnpj as cnpj_filial',
            'processo.id_workflow as p_workflow',
            'processo.approved_by as approved_by',
            'workflow.nome as w_nome',
            'processo.id_user as p_id_user',
            'user_processo.id_grupos as u_grupo',
            'processo.updated_at as updated_at',
            'processo_pendencia.observacao as pendencia_obs',
            'processo_pendencia.created_at as pendencia_data',
            'processo.pendencia',
            'processo_vencimento_valor.aprovado as pvv_aprovado',
            'processo.trace_code as trace_code',
            'fornecedor.nome as fornecedor',
            'processo.dt_parcelas as parcelas',
            'processo.id_fornecedor as f_id',
            'processo.condicao as p_condicao',
            'processo.parcelas as qtde_parcelas',
            'tipo_cobranca.id as tc_id',
            'tipo_cobranca.nome as tc_nome',
            'processo.observacao as p_observacao',
            'processo_vencimento_valor.pago as pago',
            'empresa.id as e_id',
            'pagamentos.created_at as data_pagamento',
            'pagamentos.id as id_pagamento',
            'pagamentos.observacao as pagamento_obs',
            'pagamentos.forma_pagamento as id_forma_pagamento',
            'pagamentos.data_pagamento as a_data_pagamento',
            'pagamentos.valor_pago as valor_pago',
            'pagamentos.juros as juros',
            'pagamentos.multa as multas',
            'pagamentos.desconto as descontos',
            'formas_pagamento.nome as forma_pagamento_nome',
            'bancos.id as id_banco',
            'bancos.nome as banco_nome',
            'bancos.conta as banco_conta',
            'bancos.agencia as banco_agencia',
            'rateio_setup.nome as rateio_nome',
            'centro_custos.nome as centro_custo_nome',
            'sub_categoria_dre.id as sub_id',
            'sub_categoria_dre.descricao as sub_desc',
            'processo.competencia',
            'processo.files_types_desc',
            'processo.number_of_pages',
            'processo.id_empresa as id_empresa',
            GrupoOrderFluxo::raw(
                'GROUP_CONCAT(DISTINCT grupo_processos.id SEPARATOR ",") AS grupos_ids'
            )
        )
            ->leftJoin('processo_vencimento_valor', 'processo_vencimento_valor.id_processo', 'processo.id')
            ->leftJoin('fornecedor', 'fornecedor.id', 'processo.id_fornecedor')
            ->join('users as user_processo', 'user_processo.id', 'processo.id_user')
            ->join('users as user_alteracao', 'user_alteracao.id', 'processo.user_ultima_alteracao')
            ->leftJoin('empresa', 'empresa.id', 'user_processo.id_empresa')
            ->leftJoin('filial', 'filial.id', 'processo.id_filial')
            ->leftJoin('workflow', 'workflow.id', 'processo.id_workflow')
            ->leftJoin('grupo_order_fluxo', 'grupo_order_fluxo.id_fluxo', 'workflow.id')
            ->leftJoin('grupo_processos', 'grupo_order_fluxo.id_grupo', 'grupo_processos.id')
            ->leftJoin('processo_pendencia', 'processo_pendencia.id_processo', 'processo.id')
            ->leftJoin('tipo_cobranca', 'tipo_cobranca.id', 'processo.tipo_cobranca')
            ->leftJoin('rateio_setup', 'rateio_setup.id', 'processo.id_rateio')
            ->leftJoin('pagamentos', function ($join) {
                $join
                    ->on('pagamentos.id_processo', '=', 'processo.id')
                    ->on('pagamentos.id_processo_vencimento_valor', '=', 'processo_vencimento_valor.id');
            })
            ->leftJoin('formas_pagamento', 'formas_pagamento.id', 'pagamentos.forma_pagamento')
            ->leftJoin('bancos', 'bancos.id', 'pagamentos.id_banco')
            ->leftJoin('centro_custos', 'centro_custos.id', 'processo.id_centro_custo')
            ->leftJoin('sub_categoria_dre', 'sub_categoria_dre.id', 'processo.id_sub_dre')
            ->where(function ($query) {
                $query
                    ->where('processo_pendencia.updated_at', '=', DB::raw('(SELECT MAX(updated_at) FROM processo_pendencia WHERE id_processo = processo.id)'))
                    ->orWhereNull('processo_pendencia.id');
            })
            ->groupBy(
                'processo.id',
                'fornecedor.cpf_cnpj',
                'processo.created_at',
                'processo_vencimento_valor.id',
                'processo_vencimento_valor.data_vencimento',
                'processo_vencimento_valor.price',
                'processo.valor',
                'user_processo.name',
                'fornecedor.nome',
                'processo.numero_nota',
                'processo.doc_name',
                'processo.emissao_nota',
                'user_alteracao.name',
                'workflow.id',
                'workflow.nome',
                'pendencia_obs',
                'fornecedor',
                'parcelas',
                'tc_id',
                'tc_nome',
                'p_observacao',
                'processo_pendencia.created_at',
                'pagamentos.created_at',
                'id_pagamento',
                'pagamento_obs',
                'id_forma_pagamento',
                'forma_pagamento_nome',
                'id_banco',
                'banco_nome',
                'banco_agencia',
                'banco_conta'
            )
            ->where('processo.id', $id)
            ->where('processo_vencimento_valor.data_vencimento', $data)
            ->where('grupo_order_fluxo.ativo', true)
            ->first();
    }

    public function getPendentesPaginated()
    {
        return $this->getPaginated(true);
    }

    public function getProcessosAprovadosPaginated()
    {
        return $this->getProcessosAprovados(null);
    }

    public function getProcessosCompletosPaginated()
    {
        return $this->getProcessosAprovados(true);
    }

    public function getProcessosAprovados($pago)
    {
        $query = $this
            ->select(
                'processo.id as id',
                'fornecedor.cpf_cnpj as f_doc',
                'processo.valor as valor',
                'processo.created_at',
                'processo_vencimento_valor.id as pvv_id',
                'processo_vencimento_valor.data_vencimento as pvv_dtv',
                'processo_vencimento_valor.price as vparcela',
                'processo.valor',
                'user_processo.name as u_name',
                'fornecedor.nome as f_name',
                'processo.numero_nota as num_nota',
                'processo.doc_name as file',
                'processo.emissao_nota as p_emissao',
                'user_alteracao.name as u_last_modification',
                'processo.pendencia as pendencia',
                'processo.updated_at as updated_at',
                'processo.id_workflow as p_workflow_id',
                'processo_vencimento_valor.aprovado',
                'processo.trace_code as trace_code'
            )
            ->leftJoin('processo_vencimento_valor', 'processo_vencimento_valor.id_processo', 'processo.id')
            ->leftJoin('fornecedor', 'fornecedor.id', 'processo.id_fornecedor')
            ->join('users as user_processo', 'user_processo.id', 'processo.id_user')
            ->leftJoin('workflow', 'workflow.id', '=', 'processo.id_workflow')
            ->join('users as user_alteracao', 'user_alteracao.id', 'processo.user_ultima_alteracao')
            ->orderBy('processo_vencimento_valor.data_vencimento')
            ->where('processo_vencimento_valor.aprovado', true)
            ->where('processo_vencimento_valor.pago', $pago)
            ->where('processo.deletado', '!=', true);

        $id_grupos = auth()->user()->id_grupos !== null ? json_decode(auth()->user()->id_grupos) : [0];

        $query->where(function ($query) use ($id_grupos) {
            foreach ($id_grupos as $value) {
                $query->orWhereJsonContains('workflow.id_grupos', $value);
            }
        });

        if (!auth()->user()->master || auth()->user()->master === null) {
            $query = $query->where('user_processo.id_empresa', auth()->user()->id_empresa);
        }

        return $query->paginate($this->user->linhas_grid);
    }

    public function allProcessos($search = null)
    {
        $query = $this
            ->select(
                'processo.id as id',
                DB::raw('CASE WHEN processo.id_fornecedor = 0 THEN NULL ELSE fornecedor.cpf_cnpj END AS f_doc'),
                'processo.valor as valor',
                'processo.created_at',
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
                'pagamentos.forma_pagamento as forma_pagamento',
                'processo.id_rateio as id_rateio',
                'processo.id_centro_custo as id_centro_custo',
                'filial.nome as filial_nome',
                'contratos.nome as contrato',
                'produtos.produto as produto',
                'centro_custos.nome as centro_custo',
                'rateio.nome as rateio',
                'sub_categoria_dre.descricao as dre_categoria'
            )
            ->leftJoin('processo_vencimento_valor', 'processo_vencimento_valor.id_processo', 'processo.id')
            ->leftJoin('fornecedor', 'fornecedor.id', 'processo.id_fornecedor')
            ->leftJoin('filial', 'filial.id', 'processo.id_filial')
            ->join('users as user_processo', 'user_processo.id', 'processo.id_user')
            ->leftJoin('workflow', 'workflow.id', '=', 'processo.id_workflow')
            ->join('users as user_alteracao', 'user_alteracao.id', 'processo.user_ultima_alteracao')
            ->leftJoin('tipo_cobranca', 'tipo_cobranca.id', 'processo.tipo_cobranca')
            ->leftJoin('contratos', 'contratos.id', 'processo.id_contrato')
            ->leftJoin('pagamentos', function ($join) {
                $join
                    ->on('pagamentos.id_processo', '=', 'processo.id')
                    ->on('pagamentos.id_processo_vencimento_valor', '=', 'processo_vencimento_valor.id');
            })
            ->leftJoin('bancos', 'bancos.id', 'pagamentos.id_banco')
            ->leftJoin('produtos', 'produtos.id', 'processo.id_produto')
            ->leftJoin('centro_custos', 'centro_custos.id', 'processo.id_centro_custos')
            ->leftJoin('rateio_setup as rateio', 'rateio.id', 'processo.id_rateio')
            ->leftJoin('sub_categoria_dre', 'sub_categoria_dre.id', 'processo.id_sub_dre')
            ->orderBy('processo_vencimento_valor.data_vencimento')
            ->where('processo.deletado', '!=', true);

        if (isset($search['trace_code']) && $search['trace_code'] !== null) {
            $query = $query->where('processo.trace_code', $search['trace_code']);
        }

        if (isset($search['tipo']) && $search['tipo'] == 'andamento') {
            $query = $query->where('processo_vencimento_valor.aprovado', '!=', true);
        }

        if (isset($search['tipo']) && $search['tipo'] == 'pendentes') {
            $query = $query
                ->where('processo_vencimento_valor.aprovado', true)
                ->where('processo_vencimento_valor.pago', null);
        }

        if (isset($search['tipo']) && $search['tipo'] == 'pagas') {
            $query = $query->where('processo_vencimento_valor.pago', true);
        }

        if (!auth()->user()->master || auth()->user()->master === null) {
            $query = $query->where('user_processo.id_empresa', auth()->user()->id_empresa);
        }

        if (isset($search['fornecedor']) && $search['fornecedor'] !== null) {
            $query = $query->where('fornecedor.searchable', 'like', "%{$search['fornecedor']}%");
        }
        if (isset($search['tipo_cobranca']) && $search['tipo_cobranca'] !== '0') {
            $query = $query->where('tipo_cobranca.id', $search['tipo_cobranca']);
        }

        if (isset($search['banco']) && $search['banco'] !== '0' && $search['tipo'] == 'pagas') {
            $query = $query->where('bancos.id', $search['banco']);
        }

        if (isset($search['forma_pagamento']) && $search['forma_pagamento'] !== '0' && $search['tipo'] == 'pagas') {
            $query = $query->where('pagamentos.forma_pagamento', $search['forma_pagamento']);
        }

        if (isset($search['pagamentoInicial']) && $search['pagamentoInicial'] !== null && isset($search['pagamentoFinal']) && $search['pagamentoFinal'] !== null) {
            $query = $query->whereBetween('pagamentos.updated_at', [$search['pagamentoInicial'] . ' 00:00:00', $search['pagamentoFinal'] . ' 23:59:59']);
        }

        if (isset($search['rateio']) && $search['rateio'] !== '0') {
            $query = $query->where('id_rateio', $search['rateio']);
        }

        if (isset($search['centro_custo']) && $search['centro_custo'] !== '0') {
            $query = $query->where('id_centro_custo', $search['centro_custo']);
        }

        if (isset($search) && $search == null) {
            $andamentoCount = clone $query;
            $andamentoCount->where('processo_vencimento_valor.aprovado', '!=', true);

            $pendentesCount = clone $query;
            $pendentesCount
                ->where('processo_vencimento_valor.aprovado', true)
                ->where('processo_vencimento_valor.pago', null);

            $pagosCount = clone $query;
            $pagosCount->where('processo_vencimento_valor.pago', true);
        }

        if (isset($search['vencimentoInicial']) && $search['vencimentoInicial'] == null && isset($search['vencimentoFinal']) && $search['vencimentoFinal'] == null) {
            $query->whereBetween('processo_vencimento_valor.data_vencimento', [date('Y-m-d', strtotime('-60 days')), date('Y-m-d')]);
        }

        if (isset($search['vencimentoFinal']) && $search['vencimentoFinal'] !== null && $search['vencimentoInicial'] !== null && isset($search['vencimentoInicial'])) {
            $query->whereBetween('processo_vencimento_valor.data_vencimento', [$search['vencimentoInicial'], $search['vencimentoFinal']]);
        }

        return [
            'result' => $query->paginate($this->user->linhas_grid)->appends($search),
            'qtdeAndamento' => isset($andamentoCount) ? $andamentoCount->count() : 0,
            'qtdePendentes' => isset($pendentesCount) ? $pendentesCount->count() : 0,
            'qtdePagos' => isset($pagosCount) ? $pagosCount->count() : 0
        ];
    }

    public function getPaginated($pendencia)
    {
        $query = $this
            ->select(
                'processo.id as id',
                DB::raw('CASE WHEN processo.id_fornecedor = 0 THEN NULL ELSE fornecedor.cpf_cnpj END AS f_doc'),
                'processo.valor as valor',
                'processo.created_at',
                'processo_vencimento_valor.id as pvv_id',
                'processo_vencimento_valor.data_vencimento as pvv_dtv',
                'processo_vencimento_valor.price as vparcela',
                'processo.valor',
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
                'descricao as sub_categoria_dre',
            )
            ->leftJoin('processo_vencimento_valor', 'processo_vencimento_valor.id_processo', 'processo.id')
            ->leftJoin('fornecedor', 'fornecedor.id', 'processo.id_fornecedor')
            ->join('users as user_processo', 'user_processo.id', 'processo.id_user')
            ->leftJoin('workflow', 'workflow.id', '=', 'processo.id_workflow')
            ->join('users as user_alteracao', 'user_alteracao.id', 'processo.user_ultima_alteracao')
            ->leftJoin('filial', 'filial.id', 'processo.id_filial')
            ->leftJoin('sub_categoria_dre', 'sub_categoria_dre.id', 'processo.id_sub_dre')
            ->orderBy('processo_vencimento_valor.data_vencimento')
            ->where('processo_vencimento_valor.aprovado', false)
            ->where('pendencia', $pendencia)
            ->where('deletado', '!=', true);

        $id_grupos = auth()->user()->id_grupos !== null ? json_decode(auth()->user()->id_grupos) : [0];

        $query->where(function ($query) use ($id_grupos) {
            foreach ($id_grupos as $value) {
                $query->orWhereJsonContains('workflow.id_grupos', $value);
            }
        });

        if (!auth()->user()->master || auth()->user()->master === null) {
            $query = $query->where('user_processo.id_empresa', auth()->user()->id_empresa);
        }

        return $query->paginate(auth()->user()->linhas_grid);
    }

    public function getDadosVencimentoPago($id, $qtdMeses)
    {
        $startDate = date('Y-m-01', strtotime("-{$qtdMeses} months"));
        $endDate = date('Y-m-t');
        $idFornecedor = Processo::select('id_fornecedor')->find($id);
        $getValoresFornecedorByData = Processo::leftJoin('processo_vencimento_valor', 'processo_vencimento_valor.id_processo', 'processo.id')
            ->select(
                DB::raw('MONTH(processo_vencimento_valor.data_vencimento) as mes'),
                DB::raw('YEAR(processo_vencimento_valor.data_vencimento) as ano'),
                DB::raw('SUM(processo_vencimento_valor.price) as total_price')
            )
            ->where('id_fornecedor', $idFornecedor->id_fornecedor)
            ->whereBetween('processo_vencimento_valor.data_vencimento', [$startDate, $endDate])
            ->where('processo_vencimento_valor.pago', true)
            ->groupBy('mes', 'ano')
            ->orderBy('ano')
            ->get();

        if ($getValoresFornecedorByData->count() == 0) {
            return json_encode(['nada ainda' => 0]);
        }

        foreach ($getValoresFornecedorByData as $result) {
            $data[FormatUtils::mesPorExtenso("{$result->ano}-{$result->mes}-01")] = $result->total_price;
        }

        return json_encode($data);
    }

    public function getDadosVencimentoAberto($id, $qtdMeses)
    {
        $startDate = date('Y-m-01', strtotime("-{$qtdMeses} months"));
        $endDate = date('Y-m-t');
        $idFornecedor = Processo::select('id_fornecedor')->find($id);
        $getValoresFornecedorByData = Processo::leftJoin('processo_vencimento_valor', 'processo_vencimento_valor.id_processo', 'processo.id')
            ->select(
                DB::raw('MONTH(processo_vencimento_valor.data_vencimento) as mes'),
                DB::raw('YEAR(processo_vencimento_valor.data_vencimento) as ano'),
                DB::raw('SUM(processo_vencimento_valor.price) as total_price')
            )
            ->where('id_fornecedor', $idFornecedor->id_fornecedor)
            ->whereBetween('processo_vencimento_valor.data_vencimento', [$startDate, $endDate])
            ->where('processo_vencimento_valor.pago', null)
            ->where('deletado', '!=', true)
            ->groupBy('mes', 'ano')
            ->orderBy('mes', 'DESC')
            ->orderBy('ano')
            ->get();

        foreach ($getValoresFornecedorByData as $result) {
            $data[FormatUtils::mesPorExtenso("{$result->ano}-{$result->mes}-01")] = $result->total_price;
        }
        $data = isset($data) ? $data : array(FormatUtils::mesPorExtenso($startDate) => 0);
        return json_encode($data);
    }

    public function getProcessoByString(array $search, $pendencia = false)
    {
        $pendencia = isset($search['pendencia']) && $search['pendencia'] == true ? true : false;
        $aprovado = (isset($search['financeiro']) && $search['financeiro'] == true) ||
            (isset($search['finalizado']) && $search['finalizado'] == true)
            ? true
            : false;
        $finalizado = isset($search['finalizado']) && $search['finalizado'] == true ? true : null;
        $query = $this
            ->select(
                'processo.id as id',
                'fornecedor.cpf_cnpj as f_doc',
                'processo.valor as valor',
                'processo.created_at',
                'processo_vencimento_valor.id as pvv_id',
                'processo_vencimento_valor.data_vencimento as pvv_dtv',
                'processo_vencimento_valor.price as vparcela',
                'processo.valor',
                'user_processo.name as u_name',
                'fornecedor.nome as f_name',
                'processo.numero_nota as num_nota',
                'processo.doc_name as file',
                'processo.emissao_nota as p_emissao',
                'user_alteracao.name as u_last_modification',
                'processo.pendencia as pendencia',
                'processo.updated_at as updated_at',
                'processo.id_workflow as p_workflow_id',
                'processo.trace_code as trace_code',
                'sub_categoria_dre.descricao as sub_categoria_dre',
            )
            ->leftJoin('processo_vencimento_valor', 'processo_vencimento_valor.id_processo', 'processo.id')
            ->leftJoin('fornecedor', 'fornecedor.id', 'processo.id_fornecedor')
            ->leftJoin('users as user_processo', 'user_processo.id', 'processo.id_user')
            ->leftJoin('workflow', 'workflow.id', '=', 'processo.id_workflow')
            ->leftJoin('users as user_alteracao', 'user_alteracao.id', 'processo.user_ultima_alteracao')
            ->leftJoin('sub_categoria_dre', 'processo.id_sub_dre', 'sub_categoria_dre.id')
            ->leftJoin('pagamentos', 'pagamentos.id', 'processo.id')
            ->orderBy('processo_vencimento_valor.data_vencimento')
            ->where('processo_vencimento_valor.aprovado', $aprovado)
            ->where('deletado', '!=', true);

        if ($aprovado == false) {
            $query = $query->where('pendencia', $pendencia);
        }

        if ($aprovado == true || $finalizado == true) {
            $query = $query->where('processo_vencimento_valor.pago', $finalizado);
        }

        if ((isset($search['vencimentoInicial']) && $search['vencimentoInicial'] !== null) && (isset($search['vencimentoFinal']) && $search['vencimentoFinal'] !== null)) {
            $query = $query->whereBetween('processo_vencimento_valor.data_vencimento', [$search['vencimentoInicial'], $search['vencimentoFinal']]);
        }

        if ((isset($search['insercaoInicial']) && $search['insercaoInicial'] !== null) && (isset($search['insercaoFinal']) && $search['insercaoFinal'] !== null)) {
            $query = $query->whereBetween('processo.created_at', ["{$search['insercaoInicial']} 00:00:00", "{$search['insercaoFinal']} 23:59:59"]);
        }

        if ((isset($search['pagamentoInicial']) && $search['pagamentoInicial'] !== null) && (isset($search['pagamentoFinal']) && $search['pagamentoFinal'] !== null)) {
            $query = $query->whereBetween('pagamentos.updated_at', ["{$search['pagamentoInicial']} 00:00:00", "{$search['pagamentoFinal']} 23:59:59"]);
        }

        if (isset($search['usuario']) && $search['usuario'] !== null) {
            $query = $query
                ->where('user_processo.name', 'like', "%{$search['usuario']}%")
                ->orWhere('user_processo.email', 'like', "%{$search['usuario']}%");
        }

        if (isset($search['fornecedor']) && $search['fornecedor'] !== null) {
            $query = $query
                ->where('fornecedor.nome', 'like', "%{$search['fornecedor']}%")
                ->orWhere('fornecedor.cpf_cnpj', 'like', "%{$search['fornecedor']}%");
        }

        if (isset($search['valorTotal']) && $search['valorTotal'] !== null) {
            $query = $query = $query->where('processo.valor', $search['valorTotal']);
        }

        if (isset($search['valorParcela']) && $search['valorParcela'] !== null) {
            $query = $query->where('processo_vencimento_valor.price', $search['valorParcela']);
        }

        if (isset($search['trace_code']) && $search['trace_code'] !== null) {
            $query = $query->where('processo.trace_code', $search['trace_code']);
        }

        if (isset($search['centro_custo']) && $search['centro_custo'] !== '0') {
            $query = $query->where('processo.id_centro_custo', $search['centro_custo']);
        }

        if (isset($search['rateio']) && $search['rateio'] !== '0') {
            $query = $query->where('processo.id_rateio', $search['rateio']);
        }

        if (isset($search['filial']) && $search['filial'] !== '0') {
            $query = $query->where('processo.id_filial', $search['filial']);
        }

        $id_grupos = auth()->user()->id_grupos !== null ? json_decode(auth()->user()->id_grupos) : [0];

        $query = $query->where(function ($query) use ($id_grupos) {
            foreach ($id_grupos as $value) {
                $query->orWhereJsonContains('workflow.id_grupos', $value);
            }
        });

        if (!auth()->user()->master || auth()->user()->master === null) {
            $query = $query->where('processo.id_empresa', auth()->user()->id_empresa);
        }

        return $query->paginate(10);
    }

    /*
     * o segundo argumento precisa do array com os dados
     * [
     *     'id_grupos' => json id_grupos,
     *     'id_empresa' => id_empresa
     * ]
     */
    public static function getProcessoInativos($arrayDias, $array)
    {
        $hoje = Carbon::today();
        $dataLimite = $hoje->copy()->subDays($arrayDias['diasInativo']);
        $dataFinal = $hoje->copy()->addDays($arrayDias['diasMaximoVencimento']);

        $query = Processo::select(
            'processo.id as id',
            'fornecedor.cpf_cnpj as f_doc',
            'processo.valor as valor',
            'processo.created_at',
            'processo_vencimento_valor.id as pvv_id',
            'processo_vencimento_valor.data_vencimento as pvv_dtv',
            'processo_vencimento_valor.price as vparcela',
            'processo_vencimento_valor.updated_at as updated_at_pvv',
            'processo.valor',
            'user_processo.name as u_name',
            'fornecedor.nome as f_name',
            'processo.numero_nota as num_nota',
            'processo.doc_name as file',
            'processo.emissao_nota as p_emissao',
            'user_alteracao.name as u_last_modification',
            'processo.pendencia as pendencia',
            'processo.updated_at as updated_at',
            'processo.id_workflow as p_workflow_id',
            'processo.trace_code as trace_code',
            'workflow.id_grupos as w_grupos',
            DB::raw("GROUP_CONCAT(users.email SEPARATOR '; ') AS emails"),
            DB::raw("GROUP_CONCAT(users.id SEPARATOR ', ') AS id_user")
        )
            ->leftJoin('processo_vencimento_valor', 'processo_vencimento_valor.id_processo', 'processo.id')
            ->join('fornecedor', 'fornecedor.id', 'processo.id_fornecedor')
            ->join('users as user_processo', 'user_processo.id', 'processo.id_user')
            ->leftJoin('workflow', 'workflow.id', '=', 'processo.id_workflow')
            ->join('users as user_alteracao', 'user_alteracao.id', 'processo.user_ultima_alteracao')
            ->leftJoin('users', function ($join) {
                $join->on(DB::raw('JSON_CONTAINS(JSON_UNQUOTE(workflow.id_grupos), users.id_grupos)'), '=', DB::raw(1));
            })
            ->orderBy('processo_vencimento_valor.data_vencimento')
            ->where('processo_vencimento_valor.aprovado', false)
            ->where('deletado', '!=', true);

        $id_grupos = explode(',', $array['id_grupos']);

        $query->where(function ($query) use ($id_grupos) {
            foreach ($id_grupos as $value) {
                $query->orWhereJsonContains('workflow.id_grupos', "{$value}");
            }
        });

        $query = $query
            ->where('user_processo.id_empresa', $array['id_empresa'])
            ->where('user_processo.enabled', true)
            ->whereDate('processo_vencimento_valor.updated_at', '<=', $dataLimite)
            ->whereDate('processo_vencimento_valor.data_vencimento', '<=', $dataFinal);
        $query->groupBy(
            'processo.id',
            'f_doc',
            'valor',
            'created_at',
            'pvv_id',
            'pvv_dtv',
            'vparcela',
            'u_name',
            'f_name',
            'num_nota',
            'file',
            'p_emissao',
            'u_last_modification',
            'pendencia',
            'updated_at',
            'p_workflow_id',
            'trace_code',
            'w_grupos'
        );

        return $query->get();
    }

    public function getProximoVencimento(int $diasVencimento, int $id_empresa)
    {
        $hoje = Carbon::today();
        $dataFinal = $hoje->copy()->addDays($diasVencimento);

        $query = Processo::select(
            'processo.id as id',
            'fornecedor.cpf_cnpj as f_doc',
            'processo.valor as valor',
            'processo.created_at',
            'processo_vencimento_valor.id as pvv_id',
            'processo_vencimento_valor.data_vencimento as pvv_dtv',
            'processo_vencimento_valor.price as vparcela',
            'processo_vencimento_valor.updated_at as updated_at_pvv',
            'processo.valor',
            'user_processo.name as u_name',
            'fornecedor.nome as f_name',
            'processo.numero_nota as num_nota',
            'processo.doc_name as file',
            'processo.emissao_nota as p_emissao',
            'user_alteracao.name as u_last_modification',
            'processo.pendencia as pendencia',
            'processo.updated_at as updated_at',
            'processo.id_workflow as p_workflow_id',
            'processo.trace_code as trace_code',
            'workflow.id_grupos as w_grupos',
        )
            ->leftJoin('processo_vencimento_valor', 'processo_vencimento_valor.id_processo', 'processo.id')
            ->join('fornecedor', 'fornecedor.id', 'processo.id_fornecedor')
            ->join('users as user_processo', 'user_processo.id', 'processo.id_user')
            ->leftJoin('workflow', 'workflow.id', '=', 'processo.id_workflow')
            ->join('users as user_alteracao', 'user_alteracao.id', 'processo.user_ultima_alteracao')
            ->orderBy('processo_vencimento_valor.data_vencimento')
            ->where('processo_vencimento_valor.aprovado', false)
            ->where('deletado', '!=', true);

        $query = $query
            ->where('user_processo.id_empresa', $id_empresa)
            ->whereBetween('processo_vencimento_valor.data_vencimento', [$hoje, $dataFinal]);
        $query->groupBy(
            'processo.id',
            'f_doc',
            'valor',
            'created_at',
            'pvv_id',
            'pvv_dtv',
            'vparcela',
            'u_name',
            'f_name',
            'num_nota',
            'file',
            'p_emissao',
            'u_last_modification',
            'pendencia',
            'updated_at',
            'p_workflow_id',
            'trace_code',
            'w_grupos'
        );

        return $query->get();
    }

    public function contasPagarFluxoCaixa($periodo)
    {
        $periodo ?? $periodo = [date('Y-m-01'), date('Y-m-t')];
        return Processo::selectRaw('
            DATE_FORMAT(processo_vencimento_valor.data_vencimento, "%Y-%m-%d") as vencimento,
            SUM(processo_vencimento_valor.price) as valor'
        )
            ->leftJoin('processo_vencimento_valor', 'processo_vencimento_valor.id_processo', 'processo.id')
            ->whereBetween('processo_vencimento_valor.data_vencimento', $periodo)
            ->where('processo_vencimento_valor.pago', true)
            ->where('processo.deletado', '!=', true)
            ->where('processo.id_empresa', auth()->user()->id_empresa)
            ->groupBy('vencimento')
            ->get()
            ->pluck('valor', 'vencimento');
    }

    public function listAllProcess()
    {
        return $allProcess =  Processo::select(
            'processo.id as id',
            DB::raw('CASE WHEN processo.id_fornecedor = 0 THEN NULL ELSE fornecedor.cpf_cnpj END AS f_doc'),
            'processo.valor as valor',
            'processo.created_at',
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
            'pagamentos.forma_pagamento as forma_pagamento',
            'processo.id_rateio as id_rateio',
            'processo.id_centro_custo as id_centro_custo',
            'filial.nome as filial_nome',
            'contratos.nome as contrato',
            'produtos.produto as produto',
            'centro_custos.nome as centro_custo',
            'rateio.nome as rateio',
            'sub_categoria_dre.descricao as dre_categoria'
        )
            ->leftJoin('processo_vencimento_valor', 'processo_vencimento_valor.id_processo', 'processo.id')
            ->leftJoin('fornecedor', 'fornecedor.id', 'processo.id_fornecedor')
            ->leftJoin('filial', 'filial.id', 'processo.id_filial')
            ->join('users as user_processo', 'user_processo.id', 'processo.id_user')
            ->leftJoin('workflow', 'workflow.id', '=', 'processo.id_workflow')
            ->join('users as user_alteracao', 'user_alteracao.id', 'processo.user_ultima_alteracao')
            ->leftJoin('tipo_cobranca', 'tipo_cobranca.id', 'processo.tipo_cobranca')
            ->leftJoin('contratos', 'contratos.id', 'processo.id_contrato')
            ->leftJoin('pagamentos', function ($join) {
                $join
                    ->on('pagamentos.id_processo', '=', 'processo.id')
                    ->on('pagamentos.id_processo_vencimento_valor', '=', 'processo_vencimento_valor.id');
            })
            ->leftJoin('bancos', 'bancos.id', 'pagamentos.id_banco')
            ->leftJoin('produtos', 'produtos.id', 'processo.id_produto')
            ->leftJoin('centro_custos', 'centro_custos.id', 'processo.id_centro_custos')
            ->leftJoin('rateio_setup as rateio', 'rateio.id', 'processo.id_rateio')
            ->leftJoin('sub_categoria_dre', 'sub_categoria_dre.id', 'processo.id_sub_dre')
            ->orderBy('processo_vencimento_valor.data_vencimento')
            ->where('processo.deletado', '!=', true);
    }
}
