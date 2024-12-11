@extends('dashboard.js')
@extends('layout.newLayout')

@section('content')
    <div class="row">
        <!-- Earnings (Monthly) Card Example -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card shadow h-100">
                <div class="card-body card-valores">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-white text-uppercase mb-1">
                                Faturamento Anual</div>
                            <div class="h5 mb-0 font-weight-bold text-white-800 receber_mensal">R$
                                {{ App\Helpers\FormatUtils::formatMoney($anualPagar) }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="bi bi-currency-dollar fa-2x text-white-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Pending Requests Card Example -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card shadow h-100">
                <div class="card-body card-valores">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-white text-uppercase mb-1">
                                Faturamento Mensal</div>
                            <div class="h5 mb-0 font-weight-bold text-white-800 pagar_mensal">R$
                                {{ App\Helpers\FormatUtils::formatMoney($mensalPagar) }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="bi bi-currency-dollar fa-2x text-white-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Earnings (Anual) Card Example -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card shadow h-100">
                <div class="card-body card-valores">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-white text-uppercase mb-1">
                                Faturamento (Anual)</div>
                            <div class="h5 mb-0 font-weight-bold text-white-800">R$
                                {{ App\Helpers\FormatUtils::formatMoney($anualReceber) }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-calendar fa-2x text-white-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Pending Requests Card Example -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card shadow h-100">
                <div class="card-body card-valores">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-white text-uppercase mb-1">
                                Contas a pagar (Anual)
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-white-800">
                                R$ {{ App\Helpers\FormatUtils::formatMoney($anualPagar) }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-comments fa-2x text-white-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row mb-3">
        <div class="col-12">
            <div class="font-regular-wt text-processo">Relatório de contas a pagar</div>
        </div>
    </div>
    <div class="row">
        <div class="col-6">
            @include('dashboard.abas')
        </div>
        <div class="col-3">
            @include('dashboard.acoesRapidas')
            {{-- <div class="row mb-3">
                <button class="period-report btn btn-novos-number btn-sm btn-back-number mr-1 btn-success" data-period="30">Últimos 30 dias</button>
                <button class="period-report btn btn-novos-number btn-sm btn-back-number mr-1 btn-success" data-period="60">Últimos 60 dias</button>
                <button class="period-report btn btn-sm btn-back-number btn-novos-number mr-1 btn-success" data-period="90">Últimos 90 dias</button>
                <button class="period-report btn btn-sm btn-back-number btn-novos-number btn-success" data-period="120">Últimos 120 dias</button>
            </div> --}}
        </div>
        <div class="col-3">
            <button id="exibeFormBuscaGrid" class="btn btn-sm btn-success btn-success-number mb-3">
                <i class="bi bi-search"></i>
                Pesquisar
            </button>
            <a id="gerarRelatorio" href="{{ route('relatorio.contas-receber') }}"
                class="btn btn-sm btn-success btn-success-number mb-3">
                <i class="bi bi-download"></i>
                Relátorio
            </a>
        </div>
    </div>
    <div class="row mt-1">
        @include('dashboard.buscaPagar')
    </div>
    <div class="row mt-1">
        <table class="table table-responsive-sm table-head-number table-bordered">
            <thead class="head-grid-data">
                <th class="text-aligne-center">Identificação</th>
                <th class="text-aligne-center">Fornecedor</th>
                <th class="text-aligne-center">Filial</th>
                <th class="text-aligne-center">Tipo cobrança</th>
                <th class="text-aligne-center">Contrato</th>
                <th class="text-aligne-center">Vencimento</th>
                <th class="text-aligne-center">Valor</th>
                <th class="text-aligne-center">Produto</th>
                <th class="text-aligne-center">Status</th>
            </thead>
            <tbody id="id-contas-receber" class="rel-tb-claro">
                @foreach ($listaProcessos as $rel)
                    <tr>
                        <td class="text-center td-grid-font align-middle">
                            {{ $rel->trace_code }}
                        </td>
                        <td class="text-center td-grid-font align-middle">
                            {{ $rel->f_name !== null ? $rel->f_name : $rel->dre_categoria }}
                        </td>
                        <td class="text-center td-grid-font align-middle">
                            {{ $rel->filial_nome }}
                        </td>
                        <td class="text-center td-grid-font align-middle">
                            {{ $rel->tipo_cobranca }}
                        </td>
                        <td class="text-center td-grid-font align-middle">
                            {{ $rel->nome_contrato }}
                        </td>
                        <td class="text-center td-grid-font align-middle">
                            {{ date('d/m/Y', strtotime($rel->pvv_dtv)) }}
                        </td>
                        <td class="text-center td-grid-font align-middle">
                            R$ {{ App\Helpers\FormatUtils::formatMoney($rel->vparcela) }}
                        </td>
                        <td class="text-center td-grid-font align-middle">
                            {{ $rel->produto }}
                        </td>
                        <td class="text-center td-grid-font align-middle">
                            {{ $rel->status ? 'Aberto' : 'Pago' }}
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div @class(['row'])>
        <div @class(['pagination'])>
            {{ $listaProcessos->links() }}
        </div>
    </div>
@endsection
