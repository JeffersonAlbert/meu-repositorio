<script>
    $(document).ready(function(){
        $('.page-item.page-link.all').text('Anterior')
    });

    $('#datepicker-month-year').datepicker({
        format: 'mm/yyyy',
        viewMode: "months",
        minViewMode: "months",
        autoclose: true,
    });

    $('.date-picker').datepicker({
        format: 'dd/mm/yyyy',
        viewMode: 'months',
        minViewMode: 'months',
        autoclose: true,
    });

    $('#exibeFormBuscaGrid').click(function(){
        $('#buscarDisplay').toggle();
    });

$(document).ready(function(){
    $(".grid-lines").click(function(){
        console.log($(this).attr("data-value"));
        let url = "{{ route('usuarios.grid-lines') }}";
        let gridLines = $(this).attr("data-value")
        $.post(url, {
            _token:  "{{ csrf_token() }}",
            lines: gridLines,
        }, function(response){
            console.log(response);
            location.reload();
        });
    });
});

function sendFormPost(action, data){
    showLoader();
    $.ajax({
        url: action,
        type: 'POST',
        data: data, // Envie os dados do formulário serializados
        processData: false, // Não processe os dados
        contentType: false, // Não defina o tipo de conteúdo
        success: function(response) {
            // Faça algo com a resposta do servidor, se necessário
            if(response.redirect){
                window.location.href = response.redirect;
            }else{
                $(".form-setup-error").hide();
                $(".form-setup-error").html("<p>Enviado com sucesso</p>").addClass('alert alert-success').show();
                hideLoader();
            }

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

function sendFormGet(action, data){
    showLoader();
    console.log(action);
    $.ajax({
        url: action,
        type: 'GET',
        data: data, // Envie os dados do formulário serializados
        processData: false, // Não processe os dados
        contentType: false, // Não defina o tipo de conteúdo
        success: function(response) {
            // Faça algo com a resposta do servidor, se necessário
            if(response.redirect){
                window.location.href = response.redirect;
            }else{
                $(".form-setup-error").hide();
                $(".form-setup-error").html("<p>Enviado com sucesso</p>").addClass('alert alert-success').show();
                hideLoader();
            }

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
</script>
