<script>
// Adiciona um ouvinte de evento 'input' ao campo de entrada de quantidade de parcelas
$('#cnpj').on('blur', function() {
    console.log('aqui');
    var path = "{{ route('autocomplete.empresa') }}";
    showLoader();
    $.ajax({
        type: 'POST',
        url: path,
        data: {
            _token: "{{ csrf_token() }}",
            cnpj: $('#cnpj').val(),
        },
        enctype: 'multipart/form-data',
        dataType: "Json",
        success: function(data){
            var filial = data.estabelecimento.tipo;
            var error = data.result == 'error' ? 'error' : null;
            console.log(data);
            if(data.estabelecimento.tipo == 'Filial'){
                var mensagemErro = '<div class="alert alert-danger"><p>Esse cnpj é de filial, favor cadatrar primeiro a empresa matriz.</p></div>';
                $('.mensagem-erro').html(mensagemErro).hide();
                $('.mensagem-erro').html(mensagemErro).show();
            }
            if(data.result == 'error'){
                console.log(data.message);
                var mensagemErro = '<div class="alert alert-danger"><p>' + data.message + '</p></div>';
                $('.mensagem-erro').html(mensagemErro).hide();
                $('.mensagem-erro').html(mensagemErro).show();
            }
            if(filial == 'Matriz' && error == null ){
                //$('.mensagem-erro').hide();
                $('[name="nome"]').val(data.estabelecimento.nome_fantasia);
                $('[name="razao_social"]').val(data.razao_social);
                $('[name="endereco"]').val(data.estabelecimento.tipo_logradouro+" "+data.estabelecimento.logradouro);
                $('[name="numero"]').val(data.estabelecimento.numero);
                $('[name="complemento"]').val(data.estabelecimento.complemento);
                $('[name="bairro"]').val(data.estabelecimento.bairro);
                $('[name="cep"]').val(data.estabelecimento.cep);
                $('[name="cidade"]').val(data.estabelecimento.cidade.nome);
            }
            hideLoader();
        },
        error: function(xhr, status, error) {
            var errors = xhr.responseJSON.errors;
            console.log(errors);
            let errorMessage = "";
             $.each(errors, function(field, messages) {
                errorMessage += field + ": " + messages.join(", ") + "<br>";
            });
            hideLoader();
            $(".mensagem-erro").hide();
            $(".mensagem-erro").html(errorMessage).addClass('alert alert-danger').show();
        }
    });
});
</script>

