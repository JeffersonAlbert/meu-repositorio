<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class ContasReceber extends Model
{
    use HasFactory;

    protected $table = 'contas_receber';

    protected $fillable = [
        'id_cliente',
        'id_contrato',
        'id_produto',
        'id_servico',
        'id_usuario',
        'valor_total',
        'valor_vencimento',
        'vencimento',
        'valores',
        'observacao',
        'vencimentos',
        'competencia',
        'descricao',
        'rateio',
        'id_categoria',
        'id_centro_custo',
        'codigo_referencia',
        'rateios',
        'id_empresa',
        'trace_code',
        'id_filial',
        'sub_categoria_dre',
        'files_types_desc',
        'condicao',
        'tipo',
        'dados_venda',
        'venda_id',
        'data_emissao'
    ];

    public function getVendasOuOrcamento()
    {
        $query = $this
            ->select(
                'contas_receber.id as id',
                'contas_receber.trace_code as trace_code',
                'clientes.nome as cliente',
                'contratos.nome as contrato',
                'produtos.produto as produto',
                'servicos.servico as servico',
                'contas_receber.valor_vencimento as valor',
                'contas_receber.vencimento as vencimento',
                'recebimento.status as status',
                'rateio.nome as rateio',
                'centro_custos.nome as centro_custo',
                'filial.nome as filial_nome'
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
            ->where('contas_receber.id_empresa', auth()->user()->id_empresa)
            ->where('contas_receber.tipo', 'venda')
            ->orWhere('contas_receber.tipo', 'orcamento');


        if (isset($data['tipo']) && $data['tipo'] == 'aberto') {
            $query = $query->whereNull('recebimento.status');
        }

        if (isset($data['tipo']) && $data['tipo'] == 'pago') {
            $query = $query->where('recebimento.status', 'like', '%Pago%');
        }

        if (isset($request['cliente']) && $request['cliente'] !== null) {
            $query = $query->where('clientes.nome', 'like', "%{$request['cliente']}%");
        }

        if (isset($request['categoria']) && $request['categoria'] !== '0') {
            $query = $query->where('categoria.id', $request['categoria']);
        }

        if (isset($request['centro_custo']) && $request['centro_custo'] !== '0') {
            $query = $query->where('centro_custos.id', $request['centro_custo']);
        }

        if (isset($request['rateio']) && $request['rateio'] !== '0') {
            $query = $query->where('rateio.id', $request['rateio']);
        }

        if (isset($request['contrato']) && $request['contrato'] !== '0') {
            $query = $query->where('contratos.id', $request['contrato']);
        }

        if (isset($request['produto']) && $request['produto'] !== '0') {
            $query = $query->where('produtos.id', $request['produto']);
        }

        if ((isset($request['vencimentoInicial']) && $request['vencimentoInicial'] !== null) && (isset($request['vencimentoFinal']) && $request['vencimentoFinal'])) {
            $query = $query->whereBetween('contas_receber.vencimento', [$request['vencimentoInicial'], $request['vencimentoFinal']]);
        }

        if ((isset($request['contratoInicial']) && $request['contratoInicial'] !== null) && (isset($request['contratoFinal']) && $request['contratoFinal'] !== null)) {
            $query = $query
                ->whereBetween('contratos.vigencia_inicial', [$request['contratoInicial'], $request['contratoFinal']])
                ->whereBetween('contratos.vigencia_final', [$request['contratoInicial'], $request['contratoFinal']]);
        }

        if (isset($request['rastreio']) && $request['rastreio'] !== null) {
            $query = $query->where('trace_code', 'like', "%{$request['rastreio']}%");
        }

        $query = $query
            ->orderBy('contas_receber.vencimento')
            ->paginate(auth()->user()->linhas_grid);

        return $query;
    }

    public function pegarContasReceberPorEmpresa($data = null, $total = null)
    {
        $request = isset($data['request']) ? $data['request'] : null;

        $query = $this
            ->select(
                'contas_receber.id as id',
                'contas_receber.trace_code as trace_code',
                'clientes.nome as cliente',
                'contratos.nome as contrato',
                'produtos.produto as produto',
                'servicos.servico as servico',
                'contas_receber.valor_vencimento as valor',
                'contas_receber.vencimento as vencimento',
                'recebimento.status as status',
                'rateio.nome as rateio',
                'centro_custos.nome as centro_custo',
                'filial.nome as filial_nome'
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
            ->where('contas_receber.id_empresa', auth()->user()->id_empresa);

        if ($total) {
            $countPendente = clone $query;
            $pendente = $countPendente->whereNull('recebimento.status')->count();
            $countPago = clone $query;
            $pago = $countPago->where('recebimento.status', 'like', '%Pago%')->count();
        }

        if (isset($data['tipo']) && $data['tipo'] == 'aberto') {
            $query = $query->whereNull('recebimento.status');
        }

        if (isset($data['tipo']) && $data['tipo'] == 'pago') {
            $query = $query->where('recebimento.status', 'like', '%Pago%');
        }

        if (isset($request['cliente']) && $request['cliente'] !== null) {
            $query = $query->where('clientes.nome', 'like', "%{$request['cliente']}%");
        }

        if (isset($request['categoria']) && $request['categoria'] !== '0') {
            $query = $query->where('categoria.id', $request['categoria']);
        }

        if (isset($request['centro_custo']) && $request['centro_custo'] !== '0') {
            $query = $query->where('centro_custos.id', $request['centro_custo']);
        }

        if (isset($request['rateio']) && $request['rateio'] !== '0') {
            $query = $query->where('rateio.id', $request['rateio']);
        }

        if (isset($request['contrato']) && $request['contrato'] !== '0') {
            $query = $query->where('contratos.id', $request['contrato']);
        }

        if (isset($request['produto']) && $request['produto'] !== '0') {
            $query = $query->where('produtos.id', $request['produto']);
        }

        if ((isset($request['vencimentoInicial']) && $request['vencimentoInicial'] !== null) && (isset($request['vencimentoFinal']) && $request['vencimentoFinal'])) {
            $query = $query->whereBetween('contas_receber.vencimento', [$request['vencimentoInicial'], $request['vencimentoFinal']]);
        }

        if ((isset($request['contratoInicial']) && $request['contratoInicial'] !== null) && (isset($request['contratoFinal']) && $request['contratoFinal'] !== null)) {
            $query = $query
                ->whereBetween('contratos.vigencia_inicial', [$request['contratoInicial'], $request['contratoFinal']])
                ->whereBetween('contratos.vigencia_final', [$request['contratoInicial'], $request['contratoFinal']]);
        }

        if (isset($request['rastreio']) && $request['rastreio'] !== null) {
            $query = $query->where('trace_code', 'like', "%{$request['rastreio']}%");
        }

        $query = $query
            ->orderBy('contas_receber.vencimento')
            ->paginate(auth()->user()->linhas_grid);

        if ($total) {
            return [
                'query' => $query,
                'pago' => $pago,
                'pendente' => $pendente
            ];
        }

        return $query;
    }

    public function contasReceberDiario()
    {
        $query = $this
            ->select(
                'contas_receber.valor_vencimento as valor',
            )
            ->leftJoin('receber_vencimento_valor as rvv', 'rvv.id_contas_receber', 'contas_receber.id')
            ->where('contas_receber.id_empresa', auth()->user()->id_empresa)
            ->whereBetween('contas_receber.vencimento', [date('Y-m-d 00:00:00'), date('Y-m-d 23:59:59')])
            ->whereNull('rvv.status')
            ->sum('contas_receber.valor_vencimento');

        return $query;
    }

    public function contasReceberVencidos($periodo = null)
    {
        $periodo = is_null($periodo) ? [date('Y-m-1 00:00:00'), date('Y-m-d 23:59:59', strtotime('- 1 day'))] : $periodo;
        $query = $this
            ->select(
                'contas_receber.valor_vencimento as valor',
            )
            ->leftJoin('receber_vencimento_valor as rvv', 'rvv.id_contas_receber', 'contas_receber.id')
            ->where('contas_receber.id_empresa', auth()->user()->id_empresa)
            ->whereBetween('contas_receber.vencimento', $periodo)
            ->whereNull('rvv.status')
            ->sum('contas_receber.valor_vencimento');

        return $query;
    }

    public function contasReceberAVencer()
    {
        $query = $this
            ->select(
                'contas_receber.valor_vencimento as valor',
            )
            ->leftJoin('receber_vencimento_valor as rvv', 'rvv.id_contas_receber', 'contas_receber.id')
            ->where('contas_receber.id_empresa', auth()->user()->id_empresa)
            ->whereBetween('contas_receber.vencimento', [date('Y-m-d 00:00:00'), date('Y-m-t 23:59:59')])
            ->whereNull('rvv.status')
            ->sum('contas_receber.valor_vencimento');

        return $query;
    }

    public function contasReceberPago($arrayDate = null)
    {
        $arrayDate ?? $arrayDate = [date('Y-m-1 00:00:00'), date('Y-m-t 23:59:59')];
        $query = $this->select(
                'contas_receber.valor_vencimento as valor',
            )
            ->leftJoin('receber_vencimento_valor as rvv', 'rvv.id_contas_receber', 'contas_receber.id')
            ->where('contas_receber.id_empresa', auth()->user()->id_empresa)
            ->whereBetween('contas_receber.vencimento', $arrayDate)
            ->where('rvv.status', '=', 'Pago')
            ->sum('contas_receber.valor_vencimento');

        return $query;
    }

    public function contasReceberFluxoCaixa($periodo)
    {
        $periodo ?? $periodo = [date('Y-m-1 00:00:00'), date('Y-m-t 23:59:59')];
        return $this
            ->selectRaw(
                "SUM(contas_receber.valor_vencimento) as valor,
                DATE_FORMAT(contas_receber.vencimento, '%Y-%m-%d') as vencimento"
            )
            ->leftJoin('receber_vencimento_valor as rvv', 'rvv.id_contas_receber', 'contas_receber.id')
            ->where('contas_receber.id_empresa', auth()->user()->id_empresa)
            ->whereBetween('contas_receber.vencimento', $periodo)
            ->where('rvv.status', '=', 'Pago')
            ->groupBy('vencimento')
            ->get()
            ->pluck('valor', 'vencimento');
    }

    public function contasReceberMensal($date = null)
    {
        $now = Carbon::now();

        if (is_null($date)) {
            $month = $now->month;
        }

        $query = $this
            ->select(
                'contas_receber.valor_vencimento as valor',
            )
            ->where('contas_receber.id_empresa', auth()->user()->id_empresa)
            ->whereMonth('contas_receber.vencimento', $now->month)
            ->sum('contas_receber.valor_vencimento');

        return $query;
    }

    public function relatorioContasPagar()
    {
    }

    public function relatorioContasReceber($search)
    {
        $query = ContasReceber::select(
            'contas_receber.trace_code',
            'clientes.nome as nome_cliente',
            'filial.nome as filial_nome',
            'categorias_receber.categoria as categoria_nome',
            'contratos.nome as nome_contrato',
            DB::raw("date_format(contas_receber.vencimento, '%d/%m/%Y') as vencimento_formatado"),
            'contas_receber.valor_vencimento',
            DB::raw("date_format(recebimento.created_at, '%d/%m/%Y') as recebimento"),
            'contas_receber.codigo_referencia',
            'produtos.produto as produto_nome',
            'servicos.servico as servico_nome',
            'rateio_setup.nome as nome_rateio',
            'centro_custos.nome as centro_custos_nome',
            'recebimento.status as status',
            'contas_receber.observacao',
        )
            ->leftJoin('clientes', 'clientes.id', '=', 'contas_receber.id_cliente')
            ->leftJoin('contratos', 'contratos.id', '=', 'contas_receber.id_contrato')
            ->leftJoin('produtos', 'produtos.id', '=', 'contas_receber.id_produto')
            ->leftJoin('servicos', 'servicos.id', '=', 'contas_receber.id_servico')
            ->leftJoin('rateio_setup', 'rateio_setup.id', '=', 'contas_receber.rateios')
            ->leftJoin('receber_vencimento_valor as recebimento', 'recebimento.id_contas_receber', 'contas_receber.id')
            ->leftJoin('categorias_receber', 'categorias_receber.id', 'contas_receber.id_categoria')
            ->leftJoin('filial', 'filial.id', 'contas_receber.id_filial')
            ->leftJoin('centro_custos', 'centro_custos.id', 'contas_receber.id_centro_custo')
            ->where('contas_receber.id_empresa', auth()->user()->id_empresa);

        if ($search->get('rastreio') !== null) {
            $query = $query->where('contas_receber.trace_code', $search->get('rastreio'));
        }

        if ($search->get('cliente') !== null) {
            $query = $query->where('clientes.nome', 'like', "%{$search->get('cliente')}%");
        }

        if ($search->get('categoria') !== '0' and $search->get('categoria') !== null) {
            $query = $query->where('categorias_receber.id', $search->get('categoria'));
        }

        if ($search->get('centro_custo') !== '0' && $search->get('centro_custo') !== null) {
            $query = $query->where('centro_custos.id', $search->get('centro_custo'));
        }

        if ($search->get('rateio') !== '0' && $search->get('rateio') !== null) {
            $query = $query->where('rateio_setup.id', $search->get('rateio'));
        }

        if ($search->get('contrato') !== '0' && $search->get('contrato') !== null) {
            $query = $query->where('contratos.id', $search->get('contrato'));
        }

        if ($search->get('produto') !== '0' && $search->get('produto') !== null) {
            $query = $query->where('produtos.id', $search->get('produto'));
        }

        if ($search->get('filial') !== '0' && $search->get('filial') !== null) {
            $query = $query->where('filial.id', $search->get('filial'));
        }

        if ($search->get('vencimentoInicial') !== null && $search->get('vencimentoFinal') !== null) {
            $query = $query->whereBetween('contas_receber.vencimento', [$search->get('vencimentoInicial'), $search->get('vencimentoFinal')]);
        }

        if ($search->get('contratoInicial') !== null && $search->get('contratoFinal') !== null) {
            $query = $query
                ->whereBetween('contrato.vigencia_inicial', [$search->get('contratoInicial'), $search->get('contratoFinal')])
                ->whereBetween('contrato.vigencia_final', [$search->get('contratoInicial'), $search->get('contratoFinal')]);
        }

        return $query->paginate();
    }

    public function contasReceberAnual()
    {
        $now = Carbon::now();

        $query = $this
            ->select(
                'contas_receber.valor_vencimento as valor',
            )
            ->where('contas_receber.id_empresa', auth()->user()->id_empresa)
            ->whereYear('contas_receber.vencimento', $now->year)
            ->sum('contas_receber.valor_vencimento');

        return $query;
    }

    public function contasPagarMensal()
    {
        $now = Carbon::now();
        $query = Processo::select(
            'pvv.price',
        )
            ->leftJoin('processo_vencimento_valor as pvv', 'pvv.id_processo', 'processo.id')
            ->leftJoin('users', 'users.id', 'processo.id_user')
            ->where('processo.id_empresa', auth()->user()->id_empresa)
            ->whereBetween('pvv.data_vencimento', [$now->startOfMonth()->format('Y-m-d'), $now->endOfMonth()->format('Y-m-d')])
            ->sum('pvv.price');
        return $query;
    }

    public function contasPagarMensalVencidas($periodo = null)
    {
        $periodo = is_null($periodo) ? [date('Y-m-1'), date('Y-m-d')] : $periodo;
        $query = Processo::select(
            'pvv.price',
        )
            ->leftJoin('processo_vencimento_valor as pvv', 'pvv.id_processo', 'processo.id')
            ->leftJoin('users', 'users.id', 'processo.id_user')
            ->where('processo.id_empresa', auth()->user()->id_empresa)
            ->whereBetween('pvv.data_vencimento', $periodo)
            ->whereNull('pvv.pago')
            ->sum('pvv.price');
        return $query;
    }

    public function contasPagarMensalAVencer($periodo = null)
    {
        $now = Carbon::now();

        $periodo = isset($periodo) ? $periodo : [$now->format('Y-m-d'), $now->endOfMonth()->format('Y-m-d')];

        $query = Processo::select(
            'pvv.price',
        )
            ->leftJoin('processo_vencimento_valor as pvv', 'pvv.id_processo', 'processo.id')
            ->where('processo.id_empresa', auth()->user()->id_empresa)
            ->whereBetween('pvv.data_vencimento', $periodo)
            ->whereNull('pvv.pago')
            ->sum('pvv.price');
        return $query;
    }

    public function contasPagarAnual()
    {
        $now = Carbon::now();
        $query = Processo::select(
            'pvv.price',
        )
            ->leftJoin('processo_vencimento_valor as pvv', 'pvv.id_processo', 'processo.id')
            ->leftJoin('users', 'users.id', 'processo.id_user')
            ->where('users.id_empresa', auth()->user()->id_empresa)
            ->whereYear('pvv.data_vencimento', $now->year)
            ->sum('pvv.price');
        return $query;
    }

    public function contasPagasMes()
    {
        $now = Carbon::now();
        $query = Processo::select(
            'pvv.price',
        )
            ->leftJoin('processo_vencimento_valor as pvv', 'pvv.id_processo', 'processo.id')
            ->leftJoin('users', 'users.id', 'processo.id_user')
            ->where('users.id_empresa', auth()->user()->id_empresa)
            ->whereBetween('pvv.data_vencimento', [$now->startOfMonth()->format('Y-m-d'), $now->endOfMonth()->format('Y-m-d')])
            ->whereNotNull('pvv.pago')
            ->sum('pvv.price');
        return $query;
    }

    public function contasPagarDia()
    {
        $query = Processo::select(
            'pvv.price',
        )
            ->leftJoin('processo_vencimento_valor as pvv', 'pvv.id_processo', 'processo.id')
            ->leftJoin('users', 'users.id', 'processo.id_user')
            ->where('processo.id_empresa', auth()->user()->id_empresa)
            ->whereBetween('pvv.data_vencimento', [date('Y-m-d'), date('Y-m-d')])
            ->whereNull('pvv.pago')
            ->sum('pvv.price');
        return $query;
    }

    public function receberLote($data)
    {
        $query = ContasReceber::where('id', $data['id'])
            ->where('id_empresa', auth()->user()->id_empresa)
            ->update(
                [
                    'vencimentos' => $data['vencimentos'],
                    'valores' => $data['valores'],
                    'status' => 'Pago',
                ]
            );
    }
}
