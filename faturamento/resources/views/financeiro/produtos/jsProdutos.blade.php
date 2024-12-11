<script>
    $(document).ready(function(){
        $('#cadProduto').click(function() {
            console.log('aqui');
            $('#modalCadProduto').modal();
        });

        $('#search-produto').typeahead({
            source: function (query, process) {
                return $.post("{{ route('autocomplete.produto-typeahead') }}",
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

        $('#codigo_produto').blur(function() {
            let nome = $('#produto-name').val();
            let codigo = $('#codigo_produto').val();
            if(nome.trim() !== '' && codigo.trim() !== ''){
                $('#enviarProdutos').attr('disabled', false);
            }
        });

        $("input[type='radio']").on("change", function() {
            let nome = $(this).attr("name"); // Obtém o valor do atributo 'name'
            let valor = $(this).val(); // Obtém o valor do atributo 'value'
            let tipo = $(this).attr('id');
            console.log(tipo);
            if(tipo == 'radio_servico'){
                $("#produto-ean").val('');
                $("#produto-ean").attr('disabled', true);
            }else{
                $("#produto-ean").attr('disabled', false);
            }
        });

        $('#enviarProdutos').click(function() {
            console.log('aqui');
            showLoader();
            let data = new FormData($('#formAddProduto')[0]);
            let route = $('#formAddProduto').attr('action');
            let type = $('#formAddProduto').attr('method');
            $.ajax({
                url: route,
                type: type,
                data: data,
                processData: false,
                contentType: false,
                success: function(response) {
                    $('.produto-messages').hide();
                    $('.produto-messages')
                        .html(`Produto cadastrado com sucesso`)
                        .addClass('alert alert-success')
                        .show();
                    $('#modalCadProduto').modal('hide');
                    hideLoader();
                },
                error: function(xhr, status, error) {
                    var error = xhr.responseJSON.errors;
                    var errorMessage = "";
                    $.each(error, function(field, messages){
                        errorMessage += messages.join(", ")+"<br>";
                    });
                    console.log(errorMessage);
                    $(".produto-messages").hide();
                    $(".produto-messages")
                        .html(`<p>${errorMessage}</p>`)
                        .addClass('alert alert-danger')
                        .show();
                    hideLoader();
                }
            });
        });
    });
</script>