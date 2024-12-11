<script>
// Adiciona um ouvinte de evento 'input' ao campo de entrada de quantidade de parcelas
$('#cnpj').on('blur', function() {
    console.log('aqui');
    var path = "{{ route('autocomplete.filial') }}";
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
            if(data.estabelecimento.tipo == 'Matriz'){
                var mensagemErro = '<div class="alert alert-danger"><p>Esse cnpj é de matriz, favor cadastrar como empresa matriz.</p></div>';
                $('.mensagem-erro').html(mensagemErro).hide();
                $('.mensagem-erro').html(mensagemErro).show();
                $('#btn-submit').prop('disabled', true);
            }
            if(data.result == 'error'){
                console.log(data.message);
                var mensagemErro = '<div class="alert alert-danger"><p>' + data.message + '</p></div>';
                $('.mensagem-erro').html(mensagemErro).hide();
                $('.mensagem-erro').html(mensagemErro).show();
                $('#btn-submit').prop('disabled', true);
            }
            if(filial == 'Filial' && error == null ){
                $('[name="nome"]').val(data.estabelecimento.nome_fantasia);
                $('[name="razao_social"]').val(data.estabelecimento.razao_social);
                $('[name="endereco"]').val(data.estabelecimento.tipo_logradouro+" "+data.estabelecimento.logradouro);
                $('[name="numero"]').val(data.estabelecimento.numero);
                $('[name="complemento"]').val(data.estabelecimento.complemento);
                $('[name="bairro"]').val(data.estabelecimento.bairro);
                $('[name="cep"]').val(data.estabelecimento.cep);
                $('[name="cidade"]').val(data.estabelecimento.cidade.nome);
                $('#btn-submit').prop('disabled', false);
            }
        }
    });
});
</script>
