<div class="form-group col-md-4">
    <div type="hidden" id="selected_contrato"></div>
    <label class="label-number" for="search-contrato">Contrato:</label>
    <div class="input-group">
        <input name="id_contrato" type="text" class="form-control input-login" id="search-contrato" value="{{ isset($contasReceber->id_contrato) ? $contasReceber->id_contrato." - ".$contasReceber->nome_contrato :  null }}">
        <div class="input-group-append">
            <span class="input-group-text" id="cadContrato">
                <i class="bi bi-file-plus"></i>
            </span>
        </div>
    </div>
</div>

