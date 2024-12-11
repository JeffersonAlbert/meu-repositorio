<script>
$(document).ready(function() {
    let editarCentroCusto = '';
    let formRateioCount = 1;

    $(document).ready(function() {
        $('#upload').change(function() {
            $('#file-list').empty();
            $.each(this.files, function(index, file) {
                $('#file-list').append($('<li>').text(file.name));
            });
        });
    });

    $('#cloneRateio').click(function (){
        event.preventDefault();
        showLoader();
        console.log('aqui');
        var clonedElement = $('#clonarRateio').clone();

        clonedElement.find('#cloneRateio').remove();
        clonedElement.find('.remove-on-clone').remove();

        let uniqueFormRateioId = 'formRateio' + formRateioCount;
        clonedElement.attr('id', uniqueFormRateioId);
        var buttonInsideClonedElement = clonedElement.find('button');
        buttonInsideClonedElement.attr('id', `centro_custo-rateio${formRateioCount}`);
        clonedElement.find('button').replaceWith(buttonInsideClonedElement);
        let divInsideDropDown = clonedElement.find('div#centro_custo-rateio');
        divInsideDropDown.attr('id', `centro_custo-rateio${formRateioCount}`);
        clonedElement.find('div#centro_custo-rateio').replaceWith(divInsideDropDown);
        formRateioCount++;

        $("#formCriarRateio").append(clonedElement);
        event.preventDefault();
        hideLoader();
    });

    $(document).on('click', '#inserirRateios', function (){
        event.preventDefault();
        let form = new FormData($("#cadastroRateioForm")[0]);
        let route = $("#cadastroRateioForm").attr('action');
        console.log(route);
        console.log('teste');
        $.ajax({
            url: route,
            type: "POST",
            data: form,
            processData: false,
            contentType: false,
            success: function(result){
                $('.messages-modal-cad-rateio').hide();
                $(".messages-modal-cad-rateio")
                   .html(`<p>${result.success.message}</p>`)
                   .addClass('alert alert-success')
                   .show();
                console.log(result);
                let aElement = `<a id="${result.success.id}" class="dropdown-item" href="#">
                                    <i class="bi bi-x-circle-fill text-danger deleteCentroCusto"></i>
                                    <i class="bi bi-pencil-fill text-warning editaCentroCusto"></i>
                                    ${result.success.nome}
                                </a>
                                <input id="descricao_${result.success.id}" type="hidden" value="${result.success.descricao}">
                                <div class="m-1 dropdown-divider"></div>`;
                $('.add_rateio .dropdown-rateio').append(aElement);
                for(let i = 0; i<=formRateioCount; i++){
                    $('input[name="id_centro_custo[]"]').remove();
                    if(i>0){
                        $(`#formRateio${i}`).remove();
                    }

                }
                $("button#centro_custo-rateio").text('Selecione um centro de custo');
                $('input[name="percentual_rateio[]"]').val('');
                $('#nome_rateio').val('');
                $('#modalCadastroRateio').modal('hide');
            },
            error: function(xhr, status, error){
                var error = xhr.responseJSON.errors;
                var errorMessage = "";
                $.each(error, function(field, messages) {
                    errorMessage += messages.join(", ")+"<br>";
                });
                $(".messages-modal-cad-rateio").hide();
                $(".messages-modal-cad-rateio")
                    .html(`<p>${errorMessage}</p>`)
                    .addClass('alert alert-danger')
                    .show();
            }
        });
    });

    $(document).on('click', '.dropdown-rateio a', function(e) {
        e.preventDefault();
        let idRateio = $(this).attr('id').trim();
        let textRateio = $(this).text().trim();
        console.log(`texto: ${textRateio} id: ${idRateio}`);
        $('#centro_custo-rateio').text(textRateio);
        $('input[name=id_rateio]').remove();
        $('#hidden_rateio').append(`<input name="id_rateio" value="${idRateio}" type="hidden">`);
    });

    $(document).on('click', '#addRateioCentroCusto', function () {
        console.log('estamos aqui');
        $('.messages-modal-cad-rateio').hide();
        $("#modalCadastroRateio").modal();
    });

    $('#nome_centro_custo').on("input", function() {
        let textoDigitado = $(this).val();
        if(textoDigitado.length >= 5){
            $('#inserirCentroCusto').attr('disabled', false);
        }
    });

    $(document).on('click', '#inserirCentroCusto', function() {
        let nomeCentroCusto = $('#nome_centro_custo').val();
        let descricaoCentroCusto = $('#descricao_centro_custo').val();
        let idCentroCusto = "";
        let route = "{{ route('centrocusto.store') }}";
        let data = {
            _token: "{{ csrf_token() }}",
            nome: nomeCentroCusto,
            descricao: descricaoCentroCusto
        };
        if(editarCentroCusto == true){
            console.log('aqui');
            idCentroCusto = $('#id_centro_custo_edit').val();
            let routeSemId = "{{ route('centrocusto.update', ['centrocusto' => ':id']) }}";
            route = routeSemId.replace(':id', idCentroCusto);
            data['_method'] = "PUT";
        }
        $.ajax({
            url: route,
            type: "POST",
            data: data,
            success: function(result){
               $(".mensagem-erro").hide();
               $(".mensagem-erro")
                   .html(`<p>${result.success.message}</p>`)
                   .addClass('alert alert-success')
                   .show();
               $("#modalInserirCentroCusto").modal('hide');
               if(editarCentroCusto == true){
                   console.log(idCentroCusto);
                   $(`a#${idCentroCusto}`).remove();
                   editarCentroCusto = "";
               }
               console.log(result);
               let aElement =  `<a id="${result.success.id}" class="dropdown-item" href="#">
                                    <i class="bi bi-x-circle-fill text-danger deleteCentroCusto"></i>
                                    <i class="bi bi-pencil-fill text-warning editaCentroCusto"></i>
                                    ${result.success.nome}
                                </a>
                                <input id="descricao_${result.success.id}" type="hidden" value="${result.success.descricao}">
                                <div class="m-1 dropdown-divider"></div>`;
                $('.add_centro_custo .dropdown-centro_custo').append(aElement);
            },
            error: function(xhr, status, errors){
                var error = xhr.responseJSON.errors;
                var errorMessage = "";
                $.each(error, function(field, messages) {
                    errorMessage += messages.join(", ")+"<br>";
                });
                $(".messages-modal-cad-centrocusto").hide();
                $(".messages-modal-cad-centrocusto")
                    .html(`<p>${errorMessage}</p>`)
                    .addClass('alert alert-danger')
                    .show();
                if(editarCentroCusto == true){
                    editarCentroCusto = "";
                }
            }
        })
    });

    $(document).on('click', '.dropdown-centro_custo a', function(e){
        e.preventDefault();
        $("input[name=id_centro_custo]").remove();
        let idSeletorDoCentroCusto = $(this).parent().attr('id');
        console.log("id do centro de custo: "+idSeletorDoCentroCusto);
        let selectedCentroCusto = $(this).text();
        console.log(selectedCentroCusto.trim());
        let idCentroCusto = $(this).attr('id');
        if(idSeletorDoCentroCusto) {
            console.log('aqui');
            $(`button#${idSeletorDoCentroCusto}`).text(selectedCentroCusto);
            if(idCentroCusto !== 'addRateioCentroCusto') {
               $(".hidden_centro_custo").append(`<input name="id_centro_custo[]" value="${idCentroCusto}" type="hidden">`);
            }
        } else {
            $('#centro_custo').text(selectedCentroCusto);
            $("#selected_centro_custo").append(`<input name="id_centro_custo" value="${idCentroCusto}" type="hidden">`);
        }

        $("#inserirRateios").attr('disabled', false);
    });

    $('#cadCentroCusto').click(function (){
        editarCentroCusto = "";
        $('#nome_centro_custo').val('');
        $('#descricao_centro_custo').val('');
        $('#modalInserirCentroCusto').modal();
    });

    $('#upload').on('change', function() {
        let uploadInvalido = false;
        const errorMessages = $('.mensagem-erro');
        errorMessages.empty();

        const allowedExtensions = ['.pdf', '.jpg', '.jpeg', '.png'];

        $.each(this.files, function(i, file) {
            const fileName = file.name;
            const fileExtension = fileName.slice(((fileName.lastIndexOf(".") - 1) >>> 0) + 2);

            if (!allowedExtensions.includes('.' + fileExtension.toLowerCase())) {
                errorMessages.append('<p>O arquivo ' + fileName + ' não é um arquivo de imagem ou PDF válido.</p>').addClass('alert alert-danger').show();
                uploadInvalido = true;
                $("#inserirProcesso").attr('disabled', true);
            }
        });
        if(uploadInvalido == false){
            $("#inserirProcesso").attr('disabled', false);
            errorMessages.hide();
        }
    });

    $("input[name=emissao_nota]").change(function(){
        let inputData = new Date($(this).val());
        let hoje = new Date();

        if(inputData > hoje){
            $(".mensagem-erro").html("<p>Data do pagamento não pode ser maior que a data de hoje</p>").addClass('alert alert-danger').show();
            $("#inserirProcesso").attr('disabled', true);
            console.log("diabilita o botao");
            return;
        }
        $(".mensagem-erro").hide();
        $("#inserirProcesso").attr('disabled', false);
    });

    $("input[name=telefone]").on('blur', function() {
        const phoneNumber = $(this).val();
        const formattedPhoneNumber = formatPhoneNumber(phoneNumber);
        $(this).val(formattedPhoneNumber);
    });

    $("#uploadForm").submit(function(event){
        event.preventDefault(); // Impede o envio normal do formulário
        let formData = new FormData(this);
        let tableData = [];
        $('table tbody tr').each(function(){
            let type = lcFirst($(this).find('.type-file').text());
            let row = {
                fileName: $(this).find('.name-file').text(),
                fileType: type.replace(' ', '_'),
                fileDesc: $(this).find('.desc-file').text()
            };
            tableData.push(row);
        });
        formData.append('tableData', JSON.stringify(tableData));

        enviarFormulario(formData);
    });

    $("#enviarAssimMesmo").click(function() {
        var formData = new FormData($("#uploadForm")[0]); // Use o ID correto do formulário
        formData.append("enviarSemArquivos", "1"); // Adicione o campo enviarSemArquivos
        enviarFormulario(formData);
    });

    $("#editar").on('click', function(){
        event.preventDefault();
         // Obtém todos os campos do formulário.
        var campos = $("input, select, textarea");

        // Remove o atributo "disabled" de todos os campos.
        campos.prop("disabled", false);
        $("#busca_nome").prop("disabled", true);
        $("input[name='tipo_cobranca']").remove();
        $("input[name='show_tipo_cobranca']").remove();
        var inputSelect = '<div class="input-group">'+
                                '<select name="tipo_cobranca" class="form-control form-select" aria-label=".form-select">';
        var tipo_cobranca = ("{{ json_encode($tipo_cobranca) }}");
        cleanTipoCobranca = tipo_cobranca.replace(/&quot;/g, '"');
        console.log(cleanTipoCobranca);
        var tipoCobranca = JSON.parse(cleanTipoCobranca);


        tipoCobranca.forEach(function(item){
            inputSelect +=  '<option value="' +item.id+ '">' +item.nome+ '</option>';
        });
        inputSelect += '</select></div>';
        const select = $("#select_cobranca").append(inputSelect);
        //select.html(inputSelect);
    });
});

