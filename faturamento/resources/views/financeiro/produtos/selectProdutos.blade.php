<div class="form-group col-md-4">
    <div type="hidden" id="selected_produto"></div>
    <label class="label-number" for="search-produto">Produto:</label>
    <div class="input-group">
        <input name="id_produto" type="text" class="form-control input-login" id="search-produto" value="{{ isset($contasReceber->id_produto) ? $contasReceber->id_produto." - ".$contasReceber->produto : null }}">
        <div class="input-group-append">
            <span class="input-group-text" id="cadProduto">
                <i class="bi bi-file-plus"></i>
            </span>
        </div>
    </div>
</div>
