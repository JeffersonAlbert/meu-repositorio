<div id="formRepetirLancamento" class="card-body w-100" style="display: none;">
    <div class="form-row">
        <div class="form-group input-group col-md-3">
            <label for="qtde_vencimento" class="required">Quantidade de vencimentos:</label>
            <div class="input-group">
                <input id="qtde_vencimento" class="input-login form-control" value="1">
            </div>
        </div>
        <div class="form-group input-group col-md-2">
            <label form="add_vencimento">Adicionar vencimento</label>
            <div class="input-group" style="text-align: left;">
                <button id="addVencimento" class="btn btn-sm btn-success">+</button>
            </div>

        </div>
    </div>
    <div id="clonar" class="form-row">
        <div class="form-group input-group col-md-2">
            <label for="vencimento_fixo" class="required">Vencimento:</label>
            <div class="input-group">
                <input id="vencimento_fixo" name="vencimento_recorrente[]" type="date" class="input-login form-control">
            </div>
        </div>
        <div class="form-group input-group col-md-2">
            <label for="valor_fixo" class="required">Valor:</label>
            <div class="input-group">
                <div class="input-group-prepend">
                    <span class="input-group-text">R$</span>
                </div>
                <input id="valor_fixo" name="valor_recorrente[]" type="text" class="input-login form-control">
            </div>
        </div>
    </div>
    <div id="addLancamento"></div>
</div>
@include('financeiro.contasreceber.confirmaApagarLancamento')