function enviarFormulario(formData) {
    showLoader();
    $('#inserirProcesso').attr("disabled", true);

    $.ajax({
        url: $("#uploadForm").attr("action"),
        type: "POST",
        data: formData,
        processData: false,
        contentType: false,
        success: function(response) {
            // Lidar com a resposta do servidor, se necessário
            window.location.href = "{{ route('processo.index') }}";
            hideLoader();
        },
        error: function(xhr, status, error) {
            hideLoader();
            // Habilitar o botão de envio novamente em caso de erro
            $("#inserirProcesso").attr("disabled", false);

            // Exibir mensagens de erro ou fazer algo similar
            console.log(xhr.responseJSON.errors);
            var errors = xhr.responseJSON.errors;
            console.log(errors);
            if (errors) {
                if(errors.mensagem == "Sem arquivos"){
                    console.log("sem arquivos");
                     $("#fileAlertModal").modal('show');
                }
                // Por exemplo, mostrar os erros em uma div de mensagens de erro
                var errorMessage = "";
                $.each(errors, function(field, messages) {
                    errorMessage += field + ": " + messages.join(", ") + "<br>";
                });
                console.log(errorMessage);
                $(".form-processo-error").html(errorMessage).show();
            }
        }
    });
}


function formatPhoneNumber(phoneNumber){
    const cleanedPhoneNumber = phoneNumber.replace(/\D/g, '');

    if (cleanedPhoneNumber.length === 10) {
        return `(${cleanedPhoneNumber.substring(0, 3)}) ${cleanedPhoneNumber.substring(3, 6)}-${cleanedPhoneNumber.substring(6)}`;
    } else if (cleanedPhoneNumber.length === 11) {
        return `(${cleanedPhoneNumber.substring(0, 2)}) ${cleanedPhoneNumber.substring(2, 7)}-${cleanedPhoneNumber.substring(7)}`;
    } else {
        return cleanedPhoneNumber;
    }
}


