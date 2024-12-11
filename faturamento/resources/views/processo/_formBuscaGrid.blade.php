<div id="formBuscaGrid" class="card-body-dt text-number-wt mt-2" style="{{ isset($search) && ($search == true) ? 'display: block;': 'display: none;'}}">
    <form id="formBuscar" action="{{ route("processo.busca") }}" method="GET">
        @if(isset($pendencia) && $pendencia == true)
        <input hidden value="true" name="pendencia">
        @elseif(isset($qtdeFinanceiro))
        <input hidden value="true" name="financeiro">
        @elseif(isset($qtdePago))
        <input hidden value="true" name="finalizado">
        @endif
        <div class="form-row">
            <div class="form-group col-sm-3">
                <label class="label-number" for="usuario">Usuario</label>
                <input class="input-login form-control" type="text" name="usuario">
            </div>
            <div class="form-group col-sm-2">
                <label class="label-number" for="valorTotal">Valor total</label>
                <input class="input-login form-control" type="text" name="valorTotal">
            </div>
            <div class="form-group col-sm-2">
                <label class="label-number" for="valorParcela">Valor parcela</label>
                <input class="input-login form-control" type="text" name="valorParcela">
            </div>
            <div class="form-group col-sm-2">
                <label class="label-number" for="trace_code">Codigo rastreio</label>
                <input class="input-login form-control" type="text" name="trace_code">
            </div>
            <div class="form-group col-sm-3">
                <label class="label-number" for="fornecedor">Fornecedor</label>
                <input class="input-login form-control" type="text" name="fornecedor">
            </div>
        </div>
        <div class="form-row">
            <div class="form-group col-sm-3">
                <label class="label-number" for="vencimentoInicial">Vencimento inicial</label>
                <input id="vencimentoInicial" class="input-login form-control" type="date" name="vencimentoInicial">
            </div>
            <div class="form-group col-sm-3">
                <label class="label-number" for="vencimentoFinal">Vencimento final</label>
                <input id="vencimentoFinal" class="input-login form-control" type="date" name="vencimentoFinal">
            </div>
            <div class="form-group col-sm-3">
                <label class="label-number" for="insercaoInicial">Inserção inicial</label>
                <input id="insercaoInicial" class="input-login form-control" type="date" name="insercaoInicial">
            </div>
            <div class="form-group col-sm-3">
                <label class="label-number" for="iinput-login nsercaoFinal">Inserção final</label>
                <input id="insercaoFinal" class="input-login form-control" type="date" name="insercaoFinal">
            </div>
        </div>
        <div class="form-row">
            <div class="form-group col-sm-3">
                <label class="label-number" for="centro_custo">Centro custo:</label>
                <select name="centro_custo" id="centro_custo" class="input-login form-control form-select" aria-label=".form-select">
                    <option value="0">Selecione o centro de custo</option>
                    @foreach($centrosCustos as $centroCusto)
                        <option value="{{ $centroCusto->id }}">{{ $centroCusto->nome }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group col-sm-3">
                <label class="label-number" for="rateio">Rateio:</label>
                <select name="rateio" id="rateio" class="input-login form-select form-control">
                    <option value="0">Selecione rateio</option>
                    @foreach($rateios as $rateio)
                    <option value="{{ $rateio->id}}">{{ $rateio->nome }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group col-sm-3">
                <label class="label-number" for="filial">Filial:</label>
                <select name="filial" id="filial" class="input-login form-select form-control">
                    <option value="0">Selecione a filial</option>
                    @foreach($filiais as $filial)
                        <option value="{{ $filial->id }}">{{ $filial->nome }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="form-row">
            <button class="btn btn-success btn-sm">Enviar</button>
        </div>
    </form>
    <div class="dropdown-divider"></div>
</div>

