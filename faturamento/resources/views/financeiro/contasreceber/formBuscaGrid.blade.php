<div id="formBuscaGrid" class="card-body-dt text-number-wt" style="{{ isset($search) && ($search == true) ? 'display: block;': 'display: none;'}}">
    <form id="formBuscar" action="{{ route("financeiro.pegar-abas") }}" method="GET">
        @if(isset($pendencia) && $pendencia == true)
        <input hidden value="true" name="pendente">
        @elseif(isset($qtdeFinanceiro))
        <input hidden value="true" name="geral">
        @elseif(isset($qtdePago))
        <input hidden value="true" name="pago">
        @endif
        {{--@csrf--}}
        <div class="form-row">
            <div class="form-group col-sm-3">
                <label class="label-number" for="cliente">Identificação</label>
                <input class="input-login form-control" type="text" name="rastreio" id="rastreio">
            </div>
            <div class="form-group col-sm-3">
                <label class="label-number" for="cliente">Cliente</label>
                <input class="input-login form-control" type="text" name="cliente" id="cliente">
            </div>
            <div class="form-group col-sm-3">
                <label class="label-number" or="categoria">Categoria</label>
                <select name="categoria" id="categoria" class="input-login form-control form-select" aria-label=".form-select">
                    <option value="0">Selecione a categoria</option>
                    @foreach($categorias as $categoria)
                        <option value="{{ $categoria->id }}">{{ $categoria->categoria }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group col-sm-3">
                <label class="label-number" for="centro_custo">Centro Custo</label>
                <select name="centro_custo" id="centro_custo" class="input-login form-control form-select" aria-label=".form-select">
                    <option value="0">Selecione o centro de custo</option>
                    @foreach($centrosCustos as $centroCusto)
                        <option value="{{ $centroCusto->id }}">{{ $centroCusto->nome }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="form-row">
            <div class="form-group col-sm-3">
                <label for="rateio" class="label-number" >Rateio</label>
                <select name="rateio" id="rateio" class="input-login form-select form-control">
                    <option value="0">Selecione o rateio entre cento custo</option>
                    @foreach($rateios as $rateio)
                        <option value="{{ $rateio->id }}">{{ $rateio->nome }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group col-sm-3">
                <label for="contrato" class="label-number" >Contrato</label>
                <select name="contrato" id="contrato" class="input-login form-select form-control">
                    <option value="0">Selecione o contrato</option>
                    @foreach($contratos as $contrato)
                        <option value="{{ $contrato->id }}">{{ $contrato->nome }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group col-sm-3">
                <label class="label-number" for="produto">Produto</label>
                <select name="produto" id="produto" class="input-login form-select form-control">
                    <option value="0">Selecione o produto</option>
                    @foreach($produtos as $produto)
                        <option value="{{ $produto->id }}">{{ $produto->produto }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group col-sm-3">
                <label class="label-number" for="filial">Filial</label>
                <select name="filial" id="filial" class="input-login form-select form-control">
                    <option value="0">Selecione a filial</option>
                    @foreach($filiais as $filial)
                        <option value="{{ $filial->id }}">{{ $filial->produto }}</option>
                    @endforeach
                </select>
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
                <label class="label-number" for="contratoInicial">Vigência contrato</label>
                <input id="contratoInicial" class="form-control input-login" type="date" name="contratoInicial">
            </div>
            <div class="form-group col-sm-3">
                <label class="label-number" for="contratoFinal">Vigência contrato final</label>
                <input id="contratoFinal" class="input-login form-control" type="date" name="contratoFinal">
            </div>
        </div>
        <div class="form-row">
            <button class="btn btn-success btn-sm btn-enviar">Enviar</button>
        </div>
    </form>
    <div class="dropdown-divider"></div>
</div>