// Adiciona um ouvinte de evento 'input' ao campo de entrada de quantidade de parcelas
$('#parcela').on('blur', function() {
    const date = new Date();
    var exists = document.getElementById('inserted');

    if(exists){
        document.getElementById('inserted').remove();
    }

    const datasParcelasContainer = $('#datasParcela').val();
    datasParcelasContainer.innerHTML = '';
    const quantidadeParcelas = $('#parcela').val();

    // Verifica se a quantidade de parcelas é válida
  if (!isNaN(quantidadeParcelas) && quantidadeParcelas > 0) {
    // Cria os campos de entrada para as datas das parcelas
    html = '<div id="inserted" class="form-row">';
    let valor_total = parseFloat(converterValorFormatado( $("#valor_total").val() ));
    let valor_primeira_parcela = parseFloat(converterValorFormatado( $("#valorPrimeiraParcela").val() ));
    let valor_parcela = (valor_total-valor_primeira_parcela)/(quantidadeParcelas-1);
    let data_primeira_parcela = $("#dataPrimeiraParcela").val();
    if(valor_parcela < 0){
        $(".form-processo-error").html('Parcelas estão negativas favor verificar valor total primeira parcela e qtde de parcelas').show();
        return;
    }
    console.log(valor_total-valor_primeira_parcela/(quantidadeParcelas-1));
    layoutColunas = quantidadeParcelas <= 2 ? 'col-md-6' : 'col-md-3';
    for (let i = 1; i < quantidadeParcelas; i++) {
        let newDate = new Date(data_primeira_parcela);// Cria um novo objeto Date com a mesma data que a data original
        newDate.setMonth(newDate.getMonth() + (i)); // Adiciona o número de meses correspondente ao valor de i
        newDate.setFullYear(date.getFullYear() + Math.floor((date.getMonth() + (i+1)) / 12)); // Adiciona o número de anos correspondente ao valor de i
        console.log(newDate.toISOString().substr(0,10));
        html += ' <div id="vencimento_valor" class="form-group '+layoutColunas+'">'+
                    '<input name="data'+(i)+'" type="date" class="form-control input-login" placeholder="Data parcela" value="'+newDate.toISOString().substr(0,10)+'">'+
                '</div>'+
                '<div class="form-group '+layoutColunas+'">'+
                    '<div class="input-group">'+
                        '<div class="input-group-prepend">'+
                            '<span class="input-group-text">R$</span>'+
                        '</div>'+
                        '<input name="valor'+i+'" type="text" class="form-control input-login" placeholder="Valor Parcela" value="' + formatarValor(valor_parcela) + '">'+
                    '</div>'+
                '</div>';
    }
    html += '</div>';
    $('#datasParcela').empty();
    $('#datasParcela').prepend(html);
    $('#info-adicionais').removeClass('mb-6').addClass('mb-3');

  }
});

