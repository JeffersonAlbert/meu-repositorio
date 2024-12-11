<script>
    $(document).ready(function(){

        $('.input-codigo').keyup(function() {
            if ($(this).val().length == $(this).attr('maxlength')) {
                $(this).parent().next().find('.input-codigo').focus();
            }
        });

        $('#registrationForm').submit(function () {
            $('#sendRegistro').attr("disabled", true);
            var formData = new FormData(this);
            $.ajax({
                url: $(this).attr("action"),
                type: "POST",
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    console.log(response);
                    let route = "{{ route('post-registration', ['email' => ':email']) }}";
                    window.location.href = route.replace(':email', response.success.email);
                },
                error: function(xhr, status, error) {
                    console.log(xhr.responseJSON.errors);
                    var errors = xhr.responseJSON.errors;
                    console.log(errors);
                    if (errors) {
                        // Por exemplo, mostrar os erros em uma div de mensagens de erro
                        var errorMessage = "";
                        $.each(errors, function(field, messages) {
                            errorMessage += messages.join(", ") + "<br>";
                        });
                        console.log(errorMessage);
                        $("#registration-error").hide();
                        $("#registration-error").html(errorMessage).addClass('alert alert-danger').show();
                        $('#sendRegistro').attr("disabled", false);
                    }
                }
            });
            event.preventDefault();
        });

        $('#cnpj').on('input', function () {
            var cnpj = $(this).val().replace(/[^\d]/g, '');

            $.ajax({
                url: "{{ route('autocomplete.consulta-cnpj') }}",
                dataType: 'json',
                type: 'POST', // Definir o método POST
                data: {
                    _token: "{{ csrf_token() }}",
                    cnpj: cnpj // O CNPJ digitado pelo usuário
                },
                success: function(data) {
                    $('input[name="razao_social"]').val(data.razao_social);
                    $('input[name="cep"]').val(data.estabelecimento.cep);
                    $('input[name="logradouro"]').val(data.estabelecimento.logradouro);
                    $('input[name="cidade"]').val(data.estabelecimento.cidade.nome);
                    $('input[name="bairro"]').val(data.estabelecimento.bairro);
                }
            });

            if (cnpj.length === 14) {
                if (isValidCnpj(cnpj)) {
                    // CNPJ válido
                } else {
                    // CNPJ inválido
                    $('#registration-msg').html('CNPJ inválido').css('display','block');
                }
            }

            $(this).val(formatCnpj(cnpj));

        });

        $('#cep').on('input', function () {
            var cep = $(this).val().replace(/\D/g, '');
            console.log(cep);

            buscarEnderecoPorCEP(cep, function (data) {
                if (data) {
                    for (let chave in data) {
                        if (Object.hasOwnProperty.call(data, chave)) {
                            let valor = data[chave];
                            console.log(`aa ${chave}: ${valor}`);
                            $(`#${chave}`).val(`${valor}`);
                        }
                    }
                }
                console.log(data);
            });
        });

        $('.input-login').on('input', function () {
            // Adiciona a classe "filled" se o valor do input não estiver vazio
            $(this).toggleClass('input-login-filled', $(this).val().length > 0);
        });

        function formatCnpj(cnpj) {
            return cnpj.replace(/(\d{2})(\d{3})(\d{3})(\d{4})(\d{2})/, '$1.$2.$3/$4-$5');
        }

        function isValidCnpj(cnpj) {
            var tamanho = cnpj.length - 2;
            var numeros = cnpj.substring(0, tamanho);
            var digitos = cnpj.substring(tamanho);
            var soma = 0;
            var pos = tamanho - 7;

            for (var i = tamanho; i >= 1; i--) {
                soma += numeros.charAt(tamanho - i) * pos--;
                if (pos < 2) pos = 9;
            }

            var resultado = soma % 11 < 2 ? 0 : 11 - soma % 11;
            if (resultado != digitos.charAt(0)) return false;

            tamanho = tamanho + 1;
            numeros = cnpj.substring(0, tamanho);
            soma = 0;
            pos = tamanho - 7;

            for (var i = tamanho; i >= 1; i--) {
                soma += numeros.charAt(tamanho - i) * pos--;
                if (pos < 2) pos = 9;
            }

            resultado = soma % 11 < 2 ? 0 : 11 - soma % 11;
            return resultado == digitos.charAt(1);
            return true; // Exemplo simples, substitua pela lógica real
        }

    });
</script>
