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
                            <div class="h5 mb-0 font-weight-bold text-white-800 receber_mensal">R$ {{ App\Helpers\FormatUtils::formatMoney($anualReceber) }}</div>
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
                            <div class="h5 mb-0 font-weight-bold text-white-800 pagar_mensal">R$ {{ App\Helpers\FormatUtils::formatMoney($mensalReceber) }}</div>
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
                            <div class="h5 mb-0 font-weight-bold text-white-800">R$ {{ App\Helpers\FormatUtils::formatMoney($anualReceber) }}</div>
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
            <div class="font-regular-wt text-processo">Relatório de contas a receber</div>
        </div>
    </div>
    <div class="row">
        <div class="col-6">
            @include('dashboard.abas')
        </div>
        <div class="col-3">
            @include('dashboard.acoesRapidas')
        </div>
        <div class="col-3">
            <button id="exibeFormBuscaGrid" class="btn btn-sm search-btn mb-3">
                <i class="bi bi-search"></i>
                Pesquisar
            </button>
            <a id="gerarRelatorio" href="{{ route('relatorio.contas-receber') }}" class="btn btn-sm btn-success btn-success-number mb-3">
                <i class="bi bi-download"></i>
                Relátorio
            </a>
        </div>
    </div>
    <div class="row mt-1">
        @include('dashboard.buscaReceber')
    </div>
    <div class="row mt-1">
        <table class="table table-striped table-responsive-sm table-head-number table-bordered">
            <thead class="head-grid-data" style="background-color: #e9e9e9">
                <th class="text-aligne-center">Identificação</th>
                <th class="text-aligne-center">Cliente</th>
                <th class="text-aligne-center">Filial</th>
                <th class="text-aligne-center">Categoria</th>
                <th class="text-aligne-center">Contrato</th>
                <th class="text-aligne-center">Vencimento</th>
                <th class="text-aligne-center">Valor</th>
                <th class="text-aligne-center">Produto</th>
                <th class="text-aligne-center">Status</th>
            </thead>
            <tbody id="id-contas-receber" class="rel-tb-claro">
                @foreach($relatorio as $rel)
                <tr>
                    <td class="text-center td-grid-font align-middle">
                        {{ $rel->trace_code }}
                    </td>
                    <td class="text-center td-grid-font align-middle">
                        {{ $rel->nome_cliente }}
                    </td>
                    <td class="text-center td-grid-font align-middle">
                        {{ $rel->filial_nome }}
                    </td>
                    <td class="text-center td-grid-font align-middle">
                        {{ $rel->categoria_nome }}
                    </td>
                    <td class="text-center td-grid-font align-middle">
                        {{ $rel->nome_contrato }}
                    </td>
                    <td class="text-center td-grid-font align-middle">
                        {{ $rel->vencimento_formatado }}
                    </td>
                    <td class="text-center td-grid-font align-middle">
                        R$ {{ App\Helpers\FormatUtils::formatMoney($rel->valor_vencimento) }}
                    </td>
                    <td class="text-center td-grid-font align-middle">
                        {{ $rel->produto_nome }}
                    </td>
                    <td class="text-center td-grid-font align-middle">
                        {{ is_null($rel->status) ? 'Aberto' : $rel->status }}
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="row ml-1">
        <div class="pagination">
            {{ $relatorio->links() }}
        </div>
    </div>
@endsection