//adiciona novos tipos de cobranca para a empresa do usuario
$("#addTipoCobranca").on('click', function(){
    $.ajax({
        url: "{{ route('tipocobranca.store') }}",
        type: "POST",
        data: {
            _token: "{{ csrf_token() }}",
            nome_cobranca: $("#nomeCobranca").val(),
        },
        success: function (response){
                $("#nomeCobranca").val('');
                var result = JSON.parse(response);
                console.log(result.success);
                if(result.success){
                    let selectElement = $('select[name="tipo_cobranca"]');
                    let optionElement = $('<option>').val(result.id).text(result.nome);
                    selectElement.append(optionElement);
                    $('#cadTipoCobranca').modal('hide');
                    $('.mensagem-erro').html(`<p>Adicionado com sucesso o tipo de cobrança ${result.nome}</p>`).addClass('alert alert-succcess').show();
                }
                if(!result.success){
                    $('.error-tipo-cobranca').hide();
                    $('.error-tipo-cobranca').html(result.message).show();
                }

        },
    });
});
//pesquisa o fornecedor com typeahead
$("#busca_nome").typeahead({
    source:  function (query, process) {
        return $.post("{{ route('autocomplete.fornecedor') }}",
            {
                term: query,
                _token:"{{ csrf_token() }}"
            },
            function (data) {
                if(data === 'nada aqui'){
                    $('.error-form').show();
                    return false;
                }
                return process(data);
        });
    },
});
// Adicione esse código para verificar se o modal está sendo exibido corretamente
$("#cadFornecedor").on("click", function() {
    console.log("Clicou no link para cadastrar fornecedor. Exibindo modal.");
});

