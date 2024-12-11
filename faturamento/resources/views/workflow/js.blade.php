<script>
    $(document).ready(function() {
        $("#workflowForm").submit(function(event) {
            $('#inserirWorkflow').attr("disabled", true);
            var formData = new FormData(this);
            $.ajax({
                url: $(this).attr("action"),
                type: "POST",
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    // Lidar com a resposta do servidor, se necessário
                    window.location.href = "{{ route('workflow.index') }}"
                },
                error: function(xhr, status, error) {
                    // Habilitar o botão de envio novamente em caso de erro
                    $("#inserirWorkflow").attr("disabled", false);

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
                        $(".form-workflow-error").hide();
                        $(".form-workflow-error").html(errorMessage).addClass(
                            'alert alert-danger').show();
                    }
                }
            });

            event.preventDefault(); // Impede o envio normal do formulário
        });
    });

    var searchGrupo = "{{ route('autocomplete.grupo') }}";

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
        console.log(tagName);
        $('#grupos_hidden input[class="' + nome + '"]').remove();
        tag.remove();
    });


    $(document).ready(function() {
        @if (!empty($workflow->grupos))
            showLoader();
            var grupo_processo_id = ("{{ json_encode(explode(',', $workflow->grupos)) }}");
            grupo_json = grupo_processo_id.replace(/&quot;/g, '"');
            console.log(grupo_json);
            var grupo_obj = JSON.parse(grupo_json);
            for (var i = 0; i < grupo_obj.length; i++) {
                var id = grupo_obj[i];
                console.log(id);
                $.ajax({
                    url: "{{ route('autocomplete.grupo.byid') }}",
                    type: "POST",
                    data: {
                        id: id,
                        _token: "{{ csrf_token() }}"
                    },
                    success: function(response) {
                        console.log(response);
                        $('#grupoVal').append("<div class='input-tag'><input value='" + response
                            .nome +
                            "' readonly><span class='remove-tag'>&times;</span><input name='grupo[]' value='" +
                            response.id + "' type='hidden'></div>");

                    },
                });
            }
            hideLoader();
        @endif
    });
</script>
