<script>
$(document).ready(function(){
    $("#cnpj").on('blur', function(){
        doc = $(this).val().replace(/[./-]/g, '');
        console.log(doc);
        showLoader();
        $.ajax({
            url: "{{ route('autocomplete.fornecedor') }}",
            type: "POST",
            data: {
                doc: doc,
                _token: "{{ csrf_token() }}",
                id_empresa: "{{ auth()->user()->id_empresa }}",
            },
            success: function(response){
                console.log(response);
                hideLoader();
                $('.success-form').hide();
                $('.error-form').hide();
                if((response == false && doc.length == 14) || (response == false && doc.length == 11)){
                    $('.mensagem-erro').hide();
                    $('.mensagem-erro').html("<p>Esse documento não existe na base dados dos fornecedores. Pode cadastrar</p>").addClass('alert alert-success').show();
                    $('#submissao').removeAttr("disabled");
                }
                if((response == false && doc.length == 14) || (response == false && doc.length == 11)){
                    $('.mensagem-erro').hide();
                    $('.mensagem-erro').html("<p>Esse documento existe na base de dados dos fornecedores.</p>").addClass('alert alert-danger').show();
                    $("#submissao").prop("disabled", true);
                }
            }
        });
    });
});
</script>