$("#docFornecedor").on('blur', function() {
    var doc = $("#docFornecedor").val();
    var doc_replaced = doc.replace(/[./-]/g, '');
    $.ajax({
        url: "{{ route('autocomplete.fornecedor') }}",
        type: "POST",
        data: { doc: doc_replaced, _token: "{{ csrf_token() }}", id_empresa: "{{ auth()->user()->id_empresa }}" },
        success: function (response){
            $('.success-form').hide();
            $('.error-form').hide();
            if((response == false && doc_replaced.length == 14) || (response == false && doc_replaced.length == 11)){
                $('.success-form').hide();
                $('.success-form').html("<p>Esse documento não existe na base dados dos fornecedores. Pode cadastrar</p>").show();
                $('.btn-submit').removeAttr("disabled");
            }
            if((response == true && $("#docFornecedor").val().length == 14) || (response == true && $("#docFornecedor").val().length == 11)){
                $('.error-form').hide();
                $('.error-form').html("<p>Esse documento existe na base de dados dos fornecedores.</p>").show();
            }
        },
    });
});

$(document).ready(function(){
    @if(!empty($processo->grupos_ids))
    var grupo_processo_id = ("{{ json_encode(explode(',', $processo->grupos_ids)) }}");
    grupo_json = grupo_processo_id.replace(/&quot;/g, '"');
    console.log(grupo_json);
    var grupo_obj = JSON.parse(grupo_json);
    for(var i = 0; i < grupo_obj.length; i++){
        var id = grupo_obj[i];
        console.log(id);
        $.ajax({
            url: "{{ route('autocomplete.grupo.byid') }}",
            type: "POST",
            data: { id: id, _token: "{{ csrf_token() }}" },
            success: function (response){
                console.log(response);
                var nome = response.id+" - "+response.nome;
                var inputElement = $('<button>',{
                    class: "btn btn-warning",
                    value: response.id
                });
                $('#insert-button').append(inputElement);
            },
        });
    }
    @endif
});

//habilita o campo qtde parcelas de acordo com o que o cliente escolher na condicao de pagamento
$('#condicaoSelect').change(function(){
    if($(this).val() === "prazo"){
        $("#parcela").val("");
        $("#parcela").prop("disabled", false);
        $(".switch-pago").hide();
    }else{
        $("#parcela").prop("disabled", true);
        console.log($('#parcela').val());
        for(var i=0; i < $("#parcela").val(); i++){
            console.log(i+1);
            $("input[name='data" + (i+1) + "']").remove();
            $("input[name='valor" + (i+1) + "']").remove();
            $("#vencimento_valor").remove();
        }
        $(".switch-pago").show();
        $("#parcela").prop("disabled", false);
        $("#parcela").val("1");
        $("#valorPrimeiraParcela").val($("#valor_total").val());
    }
});

//formata valor valor_total
$("#valor_total").blur(function() {
    $(this).val(formatarValor($(this).val()));
});

//formata valor da data_primeira_parcela
$("#valorPrimeiraParcela").blur(function() {
    $(this).val(formatarValor($(this).val()));
});

// Função para formatar o valor
function formatarValor(num) {
    if (typeof num !== 'string') {
         num = num.toString().replace(/\./g, ',');
    }
    console.log(num);
    const numero = parseFloat(num.replace(/\./g, '').replace(',', '.'));
    console.log(numero + "float");
    if (!isNaN(numero)) {
        const valorFormatado = numero.toLocaleString('pt-BR', { minimumFractionDigits: 2 });
        return valorFormatado;
    }
}

//desformata valor que formatamos antes para poder fazer conta
function converterValorFormatado(valorFormatado) {
    return valorFormatado.replace(/\./g, '').replace(',', '.');
}
</script>
