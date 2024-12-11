<div id="formBuscaGrid" class="card-body-dt text-number-wt" style="{{ isset($search) && ($search == true) ? 'display: block;': 'display: none;'}}">
    <form id="formBuscar" action="{{ route("financeiro.busca") }}" method="GET">
        @if(isset($pendencia) && $pendencia == true)
        <input hidden value="true" name="andamento">
        @elseif(isset($qtdeFinanceiro))
        <input hidden value="true" name="pendente">
        @elseif(isset($qtdePago))
        <input hidden value="true" name="paga">
        @endif
        {{--@csrf--}}

        <div class="form-row">
            <div class="form-group col-sm-3">
                <label class="label-number" for="trace_code">Codigo rastreio</label>
                <input class="input-login form-control" type='text' name="trace_code">
            </div>
            <div class="form-group col-sm-3">
                <label class="label-number" for="usuario">Fornecedor</label>
                <input class="input-login form-control" type="text" name="fornecedor">
            </div>
            <div class="form-group col-sm-2">
                <label class="label-number" for="tipo_cobranca">Tipo Cobrança</label>
                <select name="tipo_cobranca" id="tipo_cobranca" class="input-login form-control form-select" aria-label=".form-select">
                    <option value="0">Selecione o tipo de cobranca</option>
                    @foreach($tipos_cobranca as $tipo_cobranca)
                        <option value="{{ $tipo_cobranca->id }}">{{ $tipo_cobranca->nome }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group col-sm-2">
                <label class="label-number" for="banco">Conta / Banco / Origem</label>
                <select name="banco" id="banco" class="input-login form-control form-select" aria-label=".form-select">
                    <option value="0">Selecione Banco</option>
                    @foreach($bancos as $banco)
                        <option value="{{ $banco->id }}">{{ $banco->nome }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group col-sm-2">
                <label class="label-number" for="forma_pagamento">Forma de Pagamento</label>
                <select name="forma_pagamento" id="forma_pagamento" class="input-login form-select form-control">
                    <option value="0">Selecione a forma de pagamento</option>
                @foreach($formas_pagamento as $forma_pagamento)
                    <option value="{{ $forma_pagamento->id }}">{{ $forma_pagamento->nome }}</option>
                @endforeach
                </select>
            </div>
        </div>
        <div class="form-row">
            <div class="form-group col-sm-2">
                <label class="label-number" for="centro_custo">Centro custo:</label>
                <select name="centro_custo" id="centro_custo" class="input-login form-select form-control">
                    <option value="0">Selecione centro custo</option>
                    @foreach($centrosCusto as $centroCusto)
                    <option value="{{ $centroCusto->id}}">{{ $centroCusto->nome }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group col-sm-2">
                <label class="label-number" for="rateio">Rateio:</label>
                <select name="rateio" id="rateio" class="input-login form-select form-control">
                    <option value="0">Selecione rateio</option>
                    @foreach($rateios as $rateio)
                    <option value="{{ $rateio->id}}">{{ $rateio->nome }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group col-sm-2">
                <label class="label-number" for="vencimentoInicial">Vencimento inicial:</label>
                <input id="vencimentoInicial" class="input-login form-control" type="date" name="vencimentoInicial">
            </div>
            <div class="form-group col-sm-2">
                <label class="label-number" for="vencimentoFinal">Vencimento final:</label>
                <input id="vencimentoFinal" class="input-login form-control" type="date" name="vencimentoFinal">
            </div>
            <div class="form-group col-sm-2">
                <label class="label-number" for="pagamentoInicial">Pagamento inicial:</label>
                <input id="pagamentoInicial" class="input-login form-control" type="date" name="pagamentoInicial">
            </div>
            <div class="form-group col-sm-2">
                <label class="label-number" for="pagamentoFinal">Pagamento final:</label>
                <input id="pagamentoFinal" class="input-login form-control" type="date" name="pagamentoFinal">
            </div>
        </div>
        <div class="row">
            <div class="form-group col-sm-2">
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
            <button class="btn btn-success btn-sm btn-enviar">Enviar</button>
        </div>
    </form>
    <div class="dropdown-divider"></div>
</div>

