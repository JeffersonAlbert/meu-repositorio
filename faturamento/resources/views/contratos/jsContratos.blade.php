<script>
    $(document).ready(function() {
        $('#formAddContrato').submit(function(event) {
            event.preventDefault();
            showLoader();
            let data = new FormData(this);
            let route = $(this).attr('action');
            let type = $(this).attr('method');
            $.ajax({
                url: route,
                type: type,
                data: data,
                processData: false,
                contentType: false,
                success: function(response) {
                    hideLoader();
                    console.log(response);
                    index = response.success.redirect;
                    console.log(index);
                    window.location.href = index;
                },
                error: function(xhr, status, errors) {
                    hideLoader();
                    var e = xhr.responseJSON.errors;
                    console.log(e);
                    var errorMessage = "";
                    $.each(e, function(field, messages){
                        errorMessage +=  messages.join(", ") + "<br>";
                    });
                    console.log(errorMessage);
                    $(".contrato-messages").html(`<p>${errorMessage}</p>`).addClass('alert alert-danger').show();
                },
            });
        });

        $('#upload_contrato').click(function() {
            $('#modalFiles').modal();
        });

        $('.baixar-arquivo').click(function(){
            let arquivo = $(this).data('file');
            console.log(arquivo);
            showLoader();
            $.ajax({
                url: "{{ route('contrato.baixar-arquivo') }}",
                type: "POST",
                data: {
                    _token: "{{ csrf_token() }}",
                    file: arquivo
                } ,
                xhrFields: {
                    responseType: 'blob' // Define o tipo de resposta como blob (arquivo)
                },
                success: function(result){
                    hideLoader();
                    var a = document.createElement('a');
                    var blob = new Blob([result], { type: 'application/octet-stream' });
                    var url = window.URL.createObjectURL(blob);
                    a.href = url;
                    a.download = arquivo; // Substitua pelo nome real do arquivo
                    document.body.appendChild(a);
                    a.click();
                    window.URL.revokeObjectURL(url);
                },
                error: function(xhr, status, errors) {
                    hideLoader();
                   $(".messages-arquivos").html(`<p>Não foi possivel baixar o aquivo!</p>`).addClass('alert alert-danger').show();
                },
            });
        });

        //$('#search_cliente').typeahead({
        //    source: function (query, process) {
        //        return $.post("{{ route('autocomplete.cliente-typeahead') }}",
        //        {
        //            term: query,
        //            _token: "{{ csrf_token() }}"
        //        },
        //        function (data){
        //            if(data == 'nada aqui'){
        //                return false;
        //            }
        //            return process(data);
        //        });
        //    },
        //});

        $('.open-modal').click(function() {
            let eId = $(this).attr('id');
            $(`#modal-${eId}`).modal();
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
    });
</script>