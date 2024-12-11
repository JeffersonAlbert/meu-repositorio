<script>
    $(document).ready(function() {

        $('#tag-input').keydown(function(event) {
            if (event.key === 'Enter' || event.key === ',') {
                event.preventDefault();
                const tagText = $(this).val().trim();
                if (tagText) {
                    createTag(tagText);
                    $(this).val('');
                }
            }
        });

        function createTag(tagText) {
            const tag = $('<span class="tag"></span>').text(tagText);
            $('#tag-list').append(tag);
        }

        $("#editarProfile").on('click', function() {
            $("#textSenha").toggle();
            $("#inputSenha").toggle();
            $("#textConfirm").toggle();
            $("#inputConfirm").toggle();
        });

        $("#enviarAlteracoesUsuario").on('click', function() {
            showLoader();
            $('#enviarAlteracoesUsuario').attr("disabled", true);
            var password = $("#inputSenha").val();
            var confirmPass = $("#inputConfirm").val();
            $.ajax({
                url: "{{ route('usuarios.update-pass') }}",
                type: "POST",
                data: {
                    password: password,
                    confirmPass: confirmPass,
                    _token: "{{ csrf_token() }}",
                    id_user: "{{ auth()->user()->id }}"
                },
                success: function(response) {
                    hideLoader();
                    $(".mensagem-erro").hide();
                    $(".mensagem-erro").html("Senha alterada com sucesso").addClass(
                        'alert alert-success').show();
                    $('#enviarAlteracoesUsuario').attr("disabled", false);
                },
                error: function(xhr, status, error) {
                    var errors = xhr.responseJSON.errors;
                    console.log(errors);
                    var errorMessage = "";
                    $.each(errors, function(field, messages) {
                        errorMessage += field + ": " + messages.join(", ") + "<br>";
                    });
                    hideLoader();
                    $(".form-user-error").hide();
                    $(".form-user-error").html(errorMessage).addClass('alert alert-danger')
                        .show();
                    $('#enviarAlteracoesUsuario').attr("disabled", false);
                }
            });
        });

        $("#uploadImgProfile").submit(function() {
            $('#enviarImg').attr("disabled", true);
            showLoader();
            var formData = new FormData(this);
            $.ajax({
                url: $(this).attr("action"),
                type: "POST",
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    // Lidar com a resposta do servidor, se necessário
                    hideLoader();
                    $("#user_photo").attr("src", response.img);
                    $('#enviarImg').attr("disabled", false);
                },
                error: function(xhr, status, error) {
                    var errors = xhr.responseJSON.errors;
                    console.log(errors);
                    $.each(errors, function(field, messages) {
                        errorMessage += field + ": " + messages.join(", ") + "<br>";
                    });
                    hideLoader();
                    $(".mensagem-erro").hide();
                    $(".mensagem-erro").html(errorMessage).addClass('alert alert-danger');
                    $("#enviarImg").attr("disabled", false);
                    $("#uploadImagemPerfil").hide();
                }
            });
            event.preventDefault();
        });

        $("#usuarioForm").submit(function(event) {
            $('#inserirUsuario').attr("disabled", true);
            var formData = new FormData(this);
            $.ajax({
                url: $(this).attr("action"),
                type: "POST",
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    // Lidar com a resposta do servidor, se necessário
                    window.location.href = "{{ route('usuarios.index') }}"
                },
                error: function(xhr, status, error) {
                    // Habilitar o botão de envio novamente em caso de erro
                    $("#inserirUsuario").attr("disabled", false);

                    // Exibir mensagens de erro ou fazer algo similar
                    console.log(xhr.responseJSON.errors);
                    var errors = xhr.responseJSON.errors;
                    console.log(errors);
                    if (errors) {
                        // Por exemplo, mostrar os erros em uma div de mensagens de erro
                        var errorMessage = "";
                        $.each(errors, function(field, messages) {
                            errorMessage += field + ": " + messages.join(", ") +
                                "<br>";
                        });
                        console.log(errorMessage);
                        $(".form-user-error").hide();
                        $(".form-user-error").html(errorMessage).addClass(
                            'alert alert-danger').show();
                    }
                }
            });

            event.preventDefault(); // Impede o envio normal do formulário
        });
    });

    var searchGrupo = "{{ route('autocomplete.grupo') }}";

    $('#olho').mousedown(function() {
        $('#password').attr("type", "text");
        $('#confirm-password').attr("type", "text");
    });

    $("#olho-confirm").mousedown(function() {
        $("#password").attr("type", "text");
        $("#confirm-password").attr("type", "text");
    });

    $('#olho').mouseup(function() {
        $("#password").attr("type", "password");
        $("#confirm-password").attr("type", "password");
    });

    $('#olho-confirm').mouseup(function() {
        $("#password").attr("type", "password");
        $("#confirm-password").attr("type", "password");
    });


    $("#olho").mouseout(function() {
        $("#password").attr("type", "password");
        $("#confirm-password").attr("type", "password");
    });
    // script javascript exibe select quando clica no textarea
    $('#grupos').on('click', function() {
        $('#grupos-select').show();
        $('#grupos-select').focus();
    });

    $('#grupos-select').typeahead({
        source: function(query, process) {
            return $.post(searchGrupo, {
                term: query,
                _token: "{{ csrf_token() }}"
            }, function(data) {
                return process(data);
            });
        },
        afterSelect: function(item) {
            var parts = item.split('-');
            var numeral = parts[0].trim();
            var nome = parts.slice(1).join('-').trim();

            $('#grupo-selecionado').tagsinput('add', item);
            $('#grupos-select').val('');

            // Atualizar os valores e nomes dos elementos das tags
            var inputElement = $('<input>', {
                type: 'hidden',
                name: 'grupo[]',
                class: nome,
                value: numeral
            });
            $('#grupos_hidden').append(inputElement);
        }
    });

    // Remover campo <input> oculto quando a tag é removida
    $(document).on('click', '[data-role="remove"]', function() {
        console.log('clickou aqui');
        var tag = $(this).closest('.tag');
        var tagName = tag.text().trim();
        var parts = tagName.split('-');
        var nome = parts.slice(1).join('-').trim();

        $('#grupos_hidden input[class="' + nome + '"]').remove();
        tag.remove();
    });

    $(document).ready(function() {
        @if (!empty($grupo_processos))
            showLoader();
            var grupo_processo_id = ("{{ $grupo_processos }}");
            grupo_json = grupo_processo_id.replace(/&quot;/g, '"');
            console.log(grupo_json);
            var grupo_obj = JSON.parse(grupo_json);
            for (var i = 0; i < grupo_obj.length; i++) {
                var nome = grupo_obj[i]['nome'];
                var numeral = grupo_obj[i]['id'];
                $('#grupo-selecionado').tagsinput('add', numeral + ' - ' + nome);

                var inputElement = $('<input>', {
                    type: 'hidden',
                    name: 'grupo[]',
                    class: nome,
                    value: numeral
                });
                $('#grupoVal').append("<div class='input-tag'><input value='" + nome +
                    "' readonly><span class='remove-tag'>&times;</span><input name='grupo[]' value='" +
                    numeral + "' type='hidden'></div>");
                //$('#grupos_hidden').append(inputElement);
            }
            hideLoader();
        @endif
    });
</script>
