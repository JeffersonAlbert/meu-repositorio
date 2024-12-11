<div class="row m-3">
    <div class="col-7">
        <div class="row">
            <div class="col-6">
                <div class="card shadow h-100">
                    <div class="card-body card-valores" style="">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="row ml-3">
                                    <div class="font-regular-dt mb-1" style="font-size: 18px">
                                        Vencidos
                                    </div>
                                </div>
                                <div class="row ml-3">
                                    <div class="mb-0 font-regular-dt receber_mensal vencidos" style="font-size: 24px;">
                                        R$ {{  App\Helpers\FormatUtils::formatMoney($receberVencidos) }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-6">
                <div class="card shadow h-100">
                    <div class="card-body card-valores" style="">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="row ml-3">
                                    <div class="cor-status font-regular-dt mb-1" style="font-size: 18px">
                                        Vencem hoje
                                    </div>
                                </div>
                                <div class="row ml-3">
                                    <div class="mb-0 font-regular-dt receber_mensal" style="font-size: 24px;">
                                        R$ {{ App\Helpers\FormatUtils::formatMoney($receberDiario) }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row mt-1">
            <div class="col-6">
                <div class="card shadow h-100">
                    <div class="card-body card-valores" style="">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="row ml-3">
                                    <div class="font-regular-dt mb-1" style="font-size: 18px">
                                        A vencer
                                    </div>
                                </div>
                                <div class="row ml-3">
                                    <div class="mb-0 font-regular-dt receber_mensal a_vencer" style="font-size: 24px;">
                                        R$ {{ App\Helpers\FormatUtils::formatMoney($aVencer) }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-6">
                <div class="card shadow h-100">
                    <div class="card-body card-valores" style="">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="row ml-3">
                                    <div class="font-regular-dt mb-1" style="font-size: 18px">
                                        Pagos
                                    </div>
                                </div>
                                <div class="row ml-3">
                                    <div class="mb-0 font-regular-dt receber_mensal" style="font-size: 24px;">
                                        R$ {{ App\Helpers\FormatUtils::formatMoney($receberPago) }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-5">
        <div class="card">
            <div class="card-body card-body-wt">
                <div class="chart-area-cards">
                    <canvas id="line-bars-contas"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>
{{-- @include('financeiro.contaspagar.graficos') --}}
<!-- <div class="row m-3">
    <div class="card bg-danger shadow h-100 col-md-3 card-hide">
        <div class="card-body">
            <div class="row no-gutters align-items-center">
                <div class="col mr-2">
                    <div class="text-xs font-weight-bold text-white text-uppercase mb-1">
                        Vencidos ()</div>
                    <div class="h5 mb-0 font-weight-bold text-white-800 receber_mensal">
                        R$
                    </div>
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
    <div class="card bg-danger shadow h-100 col-md-3 card-hide">
        <div class="card-body">
            <div class="row no-gutters align-items-center">
                <div class="col mr-2">
                    <div class="text-xs font-weight-bold text-white text-uppercase mb-1">
                        Vencem hoje</div>
                    <div class="h5 mb-0 font-weight-bold text-white-800 receber_mensal">R$ </div>
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
    <div class="card bg-primary shadow h-100 col-md-3 card-hide">
        <div class="card-body">
            <div class="row no-gutters align-items-center">
                <div class="col mr-2">
                    <div class="text-xs font-weight-bold text-white text-uppercase mb-1">
                        A vencer ()</div>
                    <div class="h5 mb-0 font-weight-bold text-white-800 receber_mensal">R$ </div>
                </div>
                <div class="col-auto">
                    <i class="bi bi-currency-dollar fa-2x text-white-300"></i>
                </div>
            </div>
        </div>
        <div class="card-footer text-white bg-primary d-flex align-items-center justify-content-between small">
            <a class="text-white stretched-link" href="#">Ver relatório</a>
            <div class="text-white">
                <i class="bi bi-chevron-right"></i>
            </div>
        </div>
    </div>
    <div class="card bg-success shadow h-100 col-md-3 card-hide">
        <div class="card-body">
            <div class="row no-gutters align-items-center">
                <div class="col mr-2">
                    <div class="text-xs font-weight-bold text-white text-uppercase mb-1">
                        Pagos ()</div>
                    <div class="h5 mb-0 font-weight-bold text-white-800 receber_mensal">R$ </div>
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
</div> -->
