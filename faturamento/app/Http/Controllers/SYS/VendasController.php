<?php

namespace App\Http\Controllers\SYS;

use App\Helpers\FormatUtils;
use App\Http\Controllers\Controller;
use App\Models\Bancos;
use App\Models\CentroCusto;
use App\Models\Clientes;
use App\Models\ContasReceber;
use App\Models\FormasPagamento;
use App\Models\Produtos;
use App\Models\Setup;
use App\Models\SubCategoriaDRE;
use App\Models\Vendas;
use Barryvdh\DomPDF\PDF;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class VendasController extends Controller
{
    private $dre;

    public function __construct()
    {
        $this->middleware('auth');
        $this->dre = new SubCategoriaDRE();
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $contasReceber = new ContasReceber();

        $vendas = Vendas::select('clientes.nome', 'vendas.*', 'users.name', 'users.last_name')
            ->leftJoin('users', 'users.id', '=', 'vendas.user_id')
            ->leftJoin('clientes', 'clientes.id', '=', 'vendas.cliente_id')
            ->where('vendas.id_empresa', auth()->user()->id_empresa)
            ->paginate(auth()->user()->linhas_grid);

        return view('vendas.grid', compact('vendas'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $clientes = Clientes::where('id_empresa', auth()->user()->id_empresa)->get();

        $dreReceitas = $this->dre->dreReceita();
        $centroCustos = CentroCusto::where('id_empresa', auth()->user()->id_empresa)->get();
        $produtos = Produtos::where('id_empresa', auth()->user()->id_empresa)->get();
        $formasPagamento = FormasPagamento::all();
        $bancos = Bancos::where('id_empresa', auth()->user()->id_empresa)->get();
        session()->forget('cliente');
        return view('vendas.form', compact(
            'clientes',
            'dreReceitas',
            'centroCustos',
            'produtos',
            'formasPagamento',
            'bancos'
        ));

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $messages = [
            'clienteId.required' => 'O cliente é obrigatório',
            'sub_categoria_dre.required' => 'A sub categoria DRE é obrigatória',
            'produtoId.required' => 'O produto é obrigatório',
            'quantidade.required' => 'A quantidade é obrigatória',
            'valor_unitario.required' => 'O valor unitário é obrigatório',
            'valor_total.required' => 'O valor total é obrigatório',
            'desconto.required' => 'O desconto é obrigatório',
            'frete.required' => 'O frete é obrigatório',
            'forma-pagamento.required' => 'A forma de pagamento é obrigatória',
            'contaRecebimento.required' => 'A conta de recebimento é obrigatória',
            'condicao_pagamento.required' => 'A condição de pagamento é obrigatória',
            'data_vencimento.required' => 'A data de vencimento é obrigatória',
            'valor_receber.required' => 'O valor a receber é obrigatório',
            'representacao_percent.required' => 'A representação percentual é obrigatória',
            'quantidade.numeric' => 'A quantidade não pode ser vazia',
        ];

        $rules = [
            'clienteId' => 'required',
            'sub_categoria_dre' => 'required',
            'produtoId' => 'required',
            'quantidade' => 'required',
            'valor_unitario' => 'required',
            'valor_total' => 'required',
            'forma-pagamento' => 'required',
            'contaRecebimento' => 'required',
            'condicao_pagamento' => 'required',
            'data_vencimento' => 'required',
            'valor_receber' => 'required',
            'representacao_percent' => 'required',
        ];

        $validate = Validator::make($request->all(), $rules, $messages);

        if ($validate->fails()) {
            return response()->json(['errors' => $validate->errors()], 422);
        }

        $validaPorcentagemTotal = array_sum($request->get('representacao_percent')) == 100 ? true : false;

        if(!$validaPorcentagemTotal){
            return response()->json(['errors' => ['representacao_percent' => ['A representação percentual deve ser 100%']]], 422);
        }

        $contasReceber = new ContasReceber();

        $valoresVencimento = $request->get('valor_receber');

        $descricoes = $request->get('descricao');

        $vendas = Vendas::create([
            'id_empresa' => auth()->user()->id_empresa,
            'cliente_id' => $request->get('clienteId'),
            'user_id' => auth()->user()->id,
            'valor_total' => FormatUtils::formatMoneyDb($request->get('totalizador_valor')),
            'dados_venda' => json_encode($request->except('_token', '_method'))
        ]);

        foreach($request->get('data_vencimento') as $index => $dataVencimento){
            $contasReceber->create([
                'id_empresa' => auth()->user()->id_empresa,
                'id_usuario' => auth()->user()->id,
                'venda_id' => $vendas->id,
                'id_cliente' => $request->get('clienteId'),
                'sub_categoria_dre' => $request->get('sub_categoria_dre'),
                'valor_total' => FormatUtils::formatMoneyDb($request->get($valoresVencimento[$index])),
                'vencimento' => $dataVencimento,
                'valor_vencimento' => FormatUtils::formatMoneyDb($valoresVencimento[$index]),
                'tipo' => $request->has('orcamento') ? 'orcamento' : 'venda',
                'dados_venda' => json_encode($request->except('_token', '_method')),
                'trace_code' => FormatUtils::traceCode(),
                'competencia' => $request->get('data'),
                'descricao' => $descricoes[$index] ?? '',
                'id_categoria' => $request->get('categoria') ?? 0,
                'codigo_referencia' => $request->get('codigo_referencia') ?? '',
                'condicao' => $request->get('condicao_pagamento'),
            ]);
        }

        return response()->json([
            'success' => [
                'message' => 'Venda cadastrada com sucesso',
                'redirect' => $request->has('orcamento') ? route('vendas.show') : route('vendas.index'),
            ]
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {

        $venda = Vendas::select('vendas.*', 'users.name')
            ->leftJoin('users', 'users.id', '=', 'vendas.user_id')
            ->find($id);
        $dadosVenda = json_decode($venda->dados_venda);
        $setup = Setup::where('id_empresa', auth()->user()->id_empresa)->first();
        return view('vendas.show', compact('id', 'setup', 'venda', 'dadosVenda'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {

        $clientes = Clientes::where('id_empresa', auth()->user()->id_empresa)->get();
        $dreReceitas = $this->dre->dreReceita();
        $centroCustos = CentroCusto::where('id_empresa', auth()->user()->id_empresa)->get();
        $produtos = Produtos::where('id_empresa', auth()->user()->id_empresa)->get();
        $formasPagamento = FormasPagamento::all();
        $bancos = Bancos::where('id_empresa', auth()->user()->id_empresa)->get();
        $venda = Vendas::find($id);

        return view('vendas.form', compact(
            'clientes',
            'dreReceitas',
            'centroCustos',
            'produtos',
            'formasPagamento',
            'bancos',
            'venda'
        ));

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $messages = [
            'clienteId.required' => 'O cliente é obrigatório',
            'sub_categoria_dre.required' => 'A sub categoria DRE é obrigatória',
            'produtoId.required' => 'O produto é obrigatório',
            'quantidade.required' => 'A quantidade é obrigatória',
            'valor_unitario.required' => 'O valor unitário é obrigatório',
            'valor_total.required' => 'O valor total é obrigatório',
            'desconto.required' => 'O desconto é obrigatório',
            'frete.required' => 'O frete é obrigatório',
            'forma-pagamento.required' => 'A forma de pagamento é obrigatória',
            'contaRecebimento.required' => 'A conta de recebimento é obrigatória',
            'condicao_pagamento.required' => 'A condição de pagamento é obrigatória',
            'data_vencimento.required' => 'A data de vencimento é obrigatória',
            'valor_receber.required' => 'O valor a receber é obrigatório',
            'representacao_percent.required' => 'A representação percentual é obrigatória',
            'quantidade.numeric' => 'A quantidade não pode ser vazia',
        ];

        $rules = [
            'clienteId' => 'required',
            'sub_categoria_dre' => 'required',
            'produtoId' => 'required',
            'quantidade' => 'required',
            'valor_unitario' => 'required',
            'valor_total' => 'required',
            'forma-pagamento' => 'required',
            'contaRecebimento' => 'required',
            'condicao_pagamento' => 'required',
            'data_vencimento' => 'required',
            'valor_receber' => 'required',
            'representacao_percent' => 'required',
        ];

        $validate = Validator::make($request->all(), $rules, $messages);

        if ($validate->fails()) {
            return response()->json(['errors' => $validate->errors()], 422);
        }

        $validaPorcentagemTotal = array_sum($request->get('representacao_percent')) == 100 ? true : false;

        if(!$validaPorcentagemTotal){
            return response()->json(['errors' => ['representacao_percent' => ['A representação percentual deve ser 100%']]], 422);
        }

        $contasReceber = new ContasReceber();

        $valoresVencimento = $request->get('valor_receber');
        $descricoes = $request->get('descricao');
        $dataVencimento = $request->get('data_vencimento');

        $contasReceber = new ContasReceber();

        $vendas = Vendas::where('id', $id)->update([
            'id_empresa' => auth()->user()->id_empresa,
            'cliente_id' => $request->get('clienteId'),
            'user_id' => auth()->user()->id,
            'valor_total' => FormatUtils::formatMoneyDb($request->get('totalizador_valor')),
            'dados_venda' => json_encode($request->except('_token', '_method'))
        ]);

        $vendasContaReceber = $contasReceber->where('venda_id', $id)->get();

        foreach($vendasContaReceber as $index => $vendaContaReceber){
            $dadosVenda = [
                'id_empresa' => auth()->user()->id_empresa,
                'id_usuario' => auth()->user()->id,
                'id_cliente' => $request->get('clienteId'),
                'sub_categoria_dre' => $request->get('sub_categoria_dre'),
                'valor_total' => FormatUtils::formatMoneyDb($request->get('totalizador_valor')),
                'vencimento' => $dataVencimento[$index],
                'valor_vencimento' => FormatUtils::formatMoneyDb($valoresVencimento[$index]),
                'tipo' => $request->has('orcamento') ? 'orcamento' : 'venda',
                'dados_venda' => json_encode($request->except('_token', '_method')),
                //'trace_code' => FormatUtils::traceCode(),
                'competencia' => $request->get('data'),
                'descricao' => $descricoes[$index] ?? '',
                'id_categoria' => $request->get('categoria') ?? 0,
                'codigo_referencia' => $request->get('codigo_referencia') ?? '',
                'condicao' => $request->get('condicao_pagamento'),
            ];
             $contasReceber->where('id', $vendaContaReceber->id)
                 ->update($dadosVenda);
        }

        if($vendasContaReceber->count() < count($valoresVencimento)){
            for($i = 0; $i<(count($valoresVencimento)-$vendasContaReceber->count()); $i++){
                $contasReceber->create([
                    'id_empresa' => auth()->user()->id_empresa,
                    'id_usuario' => auth()->user()->id,
                    'venda_id' => $id,
                    'id_cliente' => $request->get('clienteId'),
                    'sub_categoria_dre' => $request->get('sub_categoria_dre'),
                    'valor_total' => FormatUtils::formatMoneyDb($request->get('totalizador_valor')),
                    'vencimento' => $dataVencimento[$i+$vendasContaReceber->count()],
                    'valor_vencimento' => FormatUtils::formatMoneyDb($valoresVencimento[$i+$vendasContaReceber->count()]),
                    'tipo' => $request->has('orcamento') ? 'orcamento' : 'venda',
                    'dados_venda' => json_encode($request->except('_token', '_method')),
                    'trace_code' => FormatUtils::traceCode(),
                    'competencia' => $request->get('data'),
                    'descricao' => $descricoes[$i+$vendasContaReceber->count()] ?? '',
                    'id_categoria' => $request->get('categoria') ?? 0,
                    'codigo_referencia' => $request->get('codigo_referencia') ?? '',
                    'condicao' => $request->get('condicao_pagamento'),
                ]);
            }

        }

        return response()->json([
            'success' => [
                'message' => 'Venda atualizada com sucesso',
                'redirect' => $request->has('orcamento') ? route('vendas.show', [ 'venda' => $id ]) : route('vendas.index'),
            ]
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    public function imprimir(string $id)
    {
        $venda = Vendas::select('vendas.*', 'users.name', 'clientes.nome')
            ->leftJoin('users', 'users.id', '=', 'vendas.user_id')
            ->leftJoin('clientes', 'clientes.id', '=', 'vendas.cliente_id')
            ->find($id);
        $pdf = new PdfController();
        $pdf->generateOrcamento($venda);
    }

}
