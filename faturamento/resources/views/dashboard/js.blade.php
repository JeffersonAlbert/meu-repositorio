<script>
    $(document).ready(function() {
        $("#exibeFormBuscaGrid").click(function() {
            // Alterna a exibição do formulário usando animação de slide
            $("#buscarDisplay").slideToggle();
        });

        $('.period-report').click(function() {
            let period = $(this).data('period');
            console.log(period);
            let date = calcularDataRetroativa(period);
            console.log(date);
            $('#vencimentoInicial').val(date.retroDate);
            $('#vencimentoFinal').val(date.hoje);

            if ($('#formSearchRelatorioReceber').length) {
                $('#formSearchRelatorioReceber').submit();
            }

            if ($('#formRelatorioPagar').length) {
                $('#formRelatorioPagar').submit();
            }

        });

        $('#formRelatorioPagar').submit(function(e) {
            e.preventDefault();
            let formData = $('#formRelatorioPagar').serialize();

            $.ajax({
                url: "{{ route('relatorio.grid-contas-pagar') }}",
                method: "GET",
                data: formData,
                success: function(response) {
                    let gridClass = 'class="text-center td-grid-font align-middle"';
                    let grid = '';
                    console.log(response.result.data);
                    result = response.result.data;
                    let field = {
                        trace_code: {
                            type: 'string'
                        },
                        f_name: {
                            type: 'string',
                            alternative: 'dre_categoria'
                        },
                        filial_nome: {
                            type: 'string'
                        },
                        tipo_cobranca: {
                            type: 'string',
                            alternative: 'dre_categoria'
                        },
                        nome_contrato: {
                            type: 'string'
                        },
                        pvv_dtv: {
                            type: 'date'
                        },
                        vparcela: {
                            type: 'currency'
                        },
                        produto: {
                            type: 'string'
                        },
                        pago: {
                            type: 'boolean',
                            nullValue: 'em aberto',
                            trueValue: 'pago'
                        }
                    };
                    let test = gridLinkPage(response.result, field, '#id-contas-receber');
                    let paginacao = paginateNumber(response.result, 'adicionado');
                    $('div.pagination').empty().append(paginacao);
                },
                error: function(xhr, status, error) {
                    console.log(xhr.responseJSON.errors);
                }
            });
        });

        async function gridLinkPage(ajaxResult, field, gridId) {
            await alterGridNumber(ajaxResult, field, gridId);
        }

        async function handlePageLinkClick(event) {
            event.preventDefault();
            let nextPage = $(this).attr('href');
            let field = {
                trace_code: {
                    type: 'string'
                },
                f_name: {
                    type: 'string',
                    alternative: 'dre_categoria'
                },
                filial_nome: {
                    type: 'string'
                },
                tipo_cobranca: {
                    type: 'string',
                    alternative: 'dre_categoria'
                },
                nome_contrato: {
                    type: 'string'
                },
                pvv_dtv: {
                    type: 'date'
                },
                vparcela: {
                    type: 'currency'
                },
                produto: {
                    type: 'string'
                },
                pago: {
                    type: 'boolean',
                    nullValue: 'em aberto',
                    trueValue: 'pago'
                }
            };
            let response = await getNextPageNumber(nextPage, null, null, field);
            let formattedData = await alterGridNumber(response.result, field, '#id-contas-receber');
            $('.page-item').removeClass('active');
            $(this).closest('.page-item').addClass('active');
        }

        $(document).on('click', '.page-link.adicionado', handlePageLinkClick);

        $('#formSearchRelatorioReceber').submit(function(e) {
            e.preventDefault(); // Evita que o link seja seguido
            var formData = $('#formSearchRelatorioReceber').serialize();

            $.ajax({
                url: "{{ route('relatorio.grid-contas-receber') }}",
                method: "GET",
                data: formData,
                success: function(response) {
                    let gridClass = 'class="text-center td-grid-font align-middle"';
                    let grid = '';
                    $.each(response, function(field, value) {
                        let filial = value.filial_nome == null ? '' : value
                            .filial_nome;
                        let contrato = value.nome_contrato == null ? '' : value
                            .nome_contrato;
                        let produto = value.produto_nome == null ? '' : value
                            .produto_nome;
                        let status = value.status == null ? 'Em aberto' : value
                            .status;
                        grid += `<tr>
                            <td ${gridClass}>${value.trace_code}</td>
                            <td ${gridClass}>${value.nome_cliente}</td>
                            <td ${gridClass}>${filial}</td>
                            <td ${gridClass}>${value.categoria_nome}</td>
                            <td ${gridClass}>${contrato}</td>
                            <td ${gridClass}>${value.vencimento_formatado}</td>
                            <td ${gridClass}>${value.valor_vencimento}</td>
                            <td ${gridClass}>${produto}</td>
                            <td ${gridClass}>${status}</td>
                        </tr>`
                    });
                    $('#id-contas-receber').empty().append(grid);
                },
                error: function(xhr, status, error) {
                    console.log(xhr.responseJSON.errors);
                }
            });
        });

        $('#gerarRelatorio').click(function(e) {
            e.preventDefault(); // Evita que o link seja seguido

            // Serializa os dados do formulário
            if ($('#formSearchRelatorioReceber').length) {
                var formData = $('#formSearchRelatorioReceber').serialize();
                var destination = "{{ route('relatorio.contas-receber') }}";
                var relatorio = 'relatorio_contas_receber.xlsx';
            }

            if ($('#formRelatorioPagar').length) {
                var formData = $('#formRelatorioPagar').serialize();
                var destination = "{{ route('relatorio.contas-pagar') }}";
                var relatorio = 'relatorio_contas_pagar.xlsx';
            }
            console.log(destination);

            // Envia a solicitação AJAX com os dados do formulário
            $.ajax({
                url: destination,
                method: "GET", // Use o método POST
                data: formData,
                xhrFields: {
                    responseType: 'blob' // Especifica que a resposta será um blob
                },
                success: function(blob) {
                    // Cria um URL temporário para o blob
                    var url = window.URL.createObjectURL(blob);

                    // Cria um link para iniciar o download
                    var a = document.createElement('a');
                    a.href = url;
                    a.download = relatorio; // Nome do arquivo
                    document.body.appendChild(a);
                    a.click();

                    // Limpa o URL temporário
                    window.URL.revokeObjectURL(url);
                },
                error: function(xhr, status, error) {
                    console.error('Erro ao gerar relatório:', error);
                }
            });
        });

        function enviarFormulario(action, data) {
            showLoader();
            $.ajax({
                url: action,
                type: 'GET',
                data: data, // Envie os dados do formulário serializados
                processData: false, //Não processe os dados
                contentType: false, //Não defina o tipo de conteúdo
                success: function(response) {
                    // Faça algo com a resposta do servidor, se necessário
                    if (response.redirect) {
                        window.location.href = response.redirect;
                    } else {
                        tabela = tableData(response);
                        paginacao = paginateDbResult(response, aba);
                        $("#tabela-corpo").empty().append(tabela);
                        $('div.pagination').empty().append(paginacao);
                        hideLoader();
                    }

                },
                error: function(xhr, status, error) {
                    // Lide com erros de solicitação, se ne scessário
                    var errors = xhr.responseJSON.error;
                    var errorMessage = "";
                    $.each(errors, function(field, messages) {
                        errorMessage += messages.join(", ") + "<br>";
                    });
                    hideLoader();
                    $(".form-setup-error").hide();
                    $(".form-setup-error").html(`<p>${errorMessage}</p>`).addClass(
                        'alert alert-danger').show();
                }
            });
        }

        $(document).on('click', 'a.periodo', function() {
            let periodo = $('#button-pai').text();
            let dateRange = convertDateRange(periodo);
            console.log(dateRange);
            $('#vencimentoInicial').val(dateRange.startDate);
            $('#vencimentoFinal').val(dateRange.endDate);
            $('#formRelatorioPagar button#enviarBuscaRelatorio').click();
        });
    })
</script>
