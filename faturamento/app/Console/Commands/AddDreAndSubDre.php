<?php

namespace App\Console\Commands;

use App\Models\DRE;
use App\Models\SubCategoriaDRE;
use App\Models\VinculoDre;
use Illuminate\Console\Command;

class AddDreAndSubDre extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'dre:addcategoriadre';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->addVinculoDre();
        $this->addDre();
        $this->addSubCategoriaDreReceita();
        $this->addDespesasDRE();
    }

    public function addVinculoDre()
    {
        $todos = [
            'Não mostrar no DRE Gerencial',
        ];

        $receitas = [
            'Receita de Vendas de Produtos e Serviços',
            'Receitas e Rendimentos Financeiros',
            'Outras Receitas Não Operacionais'
        ];

        $despesas = [
            'Impostos Sobre Vendas',
            'Comissões Sobre Vendas',
            'Custo dos Serviços Prestados',
            'Despesas Operacionais',
            'Despesas Administrativas',
            'Despesas Comerciais',
            'Outras Despesas Não Operacionais',
            'Despesas Financeiras',
            'Investimentos em Imobilizado',
            'Empréstimos e Dívidas'
        ];

        foreach($todos as $todo){
            VinculoDre::create(['descricao' => $todo, 'tipo' => 'todos']);
        }

        foreach($receitas as $receita){
            VinculoDre::create(['descricao' => $receita, 'tipo' => 'receita']);
        }

        foreach($despesas as $despesa){
            VinculoDre::create(['descricao' => $despesa, 'tipo' => 'despesa']);
        }
    }

    public function addSubCategoriaDreReceita()
    {
        $receitasVendasServicos = DRE::where('nome', '3.01 Receitas de Vendas e de Serviços')->first();
        $receitasFinanceiras = DRE::where('nome', '3.02 Receitas Financeiras')->first();
        $receitasOutrasReceitasEntradas = DRE::where('nome', '3.03 Outras Receitas e Entradas')->first();
        //vinculo dre
        $naoMostrar = VinculoDre::where('descricao', 'Não mostrar no DRE Gerencial')->first();
        $receitaVendasProdutos = VinculoDre::where('descricao', 'Receita de Vendas de Produtos e Serviços')->first();
        $receitaRendimentosFinanceiros = VinculoDre::where('descricao', 'Receitas e Rendimentos Financeiros')->first();
        $outrasReceitasNaoOperacionais = VinculoDre::where('descricao', 'Outras Receitas Não Operacionais')->first();

        $categoria = [
            [
                'descricao' => 'Receitas de Serviços',
                'id_dre' => $receitasVendasServicos->id,
                'vinculo_dre' => $receitaVendasProdutos->id,
            ],
            [
                'descricao' => 'Receitas de Vendas',
                'id_dre' => $receitasVendasServicos->id,
                'vinculo_dre' => $receitaVendasProdutos->id
            ],
            [
                'descricao' => 'Rendimentos de Aplicações',
                'id_dre' => $receitasFinanceiras->id,
                'vinculo_dre' => $receitaRendimentosFinanceiros->id,
            ],
            [
                'descricao' => 'Adiantamentos para futuros Aumentos de Capital - AFAC',
                'id_dre' => $receitasOutrasReceitasEntradas->id,
                'vinculo_dre' => $naoMostrar->id
            ],
            [
                'descricao' => 'Empréstimos de Bancos',
                'id_dre' => $receitasOutrasReceitasEntradas->id,
                'vinculo_dre' => $naoMostrar->id
            ],
            [
                'descricao' => 'Empréstimos de Instituições',
                'id_dre' => $receitasOutrasReceitasEntradas->id,
                'vinculo_dre' => $naoMostrar->id
            ],
            [
                'descricao' => 'Empréstimos de Sócios',
                'id_dre' => $receitasOutrasReceitasEntradas->id,
                'vinculo_dre' => $naoMostrar->id
            ],
            [
                'descricao' => 'Integralização de Capital Social',
                'id_dre' => $receitasOutrasReceitasEntradas->id,
                'vinculo_dre' => $naoMostrar->id
            ],
            [
                'descricao' => 'Receitas a Identificar',
                'id_dre' => $receitasOutrasReceitasEntradas->id,
                'vinculo_dre' => $outrasReceitasNaoOperacionais->id
            ],

        ];

        foreach($categoria as $subCategoria){
            SubCategoriaDRE::create($subCategoria);
        }
    }

    public function addDespesasDRE()
    {
        $impostosSobreVenda = VinculoDre::where('descricao', 'Impostos Sobre Vendas')->first();
        $comissoesSobreVendas = VinculoDre::where('descricao', 'Comissões Sobre Vendas')->first();
        $custoServicosPrestados = VinculoDre::where('descricao', 'Custo dos Serviços Prestados')->first();
        $despesasOperacionais = VinculoDre::where('descricao', 'Despesas Operacionais')->first();
        $despesasAdministrativas = VinculoDre::where('descricao', 'Despesas Administrativas')->first();
        $despesasComerciais = VinculoDre::where('descricao', 'Despesas Comerciais')->first();
        $outrasDespesasNaoOperacionais = VinculoDre::where('descricao', 'Outras Despesas Não Operacionais')->first();
        $despesasFinanceiras = VinculoDre::where('descricao', 'Despesas Financeiras')->first();
        $investimentoImobilizado = VinculoDre::where('descricao', 'Investimentos em Imobilizado')->first();
        $emprestimoDividas = VinculoDre::where('descricao', 'Empréstimos e Dívidas')->first();
        $naoMostrar = VinculoDre::where('descricao', 'Não mostrar no DRE Gerencial')->first();
       //id_dre
        $impostoSobreVendasEServicosPai = DRE::where('nome', '4.01 Impostos sobre Vendas e sobre Serviços')->first();
        $despesasVendasEServicosPai = DRE::where('nome', '4.02 Despesas com Vendas e Serviços')->first();
        $despesasSalariosEncargosPai = DRE::where('nome', '4.03 Despesas com Salários e Encargos')->first();
        $despesasColaboradoresPai = DRE::where('nome','4.04 Despesas com Colaboradores')->first();
        $despesasAdministrativasPai = DRE::where('nome','4.05 Despesas Administrativas')->first();
        $despesasComerciaisPai = DRE::where('nome','4.06 Despesas Comerciais')->first();
        $despesasImovelPai = DRE::where('nome','4.07 Despesas com Imóvel')->first();
        $despesasVeiculosPai = DRE::where('nome','4.08 Despesas com Veículos')->first();
        $despesasDiretoriaPai = DRE::where('nome','4.09 Despesas com Diretoria')->first();
        $despesasFinanceirasPai = DRE::where('nome','4.10 Despesas Financeiras')->first();
        $outrasDespesasPai = DRE::where('nome','4.11 Outras Despesas')->first();
        $bensImobilizadosEmpresaPai = DRE::where('nome','5.01 Bens Imobilizados da Empresa')->first();
        $emprestimosFinanciamentosPai = DRE::where('nome','5.02 Empréstimos e Financiamentos')->first();
        $parcelamentoDividasPai = DRE::where('nome','5.03 Parcelamentos e Dívidas')->first();


        $categoria = [
            [
                'descricao' => 'ICMS ST sobre Vendas',
                'id_dre' => $impostoSobreVendasEServicosPai->id,
                'vinculo_dre' => $impostosSobreVenda->id
            ],
            [
                'descricao' => 'ISS sobre Faturamento',
                'id_dre' => $impostoSobreVendasEServicosPai->id,
                'vinculo_dre' => $impostosSobreVenda->id
            ],
            [
                'descricao' => 'Simples Nacional - DAS',
                'id_dre' => $impostoSobreVendasEServicosPai->id,
                'vinculo_dre' => $impostosSobreVenda->id
            ],
            [
                'descricao' => 'Comissões de Vendedores',
                'id_dre' => $despesasVendasEServicosPai->id,
                'vinculo_dre' => $comissoesSobreVendas->id
            ],
            [
                'descricao' => 'Materiais Aplicados na Prestação de Serviços',
                'id_dre' => $despesasVendasEServicosPai->id,
                'vinculo_dre' => $custoServicosPrestados->id
            ],
            [
                'descricao' => 'Materiais para Revenda',
                'id_dre' => $despesasVendasEServicosPai->id,
                'vinculo_dre' => $naoMostrar->id
            ],
            [
                'descricao' => 'Trasnporte de Mercadorias Vendidas',
                'id_dre' => $despesasVendasEServicosPai->id,
                'vinculo_dre' => $despesasOperacionais->id
            ],
            [
                'descricao' => '13° Salário - 1° Parcela',
                'id_dre' => $despesasSalariosEncargosPai->id,
                'vinculo_dre' => $despesasAdministrativas->id
            ],
            [
                'descricao' => '13° Salário - 2° Parcela',
                'id_dre' => $despesasSalariosEncargosPai->id,
                'vinculo_dre' => $despesasAdministrativas->id
            ],
            [
                'descricao' => 'Adiantamento Salarial',
                'id_dre' => $despesasSalariosEncargosPai->id,
                'vinculo_dre' => $despesasAdministrativas->id
            ],
            [
                'descricao' => 'Férias',
                'id_dre' => $despesasSalariosEncargosPai->id,
                'vinculo_dre' => $despesasAdministrativas->id
            ],
            [
                'descricao' => 'FGTS e Multa de FGTS',
                'id_dre' => $despesasSalariosEncargosPai->id,
                'vinculo_dre' => $despesasAdministrativas->id
            ],
            [
                'descricao' => 'INSS sobre Salários - GPS',
                'id_dre' => $despesasSalariosEncargosPai->id,
                'vinculo_dre' => $despesasAdministrativas->id
            ],
            [
                'descricao' => 'IRRF s/ Salários - DARF 0561',
                'id_dre' => $despesasSalariosEncargosPai->id,
                'vinculo_dre' => $despesasAdministrativas->id
            ],
            [
                'descricao' => 'PLR - Participação nos Lucros e Resultados',
                'id_dre' => $despesasSalariosEncargosPai->id,
                'vinculo_dre' => $despesasAdministrativas->id
            ],
            [
                'descricao' => 'Remuneração de Autônomos',
                'id_dre' => $despesasSalariosEncargosPai->id,
                'vinculo_dre' => $despesasAdministrativas->id
            ],
            [
                'descricao' => 'Remuneração de Estagiários',
                'id_dre' => $despesasSalariosEncargosPai->id,
                'vinculo_dre' => $despesasAdministrativas->id
            ],
            [
                'descricao' => 'Rescisões',
                'id_dre' => $despesasSalariosEncargosPai->id,
                'vinculo_dre' => $despesasAdministrativas->id
            ],
            [
                'descricao' => 'Salários',
                'id_dre' => $despesasSalariosEncargosPai->id,
                'vinculo_dre' => $despesasAdministrativas->id
            ],
            [
                'descricao' => 'Confraternizações',
                'id_dre' => $despesasColaboradoresPai->id,
                'vinculo_dre' => $despesasAdministrativas->id
            ],
            [
                'descricao' => 'Contribuição Sindical',
                'id_dre' => $despesasColaboradoresPai->id,
                'vinculo_dre' => $despesasAdministrativas->id
            ],
            [
                'descricao' => 'Cursos e Treinamentos',
                'id_dre' => $despesasColaboradoresPai->id,
                'vinculo_dre' => $despesasAdministrativas->id
            ],
            [
                'descricao' => 'Exames Médicos',
                'id_dre' => $despesasColaboradoresPai->id,
                'vinculo_dre' => $despesasAdministrativas->id
            ],
            [
                'descricao' => 'Farmácia',
                'id_dre' => $despesasColaboradoresPai->id,
                'vinculo_dre' => $despesasAdministrativas->id
            ],
            [
                'descricao' => 'Gratificações',
                'id_dre' => $despesasColaboradoresPai->id,
                'vinculo_dre' => $despesasAdministrativas->id
            ],
            [
                'descricao' => 'Plano de Saúde Colaboradores',
                'id_dre' => $despesasColaboradoresPai->id,
                'vinculo_dre' => $despesasAdministrativas->id
            ],
            [
                'descricao' => 'Plano Odontológico Colaboradores',
                'id_dre' => $despesasColaboradoresPai->id,
                'vinculo_dre' => $despesasAdministrativas->id
            ],
            [
                'descricao' => 'Seguro de Vida',
                'id_dre' => $despesasColaboradoresPai->id,
                'vinculo_dre' => $despesasAdministrativas->id
            ],
            [
                'descricao' => 'Uniformes',
                'id_dre' => $despesasColaboradoresPai->id,
                'vinculo_dre' => $despesasAdministrativas->id
            ],
            [
                'descricao' => 'Vale-Alimentação',
                'id_dre' => $despesasColaboradoresPai->id,
                'vinculo_dre' => $despesasAdministrativas->id
            ],
            [
                'descricao' => 'Vale-Transporte',
                'id_dre' => $despesasColaboradoresPai->id,
                'vinculo_dre' => $despesasAdministrativas->id
            ],
            [
                'descricao' => 'Bens de Pequeno Valor',
                'id_dre' => $despesasAdministrativasPai->id,
                'vinculo_dre' => $despesasAdministrativas->id
            ],
            [
                'descricao' => 'Cartório',
                'id_dre' => $despesasAdministrativasPai->id,
                'vinculo_dre' => $despesasAdministrativas->id
            ],
            [
                'descricao' => 'Copa e Cozinha',
                'id_dre' => $despesasAdministrativasPai->id,
                'vinculo_dre' => $despesasAdministrativas->id
            ],
            [
                'descricao' => 'Correios',
                'id_dre' => $despesasAdministrativasPai->id,
                'vinculo_dre' => $despesasAdministrativas->id
            ],
            [
                'descricao' => 'Honorários (outros)',
                'id_dre' => $despesasAdministrativasPai->id,
                'vinculo_dre' => $despesasAdministrativas->id
            ],
            [
                'descricao' => 'Honorários Advocatícios',
                'id_dre' => $despesasAdministrativasPai->id,
                'vinculo_dre' => $despesasAdministrativas->id
            ],
            [
                'descricao' => 'Honorários Contábeis',
                'id_dre' => $despesasAdministrativasPai->id,
                'vinculo_dre' => $despesasAdministrativas->id
            ],
            [
                'descricao' => 'Lanches e Refeições',
                'id_dre' => $despesasAdministrativasPai->id,
                'vinculo_dre' => $despesasAdministrativas->id
            ],
            [
                'descricao' => 'Manutenção de Equipamentos',
                'id_dre' => $despesasAdministrativasPai->id,
                'vinculo_dre' => $despesasAdministrativas->id
            ],
            [
                'descricao' => 'Materiais de Escritório',
                'id_dre' => $despesasAdministrativasPai->id,
                'vinculo_dre' => $despesasAdministrativas->id
            ],
            [
                'descricao' => 'Materiais de Limpeza e de Higiene',
                'id_dre' => $despesasAdministrativasPai->id,
                'vinculo_dre' => $despesasAdministrativas->id
            ],
            [
                'descricao' => 'Retenção - Darf 1708 - IRRF',
                'id_dre' => $despesasAdministrativasPai->id,
                'vinculo_dre' => $despesasAdministrativas->id
            ],
            [
                'descricao' => 'Retenção - Darf 5953 - PIS/COFINS/CSLL',
                'id_dre' => $despesasAdministrativasPai->id,
                'vinculo_dre' => $despesasAdministrativas->id
            ],
            [
                'descricao' => 'Retenção - Darf 2631 - INSS',
                'id_dre' => $despesasAdministrativasPai->id,
                'vinculo_dre' => $despesasAdministrativas->id
            ],
            [
                'descricao' => 'Retenção - ISS Serviços Tomados',
                'id_dre' => $despesasAdministrativasPai->id,
                'vinculo_dre' => $despesasAdministrativas->id
            ],
            [
                'descricao' => 'Telefonia e Internet',
                'id_dre' => $despesasAdministrativasPai->id,
                'vinculo_dre' => $despesasAdministrativas->id
            ],
            [
                'descricao' => 'Telefonia Móvel',
                'id_dre' => $despesasAdministrativasPai->id,
                'vinculo_dre' => $despesasAdministrativas->id
            ],
            [
                'descricao' => 'Transporte Urbano (táxi, Uber)',
                'id_dre' => $despesasAdministrativasPai->id,
                'vinculo_dre' => $despesasAdministrativas->id
            ],
            [
                'descricao' => 'Brindes para Cliente',
                'id_dre' => $despesasComerciaisPai->id,
                'vinculo_dre' => $despesasComerciais->id
            ],
            [
                'descricao' => 'Marketing e Publicidade',
                'id_dre' => $despesasComerciaisPai->id,
                'vinculo_dre' => $despesasComerciais->id
            ],
            [
                'descricao' => 'Viagens e Representações',
                'id_dre' => $despesasComerciaisPai->id,
                'vinculo_dre' => $despesasComerciais->id
            ],
            [
                'descricao' => 'Água e Saneamento',
                'id_dre' => $despesasImovelPai->id,
                'vinculo_dre' => $despesasAdministrativas->id
            ],
            [
                'descricao' => 'Aluguel',
                'id_dre' => $despesasImovelPai->id,
                'vinculo_dre' => $despesasAdministrativas->id
            ],
            [
                'descricao' => 'Alvará de Funcionamento',
                'id_dre' => $despesasImovelPai->id,
                'vinculo_dre' => $despesasAdministrativas->id
            ],
            [
                'descricao' => 'Condomínio',
                'id_dre' => $despesasImovelPai->id,
                'vinculo_dre' => $despesasAdministrativas->id
            ],
            [
                'descricao' => 'Energia Elétrica',
                'id_dre' => $despesasImovelPai->id,
                'vinculo_dre' => $despesasAdministrativas->id
            ],
            [
                'descricao' => 'IPTU',
                'id_dre' => $despesasImovelPai->id,
                'vinculo_dre' => $despesasAdministrativas->id
            ],
            [
                'descricao' => 'Manutenção Predial',
                'id_dre' => $despesasImovelPai->id,
                'vinculo_dre' => $despesasAdministrativas->id
            ],
            [
                'descricao' => 'Retenção - Darf 3208 - IRRF Aluguel',
                'id_dre' => $despesasImovelPai->id,
                'vinculo_dre' => $despesasAdministrativas->id
            ],
            [
                'descricao' => 'Seguro de Imóveis',
                'id_dre' => $despesasImovelPai->id,
                'vinculo_dre' => $despesasAdministrativas->id
            ],
            [
                'descricao' => 'Taxa de Lixo',
                'id_dre' => $despesasImovelPai->id,
                'vinculo_dre' => $despesasAdministrativas->id
            ],
            [
                'descricao' => 'Vigilância e Segurança Patrimonial',
                'id_dre' => $despesasImovelPai->id,
                'vinculo_dre' => $despesasAdministrativas->id
            ],
            [
                'descricao' => 'Combustíveis',
                'id_dre' => $despesasVeiculosPai->id,
                'vinculo_dre' => $despesasAdministrativas->id
            ],
            [
                'descricao' => 'Estacionamento',
                'id_dre' => $despesasVeiculosPai->id,
                'vinculo_dre' => $despesasAdministrativas->id
            ],
            [
                'descricao' => 'IPVA / DPVAT / Licenciamento',
                'id_dre' => $despesasVeiculosPai->id,
                'vinculo_dre' => $despesasAdministrativas->id
            ],
            [
                'descricao' => 'Manutenção de Veiculos',
                'id_dre' => $despesasVeiculosPai->id,
                'vinculo_dre' => $despesasAdministrativas->id
            ],
            [
                'descricao' => 'Multas de Trânsito',
                'id_dre' => $despesasVeiculosPai->id,
                'vinculo_dre' => $despesasAdministrativas->id
            ],
            [
                'descricao' => 'Pedágios',
                'id_dre' => $despesasVeiculosPai->id,
                'vinculo_dre' => $despesasAdministrativas->id
            ],
            [
                'descricao' => 'Seguros de Veículos',
                'id_dre' => $despesasVeiculosPai->id,
                'vinculo_dre' => $despesasAdministrativas->id
            ],
            [
                'descricao' => 'Antecipação de Lucros',
                'id_dre' => $despesasDiretoriaPai->id,
                'vinculo_dre' => $naoMostrar->id
            ],
            [
                'descricao' => 'Despesas Pessoais dos Sócios',
                'id_dre' => $despesasDiretoriaPai->id,
                'vinculo_dre' => $outrasDespesasNaoOperacionais->id
            ],
            [
                'descricao' => 'INSS sobre Pró-labore - GPS',
                'id_dre' => $despesasDiretoriaPai->id,
                'vinculo_dre' => $despesasAdministrativas->id
            ],
            [
                'descricao' => 'IRRF sobre Pró-labore - Darf',
                'id_dre' => $despesasDiretoriaPai->id,
                'vinculo_dre' => $despesasAdministrativas->id
            ],
            [
                'descricao' => 'Plano de Saúde Sócios',
                'id_dre' => $despesasDiretoriaPai->id,
                'vinculo_dre' => $despesasAdministrativas->id
            ],
            [
                'descricao' => 'Plano Odontológico Sócios',
                'id_dre' => $despesasDiretoriaPai->id,
                'vinculo_dre' => $despesasAdministrativas->id
            ],
            [
                'descricao' => 'Pró-labore',
                'id_dre' => $despesasDiretoriaPai->id,
                'vinculo_dre' => $despesasAdministrativas->id
            ],
            [
                'descricao' => 'Impostos sobre Aplicações',
                'id_dre' => $despesasFinanceirasPai->id,
                'vinculo_dre' => $despesasFinanceiras->id
            ],
            [
                'descricao' => 'Tarifas Bancárias',
                'id_dre' => $despesasFinanceirasPai->id,
                'vinculo_dre' => $despesasFinanceiras->id
            ],
            [
                'descricao' => 'Tarifas de Boletos',
                'id_dre' => $despesasFinanceirasPai->id,
                'vinculo_dre' => $despesasFinanceiras->id
            ],
            [
                'descricao' => 'Tarifas de Cartões de Crédito',
                'id_dre' => $despesasFinanceirasPai->id,
                'vinculo_dre' => $despesasFinanceiras->id
            ],
            [
                'descricao' => 'Tarifas DOC / TED',
                'id_dre' => $despesasFinanceirasPai->id,
                'vinculo_dre' => $despesasFinanceiras->id
            ],
            [
                'descricao' => 'Despesas a identificar',
                'id_dre' => $outrasDespesasPai->id,
                'vinculo_dre' => $outrasDespesasNaoOperacionais->id
            ],
            [
                'descricao' => 'Benfeitorias em Bens de Terceiros',
                'id_dre' => $bensImobilizadosEmpresaPai->id,
                'vinculo_dre' => $investimentoImobilizado->id
            ],
            [
                'descricao' => 'Computadores e Periféricos',
                'id_dre' => $bensImobilizadosEmpresaPai->id,
                'vinculo_dre' => $investimentoImobilizado->id
            ],
            [
                'descricao' => 'Construções em Andamento - Imóvel Próprio',
                'id_dre' => $bensImobilizadosEmpresaPai->id,
                'vinculo_dre' => $investimentoImobilizado->id
            ],
            [
                'descricao' => 'Edifícios e Construções',
                'id_dre' => $bensImobilizadosEmpresaPai->id,
                'vinculo_dre' => $investimentoImobilizado->id
            ],
            [
                'descricao' => 'Leasing - Imóveis',
                'id_dre' => $bensImobilizadosEmpresaPai->id,
                'vinculo_dre' => $investimentoImobilizado->id
            ],
            [
                'descricao' => 'Leasing - Máquinas, Equipamentos e Instalações Industriais',
                'id_dre' => $bensImobilizadosEmpresaPai->id,
                'vinculo_dre' => $investimentoImobilizado->id
            ],
            [
                'descricao' => 'Leasing - Móveis, Utensílios e Instalações Administrativos',
                'id_dre' => $bensImobilizadosEmpresaPai->id,
                'vinculo_dre' => $investimentoImobilizado->id
            ],
            [
                'descricao' => 'Leasing - Móveis, Utensílios e Instalações Comerciais',
                'id_dre' => $bensImobilizadosEmpresaPai->id,
                'vinculo_dre' => $investimentoImobilizado->id
            ],
            [
                'descricao' => 'Leasing - Outras Imobilizações',
                'id_dre' => $bensImobilizadosEmpresaPai->id,
                'vinculo_dre' => $investimentoImobilizado->id
            ],
            [
                'descricao' => 'Leasing - Veículos',
                'id_dre' => $bensImobilizadosEmpresaPai->id,
                'vinculo_dre' => $investimentoImobilizado->id
            ],
            [
                'descricao' => 'Máquinas, Equipamentos e Instalações Industriais',
                'id_dre' => $bensImobilizadosEmpresaPai->id,
                'vinculo_dre' => $investimentoImobilizado->id
            ],
            [
                'descricao' => 'Móveis, Utensílios e Instalações Administrativos',
                'id_dre' => $bensImobilizadosEmpresaPai->id,
                'vinculo_dre' => $investimentoImobilizado->id
            ],
            [
                'descricao' => 'Móveis, Utensílios e Instalações Comerciais',
                'id_dre' => $bensImobilizadosEmpresaPai->id,
                'vinculo_dre' => $investimentoImobilizado->id
            ],
            [
                'descricao' => 'Outras Imobilizações por Aquisição',
                'id_dre' => $bensImobilizadosEmpresaPai->id,
                'vinculo_dre' => $investimentoImobilizado->id
            ],
            [
                'descricao' => 'Software / Licença de Uso',
                'id_dre' => $bensImobilizadosEmpresaPai->id,
                'vinculo_dre' => $investimentoImobilizado->id
            ],
            [
                'descricao' => 'Terrenos',
                'id_dre' => $bensImobilizadosEmpresaPai->id,
                'vinculo_dre' => $investimentoImobilizado->id
            ],
            [
                'descricao' => 'Veículos',
                'id_dre' => $bensImobilizadosEmpresaPai->id,
                'vinculo_dre' => $investimentoImobilizado->id
            ],
            [
                'descricao' => 'Empréstimos de Bancos',
                'id_dre' => $emprestimosFinanciamentosPai->id,
                'vinculo_dre' => $emprestimoDividas->id
            ],
            [
                'descricao' => 'Empréstimos de Outras Instituições',
                'id_dre' => $emprestimosFinanciamentosPai->id,
                'vinculo_dre' => $emprestimoDividas->id
            ],
            [
                'descricao' => 'Empréstimos de Sócios',
                'id_dre' => $emprestimosFinanciamentosPai->id,
                'vinculo_dre' => $emprestimoDividas->id
            ],
            [
                'descricao' => 'Juros Conta Garantida',
                'id_dre' => $emprestimosFinanciamentosPai->id,
                'vinculo_dre' => $emprestimoDividas->id
            ],
            [
                'descricao' => 'Parcelamento do Simples Nacional',
                'id_dre' => $parcelamentoDividasPai->id,
                'vinculo_dre' => $emprestimoDividas->id
            ],

        ];

        foreach($categoria as $subCategoria){
            SubCategoriaDRE::create($subCategoria);
        }
    }

        public function addImpostosSobreVendaseServicos()
        {
        /*$categoria = [
            [
                'nome' =>
            ],
        ];*/
    }

    public function addDespesasComVendasServicos()
    {

    }

    public function addDre()
    {
        $categoria = [
            [
                'nome' => '3.01 Receitas de Vendas e de Serviços',
                'tipo' => 'receita',
                'codigo' => 0
            ],
            [
                'nome' => '3.02 Receitas Financeiras',
                'tipo' => 'receita',
                'codigo' => 0
            ],
            [
                'nome' => '3.03 Outras Receitas e Entradas',
                'tipo' => 'receita',
                'codigo' => 0

            ],
            [
                'nome' => '4.01 Impostos sobre Vendas e sobre Serviços',
                'tipo' => 'despesa',
                'codigo' => 0
            ],
            [
                'nome' => '4.02 Despesas com Vendas e Serviços',
                'tipo' => 'despesa',
                'codigo' => 0
            ],
            [
                'nome' => '4.03 Despesas com Salários e Encargos',
                'tipo' => 'despesa',
                'codigo' => 0
            ],
            [
                'nome' => '4.04 Despesas com Colaboradores',
                'tipo' => 'despesa',
                'codigo' => 0
            ],
            [
                'nome' => '4.05 Despesas Administrativas',
                'tipo' => 'despesa',
                'codigo' => 0
            ],
            [
                'nome' => '4.06 Despesas Comerciais',
                'tipo' => 'despesa',
                'codigo' => 0
            ],
            [
                'nome' => '4.07 Despesas com Imóvel',
                'tipo' => 'despesa',
                'codigo' => 0
            ],
            [
                'nome' => '4.08 Despesas com Veículos',
                'tipo' => 'despesa',
                'codigo' => 0
            ],
            [
                'nome' => '4.09 Despesas com Diretoria',
                'tipo' => 'despesa',
                'codigo' => 0
            ],
            [
                'nome' => '4.10 Despesas Financeiras',
                'tipo' => 'despesa',
                'codigo' => 0
            ],
            [
                'nome' => '4.11 Outras Despesas',
                'tipo' => 'despesa',
                'codigo' => 0
            ],
            [
                'nome' => '5.01 Bens Imobilizados da Empresa',
                'tipo' => 'despesa',
                'codigo' => 0
            ],
            [
                'nome' => '5.02 Empréstimos e Financiamentos',
                'tipo' => 'despesa',
                'codigo' => 0
            ],
            [
                'nome' => '5.03 Parcelamentos e Dívidas',
                'tipo' => 'despesa',
                'codigo' => '0'
            ]
        ];

        foreach($categoria as $dre){
            DRE::create($dre);
        }

    }
}
