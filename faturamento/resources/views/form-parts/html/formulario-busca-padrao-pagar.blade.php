<div id="buscarDisplay" @class(['card', 'w-100', 'mb-3']) style="display: none;">
    <div @class(['card-body-dt', 'text-number-wt'])>
        <form action="#" id="formBuscaPadrao">
            <div @class(['row'])>
                <div @class(['form-group', 'col-3'])>
                    <label for="cliente" @class(['label-number'])>Identificação</label>
                    <input class="form-control input-login" type="text" name="rastreio" id="rastreio">
                </div>
                <div @class(['form-group', 'col-3'])>
                    <label for="fornecedor" @class(['label-number'])>Fornecedor</label>
                    @include('form-parts.html.fornecedor-search')
                </div>
                <div @class(['form-group', 'col-3'])>
                    <label for="centro_custo" @class(['label-number'])>Categoria</label>
                    @include('form-parts.html.categoria-financeira-select')
                </div>
                <div @class(['form-group', 'col-3'])>
                    <label for="centro_custo" @class(['label-number'])>Centro Custo</label>
                    @include('form-parts.html.centro-custo-select')
                </div>
            </div>
            <div @class(['row'])>
                <div @class(['form-group', 'col-3'])>
                    <label for="rateio" @class(['label-number'])>Rateio</label>
                    <select name="rateio" id="rateio" class="input-login form-select form-control select-number">
                        <option value="0">Selecione o rateio entre cento custo</option>
                        @foreach($rateios as $rateio)
                            <option value="{{ $rateio->id }}">{{ $rateio->nome }}</option>
                        @endforeach
                    </select>
                </div>
                <div @class(['form-group', 'col-3'])>
                    <label for="contrato" @class(['label-number'])>Contrato</label>
                    <select name="contrato" id="contrato" class="input-login form-select form-control select-number">
                        <option value="0">Selecione o contrato</option>
                        @foreach($contratos as $contrato)
                            <option value="{{ $contrato->id }}">{{ $contrato->nome }}</option>
                        @endforeach
                    </select>
                </div>
                <div @class(['form-group', 'col-3'])>
                    <label for="produto" class="label-number">Produto</label>
                    <select name="produto" id="produto" class="input-login form-select form-control select-number">
                        <option value="0">Selecione o produto</option>
                        @foreach($produtos as $produto)
                            <option value="{{ $produto->id }}">{{ $produto->produto }}</option>
                        @endforeach
                    </select>
                </div>
                <div @class(['form-group', 'col-3'])>
                    <label for="filial" class="label-number">Filial</label>
                    <select name="filial" id="filial" class="input-login form-select form-control select-number">
                        <option value="0">Selecione a filial</option>
                        @foreach($filiais as $filial)
                            <option value="{{ $filial->id }}">{{ $filial->nome }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div @class(['row'])>
                <div class="form-group col-3">
                    <label for="vencimentoInicial" class="label-number">Vencimento inicial</label>
                    <input id="vencimentoInicial" class="form-control input-login" type="date" name="vencimentoInicial">
                </div>
                <div class="form-group col-sm-3">
                    <label for="vencimentoFinal" class="label-number">Vencimento final</label>
                    <input id="vencimentoFinal" class="form-control input-login" type="date" name="vencimentoFinal">
                </div>
                <div class="form-group col-3">
                    <label for="contratoInicial" class="label-number">Vigência contrato</label>
                    <input id="contratoInicial" class="form-control input-login" type="date" name="contratoInicial">
                </div>
                <div class="form-group col-3">
                    <label for="contratoFinal" class="label-number">Vigência contrato final</label>
                    <input id="contratoFinal" class="form-control input-login" type="date" name="contratoFinal">
                </div>
            </div>
            <div class="form-row">
                <button id='enviarBuscaRelatorio' class="btn btn-success btn-sm" class="label-number">Enviar</button>
            </div>
        </form>
    </div>
</div>
