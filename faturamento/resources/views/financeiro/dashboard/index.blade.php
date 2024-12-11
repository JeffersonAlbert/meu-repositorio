@extends('financeiro.dashboard.js')
@extends('layout.newLayout')

@section('content')
    <div class="row">
        <!-- Earnings (Monthly) Card Example -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card bg-success shadow h-100">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-white text-uppercase mb-1">
                                Faturamento (Mensal)</div>
                            <div class="h5 mb-0 font-weight-bold text-white-800 receber_mensal">R$ {{ App\Helpers\FormatUtils::formatMoney($mensalReceber) }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="bi bi-currency-dollar fa-2x text-white-300"></i>
                        </div>
                    </div>
                </div>
                <div class="card-footer text-white bg-success d-flex align-items-center justify-content-between small">
                    <a class="text-white stretched-link" href="#">Ver relatório</a>
                    <div class="text-white">
                        <i class="bi bi-chevron-right"></i>
                    </div>
                </div>
            </div>
        </div>
        <!-- Pending Requests Card Example -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card bg-danger shadow h-100">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-white text-uppercase mb-1">
                                Contas a pagar (Mensal)</div>
                            <div class="h5 mb-0 font-weight-bold text-white-800 pagar_mensal">R$ {{ App\Helpers\FormatUtils::formatMoney($mensalPagar) }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="bi bi-currency-dollar fa-2x text-white-300"></i>
                        </div>
                    </div>
                </div>
                <div class="card-footer text-white bg-danger d-flex align-items-center justify-content-between small">
                    <a class="text-white stretched-link" href="#">Ver relatório</a>
                    <div class="text-white">
                        <i class="bi bi-chevron-right"></i>
                    </div>
                </div>
            </div>
        </div>
         <!-- Earnings (Anual) Card Example -->
         <div class="col-xl-3 col-md-6 mb-4">
            <div class="card bg-success shadow h-100 py-2">
                <div class="card-body">
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
            <div class="card bg-danger border-bottom-danger shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-white text-uppercase mb-1">
                                Contas a pagar (Anual)</div>
                            <div class="h5 mb-0 font-weight-bold text-white-800">R$ {{ App\Helpers\FormatUtils::formatMoney($anualPagar) }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-comments fa-2x text-white-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
