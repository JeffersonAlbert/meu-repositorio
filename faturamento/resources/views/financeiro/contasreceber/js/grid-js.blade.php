<script>
    $(document).ready(function() {
        $('#enviarSelecionados').toggle($('.checkCobranca:checked').length > 0);

        $(document).on('click', 'a.periodo', function() {
            let periodo = $('#button-pai').text();
            let dateRange = convertDateRange(periodo);
            console.log(dateRange);
            $('#vencimentoInicial').val(dateRange.startDate);
            $('#vencimentoFinal').val(dateRange.endDate);
            $('#formBuscar button.btn-enviar').click();
        });

        $(document).on('click', '#allCobrancas', function() {
            let checked = $(this).prop('checked');
            $('.checkCobranca').prop('checked', checked);
            $('#enviarSelecionados').toggle(checked);
        });

        //verificar se um checkbox esta marcado para exibir o botao de enviar em lote
        $(document).on('click', '.checkCobranca', function() {
            let checked = $('.checkCobranca:checked').length > 0;
            $('#enviarSelecionados').toggle(checked);
        });

        $(document).ready(function() {
            $('#enviarSelecionados').on('click', function() {
                var selectedIds = [];
                $('.checkCobranca:checked').each(function() {
                    selectedIds.push($(this).data('id'));
                });

                if (selectedIds.length > 0) {
                    $.ajax({
                        url: "{{ route('financeiro.receber-lote') }}", // Altere para a rota correta
                        method: 'POST',
                        data: {
                            ids: selectedIds,
                            _token: "{{ csrf_token() }}" // Adicione o token CSRF para segurança
                        },
                        success: function(response) {
                            // Handle the response from the server
                            console.log(response.message.success);
                            $(".billboard").html("<ul><li>" +
                                    response.message.success +
                                    "</li></ul>")
                                .addClass('alert alert-success');
                            $(".billboard").show();
                        },
                        error: function(xhr, status, error) {
                            // Handle errors
                            console.error(error);
                            alert('Ocorreu um erro ao processar o recebimento.');
                        }
                    });
                } else {
                    alert('Por favor, selecione pelo menos uma cobrança.');
                }
            });
        });
    });
</script>
