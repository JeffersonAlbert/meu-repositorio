<div class="row">
    <div class="font-regular-wt">Anexo</div>
</div>
@if(isset($contasReceber) and $contasReceber->files_types_desc !== null)
    @include('financeiro.contasreceber.formularioUpload.list')
@endif
<div class="row mt-3">
    <div class="form-group col-4">
        <label for="file-upload" class="label-number">Arquivo</label>
        <label for="file-upload" class="btn btn btn-transparent col">
            <span class="file-name">Escolha o arquivo</span>
            <i class="bi bi-paperclip"></i>
        </label>
        <input id="file-upload" type="file" name="files[]" class="d-none file-upload">
    </div>
    <div class="col-3">
        <label for="tipo_anexo" class="label-number">Tipo anexo</label>
        <select name="tipo_anexo[]" id="tipo_anexo" class="input-login form-control form-select">
            <option value="contrato">Contrato</option>
            <option value="documento_fiscal">Documento fiscal</option>
            <option value="documento_cobranca">Documento de cobrança</option>
            <option value="comprovante_pagamento">Comprovante Pagamento</option>
        </select>
    </div>
    <div class="col-4">
        <label for="descricao_arquivo" class="label-number">Descrição</label>
        <input name="descricao_arquivo[]" id="descricao_arquivo" class="input-login form-control">
    </div>
    <div class="col-1">
    <label for="add-upload" class="label-number">Novo</label>
        <button id="add-upload" class="btn btn-md btn-success d-block">
            <i class="bi bi-plus"></i>
        </button>
    </div>
</div>
<div id="upload-adicionais"></div>
