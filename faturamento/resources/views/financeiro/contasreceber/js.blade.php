<script>
    $(document).ready(function() {
        let formRateioCount = 1;
        let formRepetirLancamentoCount = 1;
        let clienteExists = '';
        let editarCentroCusto = '';
        let editarCategoria = '';
        let valorDivergente = false;
        let idReceber = '';
        let aba = '';

        $("#data-valor-recebido").change(function() {
            let inputData = new Date($(this).val());
            let hoje = new Date();

            if (inputData > hoje) {
                $("#message-receber").html(
                    "<p>Data do pagamento não pode ser maior que a data de hoje</p>").addClass(
                    'alert alert-danger').show();
                $("#receber").attr('disabled', true);
                console.log("diabilita o botao");
                return;
            }
            $("#message-receber").hide();
            $("#receber").attr('disabled', false);
        });

        $('#formBuscar').submit(function() {
            event.preventDefault();
            let formData = $(this).serialize() + "&tipo=" + aba;
            let route = $(this).attr('action');
            var pagination = $('div.pagination');
            $.ajax({
                url: route,
                type: 'GET',
                data: formData,
                beforeSend: function() {
                    showLoader();
                },
                success: function(response) {
                    hideLoader();
                    tabela = tableData(response.result)
                    $("#table-body").empty().append(tabela);
                    var paginacao = paginateDbResult(response.result, aba);
                    $('.vencidos').text(formatCurrency(response.vencidos));
                    $('.a_vencer').text(formatCurrency(response.a_vencer));
                    pagination.html(paginacao);
                    hideLoader();
                    // Faça algo com os dados de resposta (data)
                },
                error: function(xhr, status, error) {
                    hideLoader();
                    $(".mensagem-erro")
                        .html(`<p>Houve algum erro favor informar o administrador</p>`)
                        .addClass('alert alert-danger')
                        .show();
                    // Faça algo com o erro
                }
            });
        });

        $("#exibeFormBuscaGrid").click(function() {
            // Alterna a exibição do formulário usando animação de slide
            $("#formBuscaGrid").slideToggle();
        });

        $('.aba-switch').click(function() {
            event.preventDefault();
            // Remove a classe active de todos os links
            $('.aba-switch').removeClass('active');

            // Adiciona a classe active apenas ao link clicado
            $(this).addClass('active');
            let id = $(this).attr('id');
            aba = id;
            let route = $(this).attr('href');
            switchTabs(id, route);
        });

        $(document).on('click', '.page-link[data-tipo]', function() {
            event.preventDefault();
            let nextpage = $(this).attr('href');
            let tipo = $(this).data('tipo');
            getPaginate(nextpage, tipo);
        });

        function getPaginate(nextpage, tipo) {
            showLoader();
            event.preventDefault();

            var pagination = $('div.pagination');
            pagination.empty();
            $.ajax({
                url: nextpage,
                type: 'GET',
                data: {
                    tipo: tipo,
                },
                success: function(response) {
                    tabela = tableData(response)
                    $("#table-body").empty().append(tabela);

                    var paginacao = paginateDbResult(response, tipo);
                    pagination.append(paginacao);
                    hideLoader();
                },
                error: function(xhr, status, error) {
                    console.error(error);
                    hideLoader();
                }
            });
        }

        function paginateDbResult(dbResult, tipo) {
            var paginacao = `<nav>` +
                `<ul class="pagination">`;
            $.each(dbResult.links, function(index, links) {
                var active = links.active == true ? "active" : "";
                paginacao += `<li class="page-item ${active}">` +
                    `<a class="page-link ${tipo}" data-tipo="${tipo}" href="${links.url}">${links.label}</a>` +
                    `</li>`;
            });
            paginacao += '</ul></nav>';
            return paginacao.replace('Previous', '').replace('Next', '');
        }


        function switchTabs(tipo, route) {
            showLoader();
            var tabelaCorpo = $('#table-body');
            var paginate = $('div.pagination');
            tabelaCorpo.empty(); // Remove todas as linhas da tabela
            paginate.empty();

            $.ajax({
                url: route,
                type: "GET",
                data: {
                    tipo: tipo,
                },
                success: function(response) {
                    result = response.result;
                    tabela = tableData(result);
                    tabelaCorpo.append(tabela);
                    var paginacao = paginateDbResult(response, tipo);
                    paginate.append(paginacao);
                    hideLoader();
                },
            });
        }

        function tableData(dbResult) {
            let tabela = "";
            let route = "{{ route('financeiro.edit', ['financeiro' => ':id']) }}";
            $.each(dbResult.data, function(index, value) {
                let textClass = 'class="td-grid-font align-middle"';
                let textTraceCode = 'class="background-trace-code align-middle"';
                let status = value.status == null ? "Em aberto" : value.status;
                let checkBoxClass = status == "Pago" ? "" : "checkCobranca";
                let checkBoxDisabled = status == "Pago" ? "disabled" : "";
                let strAcao = status == "Em aberto" ? "Receber" : "Vizualizar";
                let contrato = value.contrato == null ? "Sem contrato" : value.contrato;
                let produto = value.produto === null ? "Sem produto" : value.produto;
                let rateio_centro_custo = value.rateio == null ? value.centro_custo : value
                    .rateio;
                rateio_centro_custo = rateio_centro_custo == null ? 'Sem centro de custo' :
                    rateio_centro_custo;
                tabela += `<tr>
                        <td class="td-grid-font align-middle">
                            <input type="checkbox" class="${checkBoxClass}" ${checkBoxDisabled} data-id="${value.id}">
                        </td>
                        <td ${textClass}>
                            <p ${textTraceCode}><b>${value.trace_code}</b></p>
                        </td>
                        <td ${textClass}>
                            <p>${value.cliente}</p>
                        </td>
                        <td ${textClass}>
                            <p><b>${formatCurrency(value.valor)}</b></p>
                        </td>
                        <td ${textClass}>
                            <p><b>${formatDate(value.vencimento)}</b></p>
                        </td>
                        <td ${textClass}>
                            <p>${status}</p>
                        </td>
                        <td ${textClass}>
                            <a id="${value.id}" href="${route.replace(':id', value.id)}" class="btn btn-sm btn-success btn-success-number baixa_fatura" >${strAcao}</a>
                            <button class="btn btn-sm btn-success btn-success-number" type="button" data-toggle="collapse" data-target="#collapse${value.trace_code}" aria-expanded="false" aria-controls="collapse${value.trace_code}">
                                    <i class="bi bi-caret-right-fill"></i>
                            </button>
                        </td>
                       </tr>
                       <tr id="collapse${value.trace_code}" class="collapse">
                        <td colspan="12" class='td-grid-font'>
                            <div class='row ml-3'>Contrato: ${contrato}</div>
                            <div class='row ml-3'>Produto / Serviço: ${produto}</div>
                            <div class='row ml-3'>Centro custo / Rateio: ${rateio_centro_custo}
                        </td>
                    </tr>`;
            });
            return tabela;
        }

        $(document).on('click', '#addRateioCentroCusto', function() {
            console.log('estamos aqui');
            $('.messages-modal-cad-rateio').hide();
            $("#modalCadastroRateio").modal();
        });

        $(document).on('click', '.editaCategoria', function() {
            let idCategoria = $(this).parent().attr('id');
            let texto = $(this).parent().text().trim();
            let descricao = $("#cat_descricao_" + idCategoria).val().trim();
            console.log("descricao: " + descricao + " texto: " + texto + " id: " + idCategoria);
            $('#id_categoria_edit').val(idCategoria);
            $('input[name=categoria]').val(texto);
            $('#descricao_categoria').val(descricao);
            $('#modalInserirCategoria').modal();
            editarCategoria = true;
            $('#inserirCategoria').attr('disabled', false);
        });

        $(document).on('click', '.editaCentroCusto', function() {
            let idCentroCusto = $(this).parent().attr('id');
            let texto = $(this).parent().text().trim();
            let descricao = $('#descricao_' + idCentroCusto).val().trim();
            console.log("descricao: " + descricao + " texto: " + texto + " id: " + idCentroCusto);
            $('#id_centro_custo_edit').val(idCentroCusto);
            $('input[name=centro_custo]').val(texto);
            $('textarea[name=descricao]').val(descricao);
            $('#modalInserirCentroCusto').modal();
            editarCentroCusto = true;
            $('#inserirCentroCusto').attr('disabled', false);
        });

        $(document).on('click', ".dropdown-categoria a", function() {
            let selectedCategoria = $(this).text();
            $('#categoria').text(selectedCategoria);
            let idCategoria = $(this).attr('id');
            $("input[name=id_categoria]").remove();
            $(".hidden_categoria").append(
                `<input name="id_categoria" value="${idCategoria}"  type="hidden">`);
        });

        $(document).on('click', '.dropdown-rateio a', function() {
            let idRateio = $(this).attr('id').trim();
            let textRateio = $(this).text().trim();
            console.log(`texto: ${textRateio} id: ${idRateio}`);
            $('#centro_custo-rateio').text(textRateio);
            $('input[name=id_rateio]').remove();
            $('#hidden_rateio').append(`<input name="id_rateio" value="${idRateio}" type="hidden">`);
        });

        $(document).on('click', '.dropdown-centro_custo a', function() {
            $("input[name=id_centro_custo]").remove();
            let idSeletorDoCentroCusto = $(this).parent().attr('id');
            console.log("id do centro de custo: " + idSeletorDoCentroCusto);
            let selectedCentroCusto = $(this).text();
            console.log(selectedCentroCusto.trim());
            let idCentroCusto = $(this).attr('id');
            if (idSeletorDoCentroCusto) {
                console.log('aqui');
                $(`button#${idSeletorDoCentroCusto}`).text(selectedCentroCusto);
                if (idCentroCusto !== 'addRateioCentroCusto') {
                    $(".hidden_centro_custo").append(
                        `<input name="id_centro_custo[]" value="${idCentroCusto}" type="hidden">`);
                }
            } else {
                $('#centro_custo').text(selectedCentroCusto);
                $("#selected_centro_custo").append(
                    `<input name="id_centro_custo" value="${idCentroCusto}" type="hidden">`);
            }

            $("#inserirRateios").attr('disabled', false);
        });

        $("#cadCategoria").click(function() {
            editarCategoria = "";
            $('#nome_categoria').val('');
            $('#descricao_categoria').val('');
            $('#modalInserirCategoria').modal();
        });

        $('#cadCentroCusto').click(function() {
            editarCentroCusto = "";
            $('#nome_centro_custo').val('');
            $('#descricao_centro_custo').val('');
            $('#modalInserirCentroCusto').modal();
        });

        $('#nome_categoria').on('input', function() {
            let textoDigitado = $(this).val();
            if (textoDigitado.length >= 5) {
                $('#inserirCategoria').attr('disabled', false);
            }
        });

        $('#nome_centro_custo').on("input", function() {
            let textoDigitado = $(this).val();
            if (textoDigitado.length >= 5) {
                $('#inserirCentroCusto').attr('disabled', false);
            }
        });

        $(document).on('click', '#inserirCategoria', function() {
            let nomeCategoria = $('#nome_categoria').val();
            let descricaoCategoria = $('#descricao_categoria').val();
            let route = "{{ route('categorias.store') }}";
            let data = {
                _token: "{{ csrf_token() }}",
                categoria: nomeCategoria,
                descricao: descricaoCategoria
            };
            if (editarCategoria) {
                console.log('aqui');
                idCategoria = $('#id_categoria_edit').val();
                let routeSemId = "{{ route('categorias.update', ['categoria' => ':id']) }}";
                route = routeSemId.replace(':id', idCategoria);
                data['_method'] = "PUT";
            }
            $.ajax({
                url: route,
                type: "POST",
                data: data,
                success: function(result) {
                    console.log(result);
                    $(".mensagem-erro").hide();
                    $(".mensagem-erro")
                        .html(`<p>${result.success.message}</p>`)
                        .addClass('alert alert-success')
                        .show();
                    $('#modalInserirCategoria').modal('hide');
                    if (editarCategoria) {
                        console.log(idCategoria);
                        $(`a#${idCategoria}`).remove();
                        editarCategoria = "";
                    }
                    let aElement = `<a id="${result.success.id}" class="dropdown-item" href="#">
                                    <i class="bi bi-x-circle-fill text-danger deleteCentroCusto"></i>
                                    <i class="bi bi-pencil-fill text-warning editaCentroCusto"></i>
                                    ${result.success.categoria}
                                </a>
                                <input id="cat_descricao_${result.success.id}" type="hidden" value="${result.success.descricao}">
                                <div class="m-1 dropdown-divider"></div>`;
                    $('.add_categoria .dropdown-categoria').append(aElement);
                },
                error: function(xhr, status, errors) {
                    var error = xhr.responseJSON.errors;
                    var errorMessage = "";
                    $.each(error, function(field, messages) {
                        errorMessage += messages.join(", ") + "<br>";
                    });
                    $(".messages-modal-cad-categoria").hide();
                    $(".messages-modal-cad-categoria")
                        .html(`<p>${errorMessage}</p>`)
                        .addClass('alert alert-danger')
                        .show();
                }
            });
        });

        $(document).on('click', '#inserirCentroCusto', function() {
            let nomeCentroCusto = $('#nome_centro_custo').val();
            let descricaoCentroCusto = $('#descricao_centro_custo').val();
            let idCentroCusto = "";
            let route = "{{ route('centrocusto.store') }}";
            let data = {
                _token: "{{ csrf_token() }}",
                nome: nomeCentroCusto,
                descricao: descricaoCentroCusto
            };
            if (editarCentroCusto == true) {
                console.log('aqui');
                idCentroCusto = $('#id_centro_custo_edit').val();
                let routeSemId = "{{ route('centrocusto.update', ['centrocusto' => ':id']) }}";
                route = routeSemId.replace(':id', idCentroCusto);
                data['_method'] = "PUT";
            }
            $.ajax({
                url: route,
                type: "POST",
                data: data,
                success: function(result) {
                    $(".mensagem-erro").hide();
                    $(".mensagem-erro")
                        .html(`<p>${result.success.message}</p>`)
                        .addClass('alert alert-success')
                        .show();
                    $("#modalInserirCentroCusto").modal('hide');
                    if (editarCentroCusto == true) {
                        console.log(idCentroCusto);
                        $(`a#${idCentroCusto}`).remove();
                        editarCentroCusto = "";
                    }
                    console.log(result);
                    let aElement = `<a id="${result.success.id}" class="dropdown-item" href="#">
                                    <i class="bi bi-x-circle-fill text-danger deleteCentroCusto"></i>
                                    <i class="bi bi-pencil-fill text-warning editaCentroCusto"></i>
                                    ${result.success.nome}
                                </a>
                                <input id="descricao_${result.success.id}" type="hidden" value="${result.success.descricao}">
                                <div class="m-1 dropdown-divider"></div>`;
                    $('.add_centro_custo .dropdown-centro_custo').append(aElement);
                },
                error: function(xhr, status, errors) {
                    var error = xhr.responseJSON.errors;
                    var errorMessage = "";
                    $.each(error, function(field, messages) {
                        errorMessage += messages.join(", ") + "<br>";
                    });
                    $(".messages-modal-cad-centrocusto").hide();
                    $(".messages-modal-cad-centrocusto")
                        .html(`<p>${errorMessage}</p>`)
                        .addClass('alert alert-danger')
                        .show();
                    if (editarCentroCusto == true) {
                        editarCentroCusto = "";
                    }
                }
            })
        });

        $('#busca_cliente').typeahead({
            source: function(query, process) {
                return $.post("{{ route('autocomplete.cliente-typeahead') }}", {
                        term: query,
                        _token: "{{ csrf_token() }}"
                    },
                    function(data) {
                        if (data == 'nada aqui') {
                            return false;
                        }
                        return process(data);
                    });
            },
        });

        $("#valor").blur(function() {
            let valor = $("#valor").val();
            let formatedValor = formatarValor(valor);
            $('#valor').val(formatedValor);
        });

        $(document).on('blur', 'input[name="valor_centro_custo[]"]', function() {
            console.log('estamos aqui');
            let valor = $(this).val();
            let formatedValor = formatarValor(valor);
            $(this).val(formatedValor);
        });

        $('#cadCliente').click(function() {
            $('#documento_cliente').val('');
            $('#nome_cliente').val('');
            $('#tel_cliente').val('');
            $('#modalInserirCliente').modal();
        });

        $('#documento_cliente').blur(function() {
            $("#inserirCliente").attr('disabled', true);
            let doc = $("#documento_cliente").val();
            var doc_replaced = doc.replace(/[./-]/g, '');
            if (doc_replaced.trim() === '') {
                $('messages-modal-cad-clientes').hide();
                $('.messages-modal-cad-clientes').html(
                    '<p class="input-login alert alert-danger" style="color: #16db65">Favor inserir o documento do cliente</p>'
                ).show();
                $('#nome_cliente').attr('disabled', true);
                return;
            }
            $.ajax({
                url: "{{ route('autocomplete.cliente') }}",
                type: "POST",
                data: {
                    doc: doc_replaced,
                    _token: "{{ csrf_token() }}"
                },
                success: function(response) {
                    if (response == true) {
                        $('.messages-modal-cad-clientes').hide();
                        $('.messages-modal-cad-clientes').html(
                            '<p class="alert alert-danger">Cliente ja existe no banco de dados impossivel inserir</p>'
                        ).show();
                        clienteExists = true;
                    } else {
                        console.log('cliente não existe');
                        $('messages-modal-cad-clientes').hide();
                        $('.messages-modal-cad-clientes').html(
                            '<p class="alert alert-success" style="color: #16db65">Liberado para inserir cliente</p>'
                        ).show();
                        $('#nome_cliente').attr('disabled', false);
                        clienteExists = false;
                    }
                },
            });
        });

        $('#tel_cliente').blur(function() {
            let phoneNumber = formatPhoneNumber($('#tel_cliente').val());
            $('#tel_cliente').val(phoneNumber);
        });

        $('#inserirCliente').click(function() {
            showLoader();
            let doc = $("#documento_cliente").val();
            let nome = $('#nome_cliente').val();
            let telefone = $('#tel_cliente').val();
            $.ajax({
                url: "{{ route('clientes.store') }}",
                type: "POST",
                data: {
                    cpf_cnpj: doc,
                    nome: nome,
                    telefone: telefone,
                    _token: "{{ csrf_token() }}"
                },
                success: function(result) {
                    if (result.success) {
                        $('.mensagem-erro').hide();
                        $('.mensagem-erro').html(
                            `<p class="alert alert-success">Cliente ${nome} inserido com sucesso</p>`
                        ).show();
                    } else {
                        $('.mensagem-erro').hide();
                        $('.mensagem-erro').html(
                            `<p class"alert alert-danger">${result.message}</p>`).show();
                    }
                    $('#modalInserirCliente').modal('hide');
                    hideLoader();
                }
            });
        });

        $('#nome_cliente').blur(function() {
            let nome = $('#nome_cliente').val();
            if (clienteExists) {
                return;
            }
            if (nome.trim() === '') {
                $('messages-modal-cad-clientes').hide();
                $('.messages-modal-cad-clientes').html(
                    '<p class="alert alert-danger" style="background-clor: #color: #16bd65">Favor inserir o nome do cliente</p>'
                ).show();
            } else {
                $("#inserirCliente").attr('disabled', false);
            }
        });

        $('#customSwitcheHabilitarRateio').change(function() {
            if (this.checked) {
                $('#formRateio').show();
                $('#centro_custo').attr('disabled', true);
                let valorTotal = $("#valor").val();
                $('input[name="valor_centro_custo[]"]').val(valorTotal);
                $('input[name="valor_centro_custo[]"]').attr('disabled', true);
            } else {
                $('#customSwitcheHabilitarRateio').prop('checked', true);
                console.log(formRateioCount);
                $('#modalConfirmaApagarRateio').modal();
            }
        });

        $('#confirmaApagarRateio').click(function() {
            $('#formRateio').hide();
            $('#centro_custo').attr('disabled', false);
            $('#customSwitcheHabilitarRateio').prop('checked', false); // Altera o estado do switch
            for (let i = 1; i < formRateioCount; i++) {
                console.log(i);
                $(`#formRateio${i}`).remove();
            }
            $('#modalConfirmaApagarRateio').modal('hide'); // Fecha o modal
            formRateioCount = 1;
        });

        $('#customSwitcheRepetirLancamento').change(function() {
            if (this.checked) {
                $('#formRepetirLancamento').show();
                let valorTotal = $("#valor").val();
                $("#valor1").val(valorTotal);
            } else {
                $('#customSwitcheRepetirLancamento').prop('checked', true);
                $('#modalConfirmaApagarLancamento').modal();
            }
        });

        $("#confirmaApagarLancamento").click(function() {
            console.log(formRepetirLancamentoCount);
            $("#formRepetirLancamento").hide();
            $("#customSwitcheRepetirLancamento").prop('checked', false);
            for (let i = 0; i < formRepetirLancamentoCount; i++) {
                /*$(`#vencimento${i}`).remove();
                $(`#valor${i}`).remove();
                $(`.span_valor${i}`).remove();*/
                $(`div.div_vencimento${i}`).remove();
                $(`div.div_valor${i}`).remove();
            }
            $('#modalConfirmaApagarLancamento').modal('hide');
        });

        $('#addVencimento').click(function() {
            showLoader();
            const date = new Date();
            let qtdeVencimento = $('#qtde_vencimento').val();
            console.log(qtdeVencimento);
            let valor = $('#valor').val();
            let data = $('#vencimento').val();
            let teste = '';
            for (var i = 0; i < qtdeVencimento; i++) {
                let newDate = new Date(
                    data); // Cria um novo objeto Date com a mesma data que a data original
                newDate.setMonth(newDate.getMonth() + (i +
                    1)); // Adiciona o número de meses correspondente ao valor de i
                newDate.setFullYear(date.getFullYear() + Math.floor((date.getMonth() + (i + 1)) /
                    12)); // Adiciona o número de anos correspondente ao valor de i
                console.log(newDate.toISOString().substr(0, 10));
                teste += `<div class="form-group input-group col-md-2 div_vencimento${i}">
                        <label for="vencimento${i}" class="required">Vencimento:</label>
                        <div class="input-group">
                            <input id="vencimento${i}" name="vencimento_recorrente[]" type="date" class="input-login input-login form-control" value="${newDate.toISOString().substr(0, 10)}">
                        </div>
                    </div>
                    <div class="form-group input-group col-md-2 div_valor${i}">
                        <label for="valor${i}" class="required">Valor:</label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text span_valor${i}">R$</span>
                            </div>
                            <input id="valor${i}" name="valor_recorrente[]" type="text" class="input-login form-control" value="${valor}">
                        </div>
                    </div>`;
            }
            $("#vencimento_fixo").val(data);
            $("#valor_fixo").val(valor);
            $('#clonar').append(teste);
            formRepetirLancamentoCount = qtdeVencimento;
            event.preventDefault();
            hideLoader();
        });

        $('#cloneButton').click(function() {
            showLoader();
            var clonedElement = $('#formRateio').clone();

            clonedElement.find('#cloneButton').remove();
            clonedElement.find('.remove-on-clone').remove();

            let uniqueFormRateioId = 'formRateio' + formRateioCount;
            clonedElement.attr('id', uniqueFormRateioId);
            var buttonInsideClonedElement = clonedElement.find('button');
            buttonInsideClonedElement.attr('id', `centro_custo-rateio${formRateioCount}`);
            clonedElement.find('button').replaceWith(buttonInsideClonedElement);
            let divInsideDropDown = clonedElement.find('div#centro_custo-rateio');
            divInsideDropDown.attr('id', `centro_custo-rateio${formRateioCount}`);
            clonedElement.find('div#centro_custo-rateio').replaceWith(divInsideDropDown);
            formRateioCount++;

            $("#addRateio").append(clonedElement);
            event.preventDefault();
            hideLoader();
        });

        $('#cloneRateio').click(function() {
            showLoader();
            console.log('aqui');
            var clonedElement = $('#clonarRateio').clone();

            clonedElement.find('#cloneRateio').remove();
            clonedElement.find('.remove-on-clone').remove();

            let uniqueFormRateioId = 'formRateio' + formRateioCount;
            clonedElement.attr('id', uniqueFormRateioId);
            var buttonInsideClonedElement = clonedElement.find('button');
            buttonInsideClonedElement.attr('id', `centro_custo-rateio${formRateioCount}`);
            clonedElement.find('button').replaceWith(buttonInsideClonedElement);
            let divInsideDropDown = clonedElement.find('div#centro_custo-rateio');
            divInsideDropDown.attr('id', `centro_custo-rateio${formRateioCount}`);
            clonedElement.find('div#centro_custo-rateio').replaceWith(divInsideDropDown);
            formRateioCount++;

            $("#formCriarRateio").append(clonedElement);
            event.preventDefault();
            hideLoader();
        });

        $(document).on('click', '#inserirRateios', function() {
            event.preventDefault();
            let form = new FormData($("#cadastroRateioForm")[0]);
            let route = $("#cadastroRateioForm").attr('action');
            console.log(route);
            console.log('teste');
            $.ajax({
                url: route,
                type: "POST",
                data: form,
                processData: false,
                contentType: false,
                success: function(result) {
                    $('.messages-modal-cad-rateio').hide();
                    $(".messages-modal-cad-rateio")
                        .html(`<p>${result.success.message}</p>`)
                        .addClass('alert alert-success')
                        .show();
                    console.log(result);
                    let aElement = `<a id="${result.success.id}" class="dropdown-item" href="#">
                                    <i class="bi bi-x-circle-fill text-danger deleteCentroCusto"></i>
                                    <i class="bi bi-pencil-fill text-warning editaCentroCusto"></i>
                                    ${result.success.nome}
                                </a>
                                <input id="descricao_${result.success.id}" type="hidden" value="${result.success.descricao}">
                                <div class="m-1 dropdown-divider"></div>`;
                    $('.add_rateio .dropdown-rateio').append(aElement);
                    for (let i = 0; i <= formRateioCount; i++) {
                        $('input[name="id_centro_custo[]"]').remove();
                        if (i > 0) {
                            $(`#formRateio${i}`).remove();
                        }

                    }
                    $("button#centro_custo-rateio").text('Selecione um centro de custo');
                    $('input[name="percentual_rateio[]"]').val('');
                    $('#nome_rateio').val('');
                    $('#modalCadastroRateio').modal('hide');
                },
                error: function(xhr, status, error) {
                    var error = xhr.responseJSON.errors;
                    var errorMessage = "";
                    $.each(error, function(field, messages) {
                        errorMessage += messages.join(", ") + "<br>";
                    });
                    $(".messages-modal-cad-rateio").hide();
                    $(".messages-modal-cad-rateio")
                        .html(`<p>${errorMessage}</p>`)
                        .addClass('alert alert-danger')
                        .show();
                }
            });
        });

        $('#valor-recebido').blur(function() {
            $('#message-receber').hide();
            let valRecebido = $(this).val();
            let valOriginal = $("#valor").val();

            if (valRecebido === "") {
                $("#message-receber").html('<p>Valor recebido não pode ser vazio</p>').addClass(
                    'alert alert-danger').show();
                $("#receber").attr('disabled', true);
                return
            }

            $("#receber").attr('disabled', false);

            if (parseFloat(unFormatMoney(valRecebido)) < parseFloat(unFormatMoney(valOriginal))) {
                $("#message-receber").html(
                    '<p>Valor recebido menor que o valor original, isso irá gerar uma nova fatura e marcar esta como parcialmente pago</p>'
                ).addClass('alert alert-danger').show();
                valorDivergente = true;
            }

            if (parseFloat(unFormatMoney(valRecebido)) > parseFloat(unFormatMoney(valOriginal))) {
                $("#message-receber").html('<p>Valor recebido maior que valor original</p>').addClass(
                    'alert alert-danger').show();
            }

            if (parseFloat(unFormatMoney(valRecebido)) == parseFloat(unFormatMoney(valOriginal))) {
                valorDivergente = false;
            }
        });

        $('.receber').click(function() {
            $('#modalInserirRecebimento').modal();
            idReceber = $('.receber').data('id');
        });

        $('#receber').click(function() {
            console.log(idReceber);
            let dados = {
                id: $('.receber').data('id'),
                valor: $('#valor-recebido').val(),
                divergente: valorDivergente,
                data: $("#data-valor-recebido").val(),
            };
            $.get("{{ route('financeiro.baixar-receber') }}", dados, function(data) {
                showLoader();
            }).done(function(data) {
                console.log("acerto");
                route = data.redirect;
                window.location.href = route;
                hideLoader();
            }).fail(function(xhr, status, error) {
                console.log("erro");
                var errors = xhr.responseJSON.message.error;
                var errorMessage = errors.join(", ");
                console.log(errorMessage);
                $("#message-receber").hide();
                $("#message-receber")
                    .html(`<p>${errorMessage}</p>`)
                    .addClass('alert alert-danger')
                    .show();
                hideLoader();
            });
        });

        $('#formUpdateContaReceber').submit(function() {
            event.preventDefault();
            let form = new FormData(this);
            let route =
                "{{ isset($contasReceber->id) ? route('financeiro.update', ['financeiro' => $contasReceber->id]) : null }}";
            console.log(route);
            $.ajax({
                url: route,
                type: 'POST',
                data: form,
                processData: false,
                contentType: false,
                success: function(response) {
                    console.log(response);
                    let message = "Registros inseridos com sucesso";
                    let ids = response.success.id;
                    console.log(ids);
                    $(".mensagem-erro").hide();
                    $.each(ids, function(field, id) {
                        message += id + ", ";
                    });
                    $(".mensagem-erro").html(`<p>${message}</p>`).addClass(
                        'alert alert-success').show();
                    window.location.href = "{{ route('financeiro.receber') }}";
                },
                error: function(xhr, status, error) {
                    var error = xhr.responseJSON.errors;
                    var errorMessage = "";
                    $.each(error, function(field, messages) {
                        errorMessage += messages.join(", ") + "<br>";
                    });
                    $(".mensagem-erro").hide();
                    $(".mensagem-erro")
                        .html(`<p>${errorMessage}</p>`)
                        .addClass('alert alert-danger')
                        .show();
                }
            });
        });

        $('#formAddContaReceber').submit(function() {
            event.preventDefault();
            let form = new FormData(this);
            $.ajax({
                url: "{{ route('financeiro.store') }}",
                type: 'POST',
                data: form,
                processData: false,
                contentType: false,
                success: function(response) {
                    console.log(response);
                    let message = "Registros inseridos com sucesso";
                    let ids = response.success.id;
                    console.log(ids);
                    $(".mensagem-erro").hide();
                    $.each(ids, function(field, id) {
                        message += id + ", ";
                    });
                    $(".mensagem-erro").html(`<p>${message}</p>`).addClass(
                        'alert alert-success').show();
                    window.location.href = "{{ route('financeiro.receber') }}";
                },
                error: function(xhr, status, error) {
                    var error = xhr.responseJSON.errors;
                    var errorMessage = "";
                    $.each(error, function(field, messages) {
                        errorMessage += messages.join(", ") + "<br>";
                    });
                    $(".mensagem-erro").hide();
                    $(".mensagem-erro")
                        .html(`<p>${errorMessage}</p>`)
                        .addClass('alert alert-danger')
                        .show();
                }
            });
        });
    })
</script>
