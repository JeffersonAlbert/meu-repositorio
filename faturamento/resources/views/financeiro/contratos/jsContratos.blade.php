<script>
$(document).ready(function(){
    $("#cadContrato").click(function () {
        console.log('estamos aqui');
        $("#modalCadContrato").modal();
    });

    $('#upload_contrato').on('change', function() {
        const messages = $('.contrato-messages');
        let alertFile = false;
        messages.hide();

        const allowedExtensions = ['.pdf', '.jpg', '.jpeg', '.png'];

        $.each(this.files, function(i, file) {
            const fileName = file.name;
            const fileExtension = fileName.slice(((fileName.lastIndexOf(".") - 1) >>> 0) + 2);
            if (!allowedExtensions.includes('.' + fileExtension.toLowerCase())) {
                messages.append('<p>O arquivo ' + fileName + ' não é um arquivo de imagem ou PDF válido.</p>').addClass('alert alert-danger').show();
                alertFile = true;
            }
        });

        if(!alertFile){
            $("#enviarContrato").attr('disabled', false);
        }
    });

    $("#vigencia_final").on('change', function() {
        console.log('valida data');
        let dataInicial = $('#vigencia_inicial').val();
        let dataFinal = $('#vigencia_final').val();
        validaData(dataInicial, dataFinal);
    });

    $('#search-contrato').typeahead({
        source: function (query, process) {
            return $.post("{{ route('autocomplete.contrato-typeahead') }}", {
                term: query,
                _token: "{{ csrf_token() }}"
            },
            function (data){
                if(data == 'nada aqui'){
                    return false;
                }
                return process(data);
            });
        },
    });

    $('#search_cliente').typeahead({
        source: function (query, process) {
            return $.post("{{ route('autocomplete.cliente-typeahead') }}",
            {
                term: query,
                _token: "{{ csrf_token() }}"
            },
            function (data){
                if(data == 'nada aqui'){
                    return false;
                }
                return process(data);
            });
        },
    });

    $('#enviarContrato').click(function() {
        let data = new FormData($("#formAddContrato")[0]);
        let route = $('#formAddContrato').attr('action');
        let type = $('#formAddContrato').attr('method');
        console.log(`${route} < route ${type}`);
        $.ajax({
            url: route,
            type: type,
            data: data,
            processData: false,
            contentType: false,
            success: function(response) {
                $(".contrato-messages").hide();
                $(".contrato-messages")
                    .html(`<p>${response.success.message}</p>`)
                    .addClass('alert alert-success')
                    .show();
                $("#modalCadContrato").modal('hide');
                $('#contrato-name').val('');
                $('#cliente_contrato').val('');
                $('#vigencia_inicial').val('');
                $('#vigencia_final').val('');
                $('#upload_contrato').val('');
                $('#valor_contrato').val('');
            },
            error: function(xhr, status, error) {
                var error = xhr.responseJSON.errors;
                var errorMessage = "";
                $.each(error, function(field, messages) {
                    errorMessage += messages.join(", ")+"<br>";
                });
                $(".contrato-messages").hide();
                $(".contrato-messages")
                    .html(`<p>${errorMessage}</p>`)
                    .addClass('alert alert-danger')
                    .show();
            }
        });
    });

    function validaData(dataInicial, dataFinal){
        dataIni = new Date(dataInicial);
        dataFim = new Date(dataFinal);
        if(dataFinal < dataInicial){
            $(".contrato-messages")
                .append("<p>A data final não pode ser menor que a data inicial</p>")
                .addClass("alert alert-danger")
                .show();
        }
    }

    $("#valor_contrato").blur(function(){
        let valor = $("#valor_contrato").val();
        let formatedValor = formatarValor(valor);
        $('#valor_contrato').val(formatedValor);
    });

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

});
</script>
