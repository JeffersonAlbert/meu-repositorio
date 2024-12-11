<div class="row m-3">
    <div class="col-7">
        <div class="row">
            <div class="col-6">
                <div class="card h-100">
                    <div class="card-body card-valores" style="">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="row ml-3">
                                    <div id="vencidos-cabecalho" class="font-regular-dt  mb-1" style="font-size: 18px">
                                        Vencidos
                                    </div>
                                </div>
                                <div class="row ml-3">
                                    <div id='vencidos-valor' class="mb-0 font-regular-dt receber_mensal"
                                        style="font-size: 24px;">
                                        R$ {{ App\Helpers\FormatUtils::formatMoney($vencidas) }}
                                        <a href="#"
                                            class="contasPagarVencidosList font-regular-dt link-number">Vizualizar</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-6">
                <div class="card h-100">
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
                                        R$ {{ App\Helpers\FormatUtils::formatMoney($pagarDia) }}
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
                <div class="card h-100">
                    <div class="card-body card-valores" style="">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="row ml-3">
                                    <div class="font-regular-dt mb-1" style="font-size: 18px">
                                        A vencer
                                    </div>
                                </div>
                                <div class="row ml-3">
                                    <div class="mb-0 font-regular-dt receber_mensal" style="font-size: 24px;">
                                        R$ {{ App\Helpers\FormatUtils::formatMoney($aVencer) }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-6">
                <div class="card h-100">
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
                                        R$ {{ App\Helpers\FormatUtils::formatMoney($contasPagasMes) }}
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
@include('financeiro.contaspagar.graficos')
