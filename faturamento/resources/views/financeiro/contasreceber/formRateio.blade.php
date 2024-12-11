<div id="formRateio" class="card-body" style="display: none;">
    <div class="form-row">
        <div class="form-group input-group col-md-4">
            <div class="dropdown add_rateio">
                <div id="hidden_rateio"></div>
                <label for="centro_custo" class="required">Selecionar Rateio:</label>
                <button id="centro_custo-rateio" name="centro_custo" class="btn dropdown-toggle col-12 btn-transparent" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    {{ isset($contasReceber->rateio) ? $contasReceber->rateio : "Selecione rateio" }}
                </button>
                <div id="centro_custo-rateio" class="dropdown-menu dropdown-rateio col-12" aria-labelledby="centro_custo" style="max-height: 200px; overflow-y: auto;">
                    <a id="addRateioCentroCusto" class="dropdown-item" href="#"><i class="bi bi-plus-circle-fill text-success"></i> Adicionar</a>
                    <div class="dropdown-divider"></div>
                    @foreach($rateios as $rateio)
                    <a id="{{ $rateio->id }}" class="dropdown-item" href="#">
                        <i class="bi bi-x-circle-fill text-danger deleteCentroCusto"></i>
                        <i class="bi bi-pencil-fill text-warning editaCentroCusto"></i>
                        {{ $rateio->nome}}
                    </a>
                    <div class="m-1 dropdown-divider"></div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>

<div id="addRateio"></div>
@include('financeiro.contasreceber.confirmaApagarRateio')
