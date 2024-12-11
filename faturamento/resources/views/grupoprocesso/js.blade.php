<script>
$(document).ready(function() {

    $('button.user-data').on('click', function(){
        console.log('clickou aqui');

        let dados = {
            id: $(this).data('id'),
        };

        $.get("{{ route('autocomplete.usuario-grupo') }}", dados, function(data){
            showLoader();
        }).done(function(data){
            console.log("deu certo");
            let tableTr = "";
            $.each(data, function(field, dados){
                tableTr +=  `<tr><td>${dados.id}</td><td>${dados.name}</td><td>${dados.last_name}</td><td>${dados.email}</td></tr>`;
            });
            $('.teste-tbody').html(tableTr);
            hideLoader();
        }).fail(function(xhr, status, error){
            console.log("deu erro");
            hideLoader();
        });
        $('#dataUsuariosGrupos').modal();
    });

    $("#grupoForm").submit(function(event){
        showLoader();
        $('#inserirGrupo').attr("disabled", true);
        var formData = new FormData(this);
        $.ajax({
            url: $(this).attr("action"),
            type: "POST",
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                // Lidar com a resposta do servidor, se necessário
                window.location.href = "{{ route('grupoprocesso.index') }}";
                hideLoader();
            },
            error: function(xhr, status, error) {
                // Habilitar o botão de envio novamente em caso de erro
                $("#inserirGrupo").attr("disabled", false);

                // Exibir mensagens de erro ou fazer algo similar
                console.log(xhr.responseJSON.errors);
                var errors = xhr.responseJSON.errors;
                console.log(errors);
                if (errors) {
                    // Por exemplo, mostrar os erros em uma div de mensagens de erro
                    var errorMessage = "";
                    $.each(errors, function(field, messages) {
                        errorMessage += field + ": " + messages.join(", ") + "<br>";
                    });
                    console.log(errorMessage);
                    $(".mensagem-erro").html(errorMessage).addClass('alert').addClass('alert-danger').show();
                }
                hideLoader();
            }
        });

        event.preventDefault(); // Impede o envio normal do formulário
    });
});
</script>
