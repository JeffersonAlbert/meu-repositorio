@extends('layout.newLayout')

@push('scripts')
    @include('dashboard.graficos.grafico-historico-faturamento')
    @include('dashboard.graficos.grafico-historico-despesas')
    @include('dashboard.graficos.grafico-venda-por-cliente')
    @include('dashboard.graficos.grafico-despesas-por-fornecedor')
@endpush
@section('content')
    <div @class(['row'])>
        <div @class(['col-3'])>
            <div @class(['card'])>
                <div @class(['card-body', 'card-dashboard'])>
                    <h5 @class(['card-title',  'title-card-number'])>Faturamento Bruto {{ $periodo }}</h5>
                    <p @class(['card-text', 'text-dashboard'])>R$ {{ \App\Helpers\FormatUtils::formatMoney($faturamentoBruto) }}</p>
                    <div @class(['card-footer', 'bg-success'])>
                    </div>
                </div>
            </div>
        </div>
        <div @class(['col-3'])>
            <div @class(['card'])>
                <div @class(['card-body', 'card-dashboard'])>
                    <h5 @class(['card-title',  'title-card-number'])>Faturamento Líquido {{ $periodo }}</h5>
                    <p @class(['card-text', 'text-dashboard'])>R$ 100,00</p>
                    <div @class(['card-footer', 'bg-success'])>
                    </div>
                </div>
            </div>
        </div>
        <div @class(['col-3'])>
            <div @class(['card'])>
                <div @class(['card-body', 'card-dashboard'])>
                    <h5 @class(['card-title', 'title-card-number'])>Despesas Fixas {{ $periodo }}</h5>
                    <p @class(['card-text', 'text-dashboard'])>R$ {{ \App\Helpers\FormatUtils::formatMoney($despesasFixas) }}</p>
                    <div @class(['card-footer', 'bg-danger'])>
                    </div>
                </div>
            </div>
        </div>
        <div @class(['col-3'])>
            <div @class(['card'])>
                <div @class(['card-body', 'card-dashboard'])>
                    <h5 @class(['card-title',  'title-card-number'])>Custos Operacionais {{ $periodo }}</h5>
                    <p @class(['card-text', 'text-dashboard'])>R$ {{ \App\Helpers\FormatUtils::formatMoney($custosOperacionais) }}</p>
                    <div @class(['card-footer', 'bg-danger'])>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div @class(['row', 'mt-2'])>
        <div @class(['col-3'])>
            <div @class(['card'])>
                <div @class(['card-body', 'card-dashboard'])>
                    <h5 @class(['card-title',  'title-card-number'])>Lucro liquido {{ $periodo }}</h5>
                    <p @class(['card-text', 'text-dashboard'])>R$ 100,00</p>
                    <div @class(['card-footer', 'bg-success'])>
                    </div>
                </div>
            </div>
        </div>
        <div @class(['col-3'])>
            <div @class(['card'])>
                <div @class(['card-body', 'card-dashboard'])>
                    <h5 @class(['card-title',  'title-card-number'])>Despesas Variáveis {{ $periodo }}</h5>
                    <p @class(['card-text', 'text-dashboard'])>R$ 100,00</p>
                    <div @class(['card-footer', 'bg-danger'])>
                    </div>
                </div>
            </div>
        </div>
        <div @class(['col-3'])>
            <div @class(['card'])>
                <div @class(['card-body', 'card-dashboard'])>
                    <h5 @class(['card-title',  'title-card-number'])>Deduções {{ $periodo }}</h5>
                    <p @class(['card-text', 'text-dashboard'])>R$ {{ \App\Helpers\FormatUtils::formatMoney($deducoesImpostos) }}</p>
                    <div @class(['card-footer', 'bg-danger'])>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div @class(['row', 'mt-3'])>
        <div @class(['col-6'])>
            <div @class(['card'])>
                <div @class(['card-body', 'card-dashboard'])>
                    <h5 @class(['card-title',  'title-card-number'])>Histórico de faturamento</h5>
                    <div @class('chart-area-invoicing') style="height: 260px; width: 100%;">
                        <div id="invoiceHistory"></div>
                    </div>
                </div>
            </div>
        </div>
        <div @class(['col-6'])>
            <div @class(['card'])>
                <div @class(['card-body', 'card-dashboard'])>
                    <h5 @class(['card-title',  'title-card-number'])>Historico despesas</h5>
                    <div @class('chart-area-expenses') style="height: 260px; width: 100%;">
                        <div id="expensesHistory"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div @class(['row', 'mt-3'])>
        <div @class(['col-6'])>
            <div @class(['card'])>
                <div @class(['card-body', 'card-dashboard'])>
                    <h5 @class(['card-title',  'title-card-number'])>Venda por cliente {{ $periodo }}</h5>
                    <div @class(['chart-area-buy-per-client']) style="height: 260px; width: 100%;">
                        <div id="buyPerClient"></div>
                    </div>
                </div>
            </div>
        </div>
        <div @class(['col-6'])>
            <div @class(['card'])>
                <div @class(['card-body', 'card-dashboard'])>
                    <h5 @class(['card-title',  'title-card-number'])>Despesa por fornecedor {{ $periodo }}</h5>
                    <div @class(['chart-area-expense-by-supplier']) style="height: 260px; width: 100%;">
                        <div id="expensePerSupplier"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
