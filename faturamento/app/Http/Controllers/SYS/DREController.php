<?php

namespace App\Http\Controllers\SYS;

use App\Exports\DREExports;
use App\Helpers\Utils;
use App\Http\Controllers\Controller;
use App\Models\ContasReceber;
use App\Models\Produtos;
use App\Models\Vendas;
use Illuminate\Http\Request;
use App\Models\DRE;
use App\Models\Processo;
use App\Models\SubCategoriaDRE;
use App\Models\VinculoDre;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class DREController extends Controller
{
    private $receitaBrutaDeVendas;
    private $deducoesReceitaBruta;

    public function __construct()
    {
        $this->middleware('auth');
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $receita = DRE::where('tipo', 'receita')->get();
        $despesa = DRE::where('tipo', 'despesa')->get();

        $subReceitas = SubCategoriaDRE::select('sub_categoria_dre.id as sub_receita_id',
            'dre.id as dre_id', 'sub_categoria_dre.descricao as sub_categoria_descricao',
            'sub_categoria_dre.vinculo_dre as sub_categoria_vinculo', 'vinculo_dre.descricao as vinculo_descricao',
            'vinculo_dre.id as vinculo_id', 'sub_categoria_dre.editable as editable')
        ->leftJoin('dre', 'dre.id', 'sub_categoria_dre.id_dre')
        ->leftJoin('vinculo_dre', 'vinculo_dre.id', 'sub_categoria_dre.vinculo_dre')
        ->where('dre.tipo', 'receita')
        ->where('sub_categoria_dre.id_empresa',null)
        ->orWhere('sub_categoria_dre.id_empresa', auth()->user()->id_empresa)
        ->get();

        $subDespesas = SubCategoriaDRE::select('sub_categoria_dre.id as sub_despesa_id',
            'dre.id as dre_id', 'sub_categoria_dre.descricao as sub_categoria_descricao',
            'sub_categoria_dre.vinculo_dre as sub_categoria_vinculo', 'vinculo_dre.descricao as vinculo_descricao',
            'vinculo_dre.id as vinculo_id', 'sub_categoria_dre.editable as editable')
        ->leftJoin('dre', 'dre.id', 'sub_categoria_dre.id_dre')
        ->leftJoin('vinculo_dre', 'vinculo_dre.id', 'sub_categoria_dre.vinculo_dre')
        ->where('dre.tipo', 'despesa')
        ->get();

        $vinculoDreReceita = VinculoDre::where('tipo', 'todos')->orWhere('tipo', 'receita')->get();
        $vinculoDreDespesa = VinculoDre::where('tipo', 'todos')->orWhere('tipo', 'despesa')->get();

        return view('dre.list', compact('receita', 'despesa', 'subReceitas', 'subDespesas','vinculoDreReceita', 'vinculoDreDespesa'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        if($request->get('tipo') == 'receita'){
            $this->storeReceita($request);
        }

        if($request->get('tipo') == 'despesa'){
            $this->storeDespesa($request);
        }

        return;
    }

    public function storeDespesa($dados)
    {
        if($dados->get('dre-pai') == 'despesa'){
            DRE::create([
                'nome' => $dados->get('nome'),
                'tipo' => 'despesa',
                'codigo' => '0',
                'id_empresa' => auth()->user()->id_empresa,
                'id_usuario' => auth()->user()->id,
                'editable' => true
            ]);
        }

        if($dados->get('dre-pai') !== 'despesa'){
            SubCategoriaDRE::create([
                'id_dre' => $dados->get('dre-pai'),
                'descricao' => $dados->get('nome'),
                'vinculo_dre' => $dados->get('vinculo-dre'),
                'id_empresa' => auth()->user()->id_empresa,
                'id_usuario' => auth()->user()->id,
                'editable' => true
            ]);
        }
    }

    public function storeReceita($dados)
    {
        if($dados->get('dre-pai') == 'receita'){
            DRE::create([
                'nome' => $dados->get('nome'),
                'tipo' => 'receita',
                'codigo' => '0',
                'id_empresa' => auth()->user()->id_empresa,
                'id_usuario' => auth()->user()->id,
                'editable' => true
            ]);
        }

        if($dados->get('dre-pai') !== 'receita'){
            SubCategoriaDRE::create([
                'id_dre' => $dados->get('dre-pai'),
                'descricao' => $dados->get('nome'),
                'vinculo_dre' => $dados->get('vinculo-dre'),
                'id_empresa' => auth()->user()->id_empresa,
                'id_usuario' => auth()->user()->id,
                'editable' => true
            ]);
        }

        return;
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $selectDREByID = SubCategoriaDRE::select('sub_categoria_dre.id as scd_id', 'dre.tipo as tipo','dre.nome as dre_pai', 'dre.id as dre_id',
            'vinculo_dre.descricao as vinculo_desc', 'vinculo_dre.id as vinculo_id')
        ->leftJoin('dre', 'dre.id', 'sub_categoria_dre.id_dre')
        ->leftJoin('vinculo_dre', 'vinculo_dre.id', 'sub_categoria_dre.vinculo_dre')
        ->where('sub_categoria_dre.id', $id)
        ->where('sub_categoria_dre.id_empresa', auth()->user()->id_empresa)
        ->first();

        if($selectDREByID->tipo == 'despesa'){
            $dre = DRE::where('tipo', 'despesa')
            ->where('id_empresa', auth()->user()->id_empresa)
            ->orWhere('id_empresa', null)->where('tipo', 'despesa')
            ->get();
            $subDre = SubCategoriaDRE::select('sub_categoria_dre.id as sub_despesa_id',
                'dre.id as dre_id', 'sub_categoria_dre.descricao as sub_categoria_descricao',
                'sub_categoria_dre.vinculo_dre as sub_categoria_vinculo', 'vinculo_dre.descricao as vinculo_descricao',
                'vinculo_dre.id as vinculo_id', 'sub_categoria_dre.editable as editable')
            ->leftJoin('dre', 'dre.id', 'sub_categoria_dre.id_dre')
            ->leftJoin('vinculo_dre', 'vinculo_dre.id', 'sub_categoria_dre.vinculo_dre')
            ->where('dre.tipo', 'despesa')
            ->get();
            $vinculoDreReceita = VinculoDre::where('tipo', 'todos')->orWhere('tipo', 'despesa')->get();
        }

        if($selectDREByID->tipo == 'receita'){
            $dre = DRE::where('tipo', 'receita')
            ->where('id_empresa', auth()->user()->id_empresa)
            ->orWhere('id_empresa', null)->where('tipo', 'receita')
            ->get();
            $subDre = SubCategoriaDRE::select('sub_categoria_dre.id as sub_despesa_id',
                'dre.id as dre_id', 'sub_categoria_dre.descricao as sub_categoria_descricao',
                'sub_categoria_dre.vinculo_dre as sub_categoria_vinculo', 'vinculo_dre.descricao as vinculo_descricao',
                'vinculo_dre.id as vinculo_id', 'sub_categoria_dre.editable as editable')
            ->leftJoin('dre', 'dre.id', 'sub_categoria_dre.id_dre')
            ->leftJoin('vinculo_dre', 'vinculo_dre.id', 'sub_categoria_dre.vinculo_dre')
            ->where('dre.tipo', 'receita')
            ->get();
            $vinculoDre = VinculoDre::where('tipo', 'todos')->orWhere('tipo', 'receita')->get();
        }



        return response()->json([
            'registro' => $selectDREByID,
            'dre' => $dre,
            'subDre' => $subDre,
            'vinculo' => $vinculoDre
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        if($request->get('tipo') == 'receita'){
            $this->updateReceita($request);
        }

        if($request->get('tipo') == 'despesa'){
            $this->updateDespesa($request);
        }
    }

    public function updateReceita($dados)
    {

    }

    public function updateDespesa($dados)
    {
        if($dados->get('dre-pai') !== 'despesa'){
            SubCategoriaDRE::where('id', $dados->get('id'))
            ->where('id_empresa', auth()->user()->id_empresa)
            ->update([
                'id_dre' => $dados->get('dre-pai'),
                'descricao' => $dados->get('nome'),
                'vinculo_dre' => $dados->get('vinculo-dre'),
                'id_usuario' => auth()->user()->id
            ]);
        }

        if($dados->get('dre-pai') == 'despesa'){

            $subDre = $this->validateSubDre($dados->get('id'));

            if($subDre){
                $this->deleteSubCategoriaDre($dados->get('id'));
            }

            $drePai = $this->validateDrePai($dados->get('dre-pai'));

            if($drePai){
                DRE::where('id', $dados->get('dre-pai'))->update([
                    'id_usuario' => auth()->user()->id,
                    'nome' => auth()->user()->id,
                    'editable' => true,
                ]);
            }
            if(!$drePai){
                DRE::create(
                    [
                        'id_usuario' => auth()->user()->id,
                        'id_empresa' => auth()->user()->id_empresa,
                        'nome' => $dados->get('nome'),
                        'tipo' => 'despesa',
                        'codigo' => 0,
                        'editable' => true
                    ]
                );
            }

        }
    }

    public function validateDrePai($id)
    {
        $validate = DRE::where('id', $id)->where('id_empresa')->get();
        if($validate->count() == 1){
            return true;
        }
        return false;
    }

    public function deleteSubCategoriaDre($id)
    {
        SubCategoriaDRE::where('id', $id)->where('id_empresa', auth()->user()->id_empresa)->delete();
        return;
    }

    public function validateSubDre($id)
    {
        $validate = SubCategoriaDRE::where('id',$id)->where('id_empresa', auth()->user()->id_empresa)->get();
        if($validate->count() == 1){
            return true;
        }
        return false;
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    public function dreReport($periodo, $startYear = null, $endYear = null)
    {
        $idEmpresa = auth()->user()->id_empresa;

        $period = ["startYear" => $startYear, "endYear" => $endYear];

        $startYear = $startYear ?? date('Y');
        $endYear = $endYear ?? date('Y');
        $year = $periodo == 'yearly' ? ["{$startYear}-01-01", "{$endYear}-12-31"] : $startYear;

        $receitasOperacionais = $this->getMonthlyData(
            'contas_receber',
            'contas_receber.sub_categoria_dre',
            'contas_receber.competencia',
            'contas_receber.valor_vencimento',
            [2],
            $year
        );

        $receitaTotalVendas =  $this->getFormattedSalesData('totalizador_itens', 'ativo', $year);
        $freteEAdicionais = $this->getFormattedSalesData('totalizador_valor_adicional', 'ativo', $year);

        $receitasOperacionais = $this->somarValoresMensais($receitasOperacionais, $receitaTotalVendas);
        $receitasOperacionaisTotal = $this->somarValoresMensais($receitasOperacionais, $freteEAdicionais);

        $impostosSobreVendas = $this->getMonthlyData(
            'processo',
            'processo.id_sub_dre',
            'processo.competencia',
            'processo.valor',
            [5],
            $year
        );

        $comissoesSobreVendas = $this->getMonthlyData(
            'processo',
            'processo.id_sub_dre',
            'processo.competencia',
            'processo.valor',
            [6],
            $year
        );

        $deducoesParcial = $this->somarValoresMensais($impostosSobreVendas, $comissoesSobreVendas);

        $descontosIncondicionais = $this->getFormattedSalesData('totalizador_desconto', 'ativo', $year);
        //$descontosIncondicionais = Utils::monthNoValueList($descontosIncondicionais);

        $devolucoesDeVendas  = $this->getFormattedSalesData('totalizador_valor', 'cancelado', $year);

        //$devolucoesDeVendas = Utils::monthNoValueList($devolucoesDeVendas);

        $deducoesReceitaBruta = $this->somarValoresMensais($deducoesParcial, $descontosIncondicionais);

        $deducoesReceitaBruta = $this->somarValoresMensais($deducoesReceitaBruta, $devolucoesDeVendas);

        $receitaLiquidaDeVendas = $this->subtrairValoresMensais($receitasOperacionaisTotal, $deducoesReceitaBruta);

        $custoDosProdutosVendidos = $this->getProductCosts($year);

        $custoDasVendasDeProdutos = is_array($year) ?
            Utils::yearValueList([0], $year) :
            Utils::monthNoValueList(collect(['1999-06' => 0]));

        $custosOperacionais = $this->getMonthlyData(
            'processo',
            'processo.id_sub_dre',
            'processo.competencia',
            'processo.valor',
            [7],
            $year
        );

        $custosDosServicosPrestados = $this->getMonthlyData(
            'processo',
            'processo.id_sub_dre',
            'processo.competencia',
            'processo.valor',
            [7],
            $year
        );

        $custosOperacionais = $this->somarValoresMensais($custosOperacionais, $custosDosServicosPrestados);
        $custosOperacionais = $this->somarValoresMensais($custosOperacionais, $custoDosProdutosVendidos);


        $lucroBruto = $this->subtrairValoresMensais($receitaLiquidaDeVendas, $custosOperacionais);

        $despesasOperacionaisTotal = $this->getMonthlyData(
            'processo',
            'processo.id_sub_dre',
            'processo.competencia',
            'processo.valor',
            [8,9,10],
            $year
        );

        $despesasComerciais = $this->getMonthlyData(
            'processo',
            'processo.id_sub_dre',
            'processo.competencia',
            'processo.valor',
            [10],
            $year
        );

        $despesasAdministrativas = $this->getMonthlyData(
            'processo',
            'processo.id_sub_dre',
            'processo.competencia',
            'processo.valor',
            [9],
            $year
        );

        $despesasOperacionais = $this->getMonthlyData(
            'processo',
            'processo.id_sub_dre',
            'processo.competencia',
            'processo.valor',
            [8],
            $year
        );

        $lucroPrejuizoOperacional = $this->subtrairValoresMensais($lucroBruto, $despesasOperacionaisTotal);

        $receitasFinanceiras = $this->getMonthlyData(
            'contas_receber',
            'contas_receber.sub_categoria_dre',
            'contas_receber.competencia',
            'contas_receber.valor_vencimento',
            [3],
            $year
        );

        $despesasFinanceiras = $this->getMonthlyData(
            'processo',
            'processo.id_sub_dre',
            'processo.competencia',
            'processo.valor',
            [12],
            $year
        );

        $receitasEDespesasFinanceiras = $this->subtrairValoresMensais($receitasFinanceiras, $despesasFinanceiras);

        $outrasReceitasNaoOperacionais = $this->getMonthlyData(
            'contas_receber',
            'contas_receber.sub_categoria_dre',
            'contas_receber.competencia',
            'contas_receber.valor_vencimento',
            [4],
            $year
        );

        $outrasDespesasNaoOperacionais = $this->getMonthlyData(
            'processo',
            'processo.id_sub_dre',
            'processo.competencia',
            'processo.valor',
            [11],
            $year
        );

        $outrasReceitasEDespesasNaoOperacionais = $this->subtrairValoresMensais($outrasReceitasNaoOperacionais, $outrasDespesasNaoOperacionais);

        $lucroPrejuizoLiquido = $this->calcularLucroPrejuizoLiquido($lucroPrejuizoOperacional, $receitasEDespesasFinanceiras, $outrasReceitasEDespesasNaoOperacionais);

        $despesasComInvestimentosEEmpréstimos = $this->getMonthlyData(
            'processo',
            'processo.id_sub_dre',
            'processo.competencia',
            'processo.valor',
            [13, 14],
            $year
        )->toArray();

        $investimentosEmImobilizado = $this->getMonthlyData(
            'processo',
            'processo.id_sub_dre',
            'processo.competencia',
            'processo.valor',
            [13],
            $year
        );

        $emprestimosEDividas = $this->getMonthlyData(
            'processo',
            'processo.id_sub_dre',
            'processo.competencia',
            'processo.valor',
            [14],
            $year
        );

        $lucroPrejuizoFinal = $this->calcularLucroPrejuizoFinal($lucroPrejuizoLiquido, $despesasComInvestimentosEEmpréstimos);

        $data = [
            //receitas operacionais
            'Receitas Operacionais' => $receitasOperacionaisTotal,
            '+ Receita de Vendas de Produtos e Serviços' => $receitasOperacionais,
            '+ Receita de Fretes e Entregas' => $freteEAdicionais,
            //so aparece em venda de produto quando cadastra o frete
            '= Receita Bruta de Vendas' => $receitasOperacionaisTotal,
            //deducoes de receita bruta
            'Deduções da Receita Bruta' => $deducoesReceitaBruta, //total deducao
            '- Impostos Sobre Vendas' => $impostosSobreVendas, //id vinculo = 5
            '- Comissões Sobre Vendas' => $comissoesSobreVendas,
            //id vinculo = 6
            '- Descontos Incondicionais' => $descontosIncondicionais,
            //quando aplica desconto na venda
            '- Devoluções de Vendas' => $devolucoesDeVendas,
            '= Receita Líquida de Vendas' => $receitaLiquidaDeVendas,
            //custos operacionais
            'Custos Operacionais' => $custosOperacionais,
            '- Custo dos Produtos Vendidos' => $custoDosProdutosVendidos,
            //so aparece quando tem custo cadastrado em um produto
            '- Custo das Vendas de Produtos' => $custoDasVendasDeProdutos,
            //precisamos descobrir
            '- Custo dos Serviços Prestados' => $custosDosServicosPrestados,
            //id vinculo = 7
            '= Lucro Bruto' => $lucroBruto,
            //despesas operacionais
            'Despesas Operacionais' => $despesasOperacionaisTotal,
            '- Despesas Comerciais' => $despesasComerciais,
            //id vinculo = 10
            '- Despesas Administrativas' => $despesasAdministrativas,
            //id vinculo = 9
            '- Despesas Operacionais' => $despesasOperacionais,
            //id vinculo = 8
            '= Lucro / Prejuízo Operacional' => $lucroPrejuizoOperacional,
            'Receitas e Despesas Financeiras' => $receitasEDespesasFinanceiras,
            //id vinculo 3 e 12
            '+ Receitas e Rendimentos Financeiros' => $receitasFinanceiras,//id vinculo 3
            '- Despesas Financeiras' => $despesasFinanceiras,//id vinculo 12
            //outras receitas e despesas nao operacionais
            'Outras Receitas e Despesas Não Operacionais' => $outrasReceitasEDespesasNaoOperacionais,//id vinculo = 4 e 11
            '+ Outras Receitas Não Operacionais' => $outrasReceitasNaoOperacionais, //id vinculo = 4
            '- Outras Despesas Não Operacionais' => $outrasDespesasNaoOperacionais,//id vinculo = 11
            '= Lucro / Prejuízo Líquido' => $lucroPrejuizoLiquido,
            'Despesas com Investimentos e Empréstimos' => $despesasComInvestimentosEEmpréstimos,
            //id dre = 13 e 14
            '- Investimentos em Imobilizado' => $investimentosEmImobilizado,
            //id dre = 13
            '- Empréstimos e Dívidas' => $emprestimosEDividas,
            //id dre = 14
            '= Lucro / Prejuízo Final' => $lucroPrejuizoFinal,
        ];

        $tipo = is_null($periodo) ? 'monthly' : $periodo;
        return $this->reportWithPeriod(
            $tipo,
            $period,
            $data
        );
    }

    public function reportWithPeriod($tipo, $period, $array)
    {
        switch ($tipo) {
            case 'monthly':
                return $array;
                break;
            case 'bimonthly':
                return Utils::sumBimonthly($array);
                break;
            case 'quarterly':
                return Utils::sumQuarterly($array);
                break;
            case 'semester':
                return Utils::sumSemester($array);
                break;
            case 'yearly':
                return $array;
                break;
            default:
                return 'Periodo não encontrado';
                break;
        }
    }

    private function getMonthlyData($table, $categoryColumn, $dateColumn, $valueColumn, $categoryIds, $year=null)
    {
        $data = DB::table($table)
            ->leftJoin('sub_categoria_dre', $categoryColumn, 'sub_categoria_dre.id')
            ->leftJoin('vinculo_dre', 'sub_categoria_dre.vinculo_dre', 'vinculo_dre.id')
            ->whereIn('vinculo_dre.id', $categoryIds);

        if(is_array($year)){
            $data = $data->whereBetween('competencia', $year);
            $sqlString = "DATE_FORMAT({$dateColumn}, '%Y') as year";
            $groupString = 'year';
        }else{
            $data = $data->whereYear('competencia', $year);
            $sqlString = "DATE_FORMAT({$dateColumn}, '%Y-%m') as mes";
            $groupString = 'mes';
        }

            $data = $data->where($table.'.id_empresa', auth()->user()->id_empresa);

        if($table == 'contas_receber'){
            $data = $data->whereNull('contas_receber.tipo');
        }
            $data = $data->selectRaw('
                '.$sqlString.',
                SUM(' . $valueColumn . ') as total
            ')
            ->groupBy($groupString)
            ->get()
            ->pluck('total', $groupString);
        return is_array($year) ? Utils::yearValueList($data, $year) : Utils::monthNoValueList($data);

    }

    public function calcularLucroPrejuizoLiquido($lucroPrejuizoOperacional, $receitasEDespesasFinanceiras, $outrasReceitasEDespesasNaoOperacionais)
    {
        return array_map(function($operacional, $financeiras, $naoOperacionais) {
            return $operacional + $financeiras + $naoOperacionais;
        }, $lucroPrejuizoOperacional, $receitasEDespesasFinanceiras, $outrasReceitasEDespesasNaoOperacionais);
    }

    public function calcularLucroPrejuizoFinal($lucroPrejuizoLiquido, $despesasInvestimentos)
    {
        return array_map(function($liquido, $investimentos) {
            return $liquido - $investimentos;
        }, $lucroPrejuizoLiquido, $despesasInvestimentos);
    }

    public function subtrairValoresMensais($array1, $array2)
    {
        $resultado = [];
        foreach ($array1 as $mes => $valor) {
            if (isset($array2[$mes])) {
                $resultado[$mes] = $valor - $array2[$mes];
            } else {
                $resultado[$mes] = $valor;
            }
        }
        return $resultado;
    }

    public function somarValoresMensais($array1, $array2)
    {
        $resultado = [];
        foreach ($array1 as $mes => $valor) {
            if (isset($array2[$mes])) {
                $resultado[$mes] = $valor + $array2[$mes];
            } else {
                $resultado[$mes] = $valor;
            }
        }
        return $resultado;
    }

    public function getFormattedSalesData($field, $status = null, $year)
    {
        if(is_array($year)){
            $sqlString = "DATE_FORMAT(JSON_UNQUOTE(JSON_EXTRACT(dados_venda, '$.data')), '%Y') as year";
        }else{
            $sqlString = "DATE_FORMAT(JSON_UNQUOTE(JSON_EXTRACT(dados_venda, '$.data')), '%Y-%m') as mes";
        }
        $query = Vendas::selectRaw($sqlString.',
            SUM(
                REPLACE(
                    REPLACE(
                        JSON_UNQUOTE(
                            JSON_EXTRACT(
                                dados_venda, "$.' . $field . '"
                            )
                        ), ".", ""),
                    ",", "."
                )
            ) as valor
        ');
        if(is_array($year)) {
            $query = $query->whereBetween(DB::raw('JSON_UNQUOTE(JSON_EXTRACT(dados_venda, "$.data"))'), $year);
        }else{
            $query = $query->whereYear(DB::raw('JSON_UNQUOTE(JSON_EXTRACT(dados_venda, "$.data"))'), $year);
        }
           $query = $query->where('id_empresa', auth()->user()->id_empresa);

        if ($status) {
            $query->where('status', $status);
        }

        return is_array($year) ? Utils::yearValueList(
            $query->groupBy('year')->get()->pluck('valor', 'year'),
            $year
        ) : Utils::monthNoValueList(
            $query->groupBy('mes')->get()->pluck('valor', 'mes')
        );
    }

    public function getProductCosts($year)
    {
        if(is_array($year)){
            $sqlString = "DATE_FORMAT(JSON_UNQUOTE(JSON_EXTRACT(dados_venda, '$.data')), '%Y') as year";
        }else{
            $sqlString = "DATE_FORMAT(JSON_UNQUOTE(JSON_EXTRACT(dados_venda, '$.data')), '%Y-%m') as mes";
        }
        // 1. Extrair e formatar as vendas com data, ids dos produtos e quantidades
        $results = Vendas::selectRaw($sqlString.'
            ,
            JSON_UNQUOTE(
                JSON_EXTRACT(
                    dados_venda, "$.produtoId"
                )
            ) as produto_ids,
            JSON_UNQUOTE(
                JSON_EXTRACT(
                    dados_venda, "$.quantidade"
                )
            ) as quantidades
        ');
            if(is_array($year)){
                $results = $results->whereBetween(DB::raw('JSON_UNQUOTE(JSON_EXTRACT(dados_venda, "$.data"))'), $year);
            }else{
                $results = $results->whereYear(DB::raw('JSON_UNQUOTE(JSON_EXTRACT(dados_venda, "$.data"))'), $year);
            }
            $results = $results->where('id_empresa', auth()->user()->id_empresa)
            ->get();

        // 2. Processar dados e calcular custos por mês
        $monthlyCosts = $results->map(function ($result) {
            $produtoIds = json_decode($result->produto_ids, true);
            $quantidades = json_decode($result->quantidades, true);

            $totalCost = 0;
            if (is_array($produtoIds) && is_array($quantidades)) {
                foreach ($produtoIds as $index => $produtoId) {
                    $quantidade = isset($quantidades[$index]) ? (int) $quantidades[$index] : 0;
                    if ($quantidade > 0) {
                        // Adiciona os custos dos produtos
                        $produto = DB::table('produtos')->where('id', $produtoId)->first(['valor_custo']);
                        if ($produto) {
                            $totalCost += $produto->valor_custo * $quantidade;
                        }
                    }
                }
            }
            if (isset($result->year)){
                return [
                    'year' => $result->year,
                    'total_custo' => $totalCost,
                ];
            }   else {
                return [
                    'mes' => $result->mes,
                    'total_custo' => $totalCost,
                ];
            }
        });

        // 3. Agrupar e somar os custos por mês
        if(is_array($year)){
            $costByMonth = $monthlyCosts->groupBy('year')->mapWithKeys(function ($items, $key) {
                return [
                    $key => $items->sum('total_custo'),
                ];
            });
        }else{
            $costByMonth = $monthlyCosts->groupBy('mes')->mapWithKeys(function ($items, $key) {
                return [
                    $key => $items->sum('total_custo'),
                ];
            });
        }

        return is_array($year) ? Utils::yearValueList($costByMonth, $year) : Utils::monthNoValueList($costByMonth);
    }

    public function excelDre($periodo, $startYear, $endYear)
    {
        return Excel::download(new DREExports($periodo, $startYear, $endYear), 'dre.xlsx');
    }
}
