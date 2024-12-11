<script>
let count = 1;
let fileRemove = null;
let row = null;
$("#switch-imposto").on('change', function(){
    if($(this).prop('checked')){
        $('#busca_nome').attr('disabled',true);
        console.log('desliga o cadastro do fornecedor');
    }else{
        $('#busca_nome').attr('disabled', false);
        console.log('desliga o cadastro do fornecedor');
    }
});

$(document).on('click', 'button.removerArquivo ', function(e){
    e.preventDefault();
    // Encontra a linha pai
    row = $(this).closest('tr');

    // Captura os dados da linha
    var fileName = row.find('.name-file').text();
    var fileType = lcFirst(row.find('.type-file').text());
    var fileDesc = row.find('.desc-file').text();
    var id = $(this).data('id');

    // Dados a serem enviados ao servidor
    fileRemove = {
        id: id,
        fileName: fileName,
        fileType: fileType.replace(' ', '_'),
        fileDesc: fileDesc
    };
    console.log(fileRemove);
    $('#modalRemoveArquivo').modal();
    $('.arquivo-texto').text(`Tem certeza que quer remover o arquivo: ${fileName}`);
});

$(document).on('click', 'button#confirmaRemoverArquivo', function(e){
    let route = "{{ route('processo.destroyFile') }}"
    $.ajax({
        url: route,
        type: "GET",
        data: fileRemove,
        success: function(result){
            console.log(result);
            if(result.success == 'success'){
                row.remove();
            }
        },
        error: function(xhr, status, error){
            console.log(xhr.responseJSON.errors);
        }
    });
});

$(document).on('change', '.row .file-upload', function(){
    var fileName = $(this).val().split('\\').pop();
    var fileSize = this.files[0].size;
    var fileSizeFormatted = formatBytes(fileSize);
    $(this).closest('.row').find('.file-name').text(fileName + ' (' + fileSizeFormatted + ')');
});

$(document).on('click', '.row #rm-upload', function(e){
    e.preventDefault();
    $(this).closest('.row').remove();
});

$(document).on('click', '#add-upload',function(e){
    e.preventDefault();
    console.log('clickou para adicionar upload');
    let newUpload =`<div class="row mt-3">
                            <div class="form-group col-4">
                                <label for="file-upload${count}" class="label-number">Arquivo</label>
                                <label for="file-upload${count}" class="btn btn btn-transparent col">
                                    <span class="file-name">Escolha o arquivo</span>
                                    <i class="bi bi-paperclip"></i>
                                </label>
                                <input id="file-upload${count}" type="file" name="files[]" class="d-none file-upload">
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
                            <label for="rm-upload" class="label-number">Remove</label>
                                <button id="rm-upload" class="btn btn-md btn-success d-block">
                                    -
                                </button>
                            </div>
                        </div>`;
    $('#upload-adicionais').append(newUpload);
    count = count+1;
});
</script>
