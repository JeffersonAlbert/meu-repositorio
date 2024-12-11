<script>
function enviarFormularios(action, data){
    console.log(data);
    showLoader();
    $.ajax({
        url: action,
        type: 'POST',
        data: data, // Envie os dados do formulário serializados
        processData: false, // Não processe os dados
        contentType: false, // Não defina o tipo de conteúdo
        success: function(response) {
            // Faça algo com a resposta do servidor, se necessário
            console.log("Sucesso: ", response);
            $(".form-setup-error").hide();
            $(".form-setup-error").html("<p>Enviado com sucesso</p>").addClass('alert alert-success').show();
            hideLoader();
            window.location.reload()
        },
        error: function(xhr, status, error) {
            // Lide com erros de solicitação, se ne scessário
            var errors = xhr.responseJSON.error;
            var errorMessage = "";
            $.each(errors, function(field, messages) {
                errorMessage +=  messages.join(", ") + "<br>";
            });
            hideLoader();
            $(".form-setup-error").hide();
            $(".form-setup-error").html(`<p>${errorMessage}</p>`).addClass('alert alert-danger').show();
        }
    });
}

$(document).ready(function(){
    $('#enviarSetup').on('click', function(){
        let form = new FormData(document.getElementById('setupForm')); // Use document.getElementById para obter o formulário
        let action = $("#setupForm").attr("action");
        enviarFormularios(action, new FormData(document.getElementById('setupForm')));
    });
});
</script>
