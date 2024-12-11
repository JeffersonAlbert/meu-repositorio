<script>
    $(document).ready(function() {
        // Lidar com o clique no botão de alternância
        $("#exibeFormBuscaGrid").click(function() {
            // Alterna a exibição do formulário usando animação de slide
            $("#formBuscaGrid").slideToggle();
        });

        $("#vencimentoFinal").on('change', function() {
            validaData($("#vencimentoInicial").val(), $("#vencimentoFinal").val());
        });

        $("#insercaoFinal").on('change', function() {
            validaData($("#insercaoInicial").val(), $("#insercaoFinal").val());
        });

        $(document).on('click', '.page-link', function() {
            event.preventDefault();
            var nextpage = $(this).attr('href');
            let tipo = $('#tipo').val();
            getPaginate(nextpage, tipo);
        });

        function getPaginate(nextpage, tipo = null) {
            showLoader();
            event.preventDefault();
            console.log(nextpage);

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
                    $("#tabela-processos").empty().append(tabela);

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

        function tableData(dbResult) {
            let grid = '';
            $.each(dbResult.data, function(key, value) {
                console.log('categoria dre ' + value.sub_categoria_dre);
                let alert = value.status_aprovacao == false ?
                    '<div  style="color: red"><i class="bi bi-exclamation-triangle"></i></div>' : null;
                let fornecedor = value.f_name == null ? value.sub_categoria_dre : value.f_name;
                let routeEdit =
                    "{{ route('processo.editar-processo', ['processo' => ':valueId', 'data' => ':valuePvv_dtv']) }}";
                let routeEditReplaced = routeEdit.replace(':valueId', value.id).replace(':valuePvv_dtv',
                    value.pvv_dtv);
                let routeProtocolo = "{{ route('processo.protocolo', ['processo' => ':valueId']) }}";
                let routeProtocoloReplaced = routeProtocolo.replace(':valueId', value.id);
                let routeAprovacao =
                    "{{ route('processo.aprovacao', ['id' => ':valueId', 'vencimento' => ':valuePvv_dtv']) }}";
                let routeAprovacaoReplaced = routeAprovacao.replace(':valueId', value.id).replace(
                    ':valuePvv_dtv', value.pvv_dtv);
                let updated_atFormatted = formatDateNumber(value.updated_at);
                let updated_atHourFormatted = formatHourNumber(value.updated_at);
                let vparcelaFormatted = formatCurrency(parseFloat(value.vparcela));
                let valorFormatted = formatCurrency(value.valor);
                let pvv_dtvFormatted = formatDateNumber(value.pvv_dtv);
                grid += `<tr>
            <td class="text-center td-grid-font align-middle">
                <b>${value.u_name}</b>
            </td>
            <td class="text-center td-grid-font align-middle">
                <p class="background-trace-code align-middle"><b>Identificação: ${value.trace_code}</b></p>
                <p>${fornecedor}</p>
                <p>Nº Nota: ${value.num_nota}</p>
            </td>
            <td class="text-center td-grid-font align-middle">
                <p><b>${updated_atFormatted}</b></p>
                <p>${updated_atHourFormatted}</p>
                <p>Usuário: ${value.u_last_modification}</p>
            </td>
            <td class="text-center td-grid-font align-middle">
                <p><b>${vparcelaFormatted}/Mês</b></p>
                <p>${valorFormatted}/Processo</p>
            </td>
            <td class="text-center td-grid-font align-middle">
                <p><b>${pvv_dtvFormatted}</b></p>
            </td>
            <td>
                <button onclick="window.location.href='${routeEditReplaced}'" class="btn btn-sm btn-success btn-success-number">
                    <i class="bi bi-pen"></i>
                    Editar
                </button><br>
                <button onclick="window.location.href='${routeProtocoloReplaced}'" class="btn btn-sm btn-success btn-success-number mt-1">
                    <i class="bi bi-download"></i>
                    Download
                </button><br>
                <button onclick="window.location.href='${routeAprovacaoReplaced}'"  class="btn btn-sm btn-success btn-success-number mt-1" data-toggle="modal" data-target=".bd-example-modal-xl">
                    <i class="bi bi-eye"></i>
                    Vizualizar
                </button>
            </td>
            </tr>`;
            });
            return grid;
        }

        function validaData(dataInicial, dataFinal) {
            dataIni = new Date(dataInicial);
            dataFim = new Date(dataFinal);
            if (dataFinal < dataInicial) {
                $(".mensagem-erro").html("<p>A data final não pode ser menor que a data inicial</p>").addClass(
                    "alert alert-danger").show();
            }
        }

        function paginateDbResult(result, tipo) {
            console.log('pagination');
            console.log(result);
            var paginacao = `<nav>` +
                `<ul class="pagination">`;
            $.each(result.links, function(index, links) {
                console.log(links);
                var active = links.active == true ? "active" : "";
                paginacao += `<li class="page-item ${active}">` +
                    `<a class="page-link ${tipo}" href="${links.url}">${links.label}</a>` +
                    `</li>`;
            });
            paginacao += '</ul></nav>';
            return paginacao.replace('Previous', '').replace('Next', '');
        }

        $('#formBuscar').submit(function(e) {
            e.preventDefault();
            let dataForm = $(this).serialize();
            let tipo = $('#tipo').val();
            $.get("{{ route('processo.consulta') }}", {
                dataForm,
                tipo: tipo
            }, function(data) {
                showLoader();
            }).done(function(data) {
                console.log('Acerto');
                console.log(data.data);
                let grid = '';
                $.each(data.data, function(key, value) {
                    let inform = value.status_aprovacao == false ?
                        '<div  style="color: red"><i class="bi bi-exclamation-triangle"></i></div>' :
                        '';
                    let fornecedor = value.f_name == null ? value.sub_categoria_dre :
                        value.f_name;
                    let routeEdit =
                        "{{ route('processo.editar-processo', ['processo' => ':valueId', 'data' => ':valuePvv_dtv']) }}";
                    let routeEditReplaced = routeEdit.replace(':valueId', value.id)
                        .replace(':valuePvv_dtv', value.pvv_dtv);
                    let routeProtocolo =
                        "{{ route('processo.protocolo', ['processo' => ':valueId']) }}";
                    let routeProtocoloReplaced = routeProtocolo.replace(':valueId',
                        value.id);
                    let routeAprovacao =
                        "{{ route('processo.aprovacao', ['id' => ':valueId', 'vencimento' => ':valuePvv_dtv']) }}";
                    let routeAprovacaoReplaced = routeAprovacao.replace(':valueId',
                        value.id).replace(':valuePvv_dtv', value.pvv_dtv);
                    let updated_atFormatted = formatDateNumber(value.updated_at);
                    let updated_atHourFormatted = formatHourNumber(value.updated_at);
                    let vparcelaFormatted = formatCurrency(parseFloat(
                        value.vparcela));
                    let valorFormatted = formatCurrency(value.valor);
                    let pvv_dtvFormatted = formatDateNumber(value.pvv_dtv);
                    grid += `<tr>
        <td class="text-center td-grid-font align-middle">
        <p>${inform}</p>
            <b>${value.u_name}</b>
        </td>
        <td class="text-center td-grid-font align-middle">
            <p class="background-trace-code align-middle"><b>Identificação: ${value.trace_code}</b></p>
            <p>${fornecedor}</p>
            <p>Nº Nota: ${value.num_nota}</p>
        </td>
        <td class="text-center td-grid-font align-middle">
            <p><b>${updated_atFormatted}</b></p>
            <p>${updated_atHourFormatted}</p>
            <p>Usuário: ${value.u_last_modification}</p>
        </td>
        <td class="text-center td-grid-font align-middle">
            <p><b>${vparcelaFormatted}/Mês</b></p>
            <p>${valorFormatted}/Processo</p>
        </td>
        <td class="text-center td-grid-font align-middle">
            <p><b>${pvv_dtvFormatted}</b></p>
        </td>
        <td>
            <button onclick="window.location.href='${routeEditReplaced}'" class="btn btn-sm btn-success btn-success-number">
                <i class="bi bi-pen"></i>
                Editar
            </button><br>
            <button onclick="window.location.href='${routeProtocoloReplaced}'" class="btn btn-sm btn-success btn-success-number mt-1">
                <i class="bi bi-download"></i>
                Download
            </button><br>
            <button onclick="window.location.href='${routeAprovacaoReplaced}'"  class="btn btn-sm btn-success btn-success-number mt-1" data-toggle="modal" data-target=".bd-example-modal-xl">
                <i class="bi bi-eye"></i>
                Vizualizar
            </button>
        </td>
        </tr>`;
                });
                $('#tabela-processos').empty().append(grid);
                paginacao = paginateDbResult(data, tipo);
                $('div.pagination').empty().append(paginacao);
                hideLoader();
            }).fail(function(xhr, status, error) {
                console.log("erro");
                hideLoader();
            });
        });

    });
</script>
