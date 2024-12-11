<script>
$(document).ready(function () {
    $('.toggle-collapse').on('click', function () {
        var icon = $(this).find('i');
        var isExpanded = $(this).attr('aria-expanded') === 'true';
        icon.toggleClass('bi-caret-right-fill bi-caret-down-fill');
    });

    $('.collapse').on('shown.bs.collapse', function () {
        var target = $(this).attr('id');
        $('button[data-target="#' + target + '"]').find('i').removeClass('bi-caret-right-fill').addClass('bi-caret-down-fill');
    });

    $('.collapse').on('hidden.bs.collapse', function () {
        var target = $(this).attr('id');
        $('button[data-target="#' + target + '"]').find('i').removeClass('bi-caret-down-fill').addClass('bi-caret-right-fill');
    });
});

$(document).on('click', 'a.periodo', function(){
    let periodo = $('#button-pai').text();
    let dateRange = convertDateRange(periodo);
    console.log('date range '+dateRange);
    $('#vencimentoInicial').val(dateRange.startDate);
    $('#vencimentoFinal').val(dateRange.endDate);
    $('#formBuscar button.btn-enviar').click();
});

$(document).ready(function() {
    let aba = "geral";

    $("#vencimentoFinal").on('change', function(){
        validaData($("#vencimentoInicial").val(), $("#vencimentoFinal").val());
    });

    $("#pagamentoFinal").on('change', function(){
        validaData($("#pagamentoInicial").val(), $("#pagamentoFinal").val());
    });

    $("#exibeFormBuscaGrid").click(function() {
        // Alterna a exibição do formulário usando animação de slide
        $("#formBuscaGrid").slideToggle();
        console.log('lalaal');
    });

    $("#formBuscar").submit(function(){
        event.preventDefault();
        console.log(aba);
        let data = $(this).serialize();
        data = data+'&tipo='+aba;
        let action = $(this).attr("action");
        enviarFormulario(action, data);
    });

    $('.aba-financeiro').click(function(){
        event.preventDefault();
        // Remove a classe active de todos os links
        $('.aba-financeiro').removeClass('active');
        $('.aba-financeiro').css('text-decoration', '');


        // Adiciona a classe active apenas ao link clicado
        $(this).addClass('active');
        $(this).css('text-decoration', 'underline');
    });

    $('#geral').on('click', function(){
        aba = 'geral';
        window.location.href = "{{ route('financeiro.controle') }}";
    });

    $(document).on('click', '.page-link.geral',function(){
        event.preventDefault();
        let nextpage = $(this).attr('href');
        getPaginate(nextpage, 'geral');
    });

    $('#andamento').on('click', function(){
        aba = 'andamento';
        mudarAbas('andamento');
    });

    $(document).on('click', '.page-link.andamento', function(){
        event.preventDefault();
        var nextpage = $(this).attr('href');
        getPaginate(nextpage, 'andamento');
    });

    $('#pendentes').on('click', function(){
        aba = 'pendentes';
        mudarAbas('pendentes');
    });

    $(document).on('click', '.page-link.pendentes', function(){
        event.preventDefault();
        var nextpage = $(this).attr('href');
        getPaginate(nextpage, 'pendentes');
    });


    $('#pagas').on('click', function(){
        aba = 'pagas'
        mudarAbas('pagas');
    });

    $(document).on('click', '.page-link.pagas', function(){
        event.preventDefault();
        var nextpage = $(this).attr('href');
        getPaginate(nextpage, 'pagas');
    });


    function getPaginate(nextpage, tipo){
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
            success: function(response){
                tabela = tableData(response)
                $("#tabela-corpo").empty().append(tabela);

                var paginacao = paginateDbResult(response, tipo);
                pagination.append(paginacao);
                hideLoader();
            },
            error: function(xhr, status, error){
                console.error(error);
                hideLoader();
            }
        });
    }

    function tableData(dbResult){
        var tabela = "";
        var url = "{{ route('processo.aprovacao', ['id' => "id", 'vencimento' => "vencimento"]) }}";
        $.each(dbResult.data, function(index, tableData){
            console.log(tableData);
            let status = (tableData.aprovado == true ? (tableData.pago == true ? "Aprovado por nomes" : "Aprovado por nomes falta pagar") : "Aguardando aprovação grupos");
            let statusIcon = (tableData.aprovado == true ?  (tableData.pago == true ? 'bi bi-check-circle-fill text-success': 'bi bi-exclamation-circle-fill text-primary') : 'bi bi-exclamation-circle-fill text-warning');
            let textoStatus = (tableData.aprovado == true ? (tableData.pago == true ? "Pago" : "Aprovado aguardando pagamento") : "Aguardando aprovacao");
            let textClass = 'class="td-grid-font align-middle"';
            let textTraceCode = 'class="background-trace-code align-middle"';
            let forncedorName = tableData.f_name == null ? tableData.dre_categoria : tableData.f_name;
            toolTip = (tableData.aprovado == true ? (tableData.pago ==true ? "Aprovado por nomes" : "Aprovado por nomes falta pagar") : "Aguardando aprovação grupos");
            icon = (tableData.aprovado == true ?  (tableData.pago == true ? 'bi bi-check-circle-fill text-success': 'bi bi-exclamation-circle-fill text-primary') : 'bi bi-exclamation-circle-fill text-warning');
            link = url.replace("id", tableData.id).replace("vencimento", tableData.pvv_dtv);
            texto = (tableData.aprovado == true ? (tableData.pago == true ? "Pago" : "Aprovado aguardando pagamento") : "Aguardando aprovacao");
            filial = tableData.filial_nome === null ? 'N/A' : tableData.filial_nome;
            contrato = tableData.contrato === null ? 'N/A' : tableData.contrato;
            produto = tableData.produto === null ? 'N/A' : tableData.produto;
            rateio = tableData.rateio === null ? 'N/A' : tableData.rateio;
            centro_custo = tableData.centro_custo === null ? 'N/A' : tableData.centro_custo;
            result = `<div data-toogle="tooltip" title="${toolTip}">`+
                    `<i fill="currentColor" class="${icon}"></i>`+
                    `<a href=${link}>`+
                    `<span>${texto}</span>`+
                    `</a>`+
                `</div>`;
            tabela += `<tr>
                    <td ${textClass}>
                        <p ${textTraceCode}><b>${tableData.trace_code}</b></p>
                    </td>
                    <td ${textClass}>
                        <p>${forncedorName}</p>
                    </td>
                    </td>
                    <td ${textClass}>
                        <p><b>R$: ${formatCurrency(tableData.vparcela)} / Mês</b></p>
                    </td>
                    <td ${textClass}>
                        <p><b>${formatDateNumber(tableData.pvv_dtv)}</b></p>
                    </td>
                    <td  ${textClass}>
                        <p>${result}</p>
                    </td>

                    <td ${textClass}>
                        <a class="btn btn-sm btn-success btn-success-number" href="${link}">
                            <i class="bi bi-eye"></i>
                        </a>
                        <button class="btn btn-sm btn-success btn-success-number" type="button" data-toggle="collapse" data-target="#collapse${tableData.trace_code}" aria-expanded="false" aria-controls="collapse${tableData.trace_code}">
                            <i class="bi bi-caret-right-fill"></i>
                        </button>
                    </td>
                </tr>
                <tr id="collapse${tableData.trace_code}" class="collapse">
                        <td colspan="12" class="td-grid-font">
                            <div class="row ml-3">Filial: ${filial} / Contrato: ${contrato}</div>
                            <div class="row ml-3">Produto/Serviço: ${produto}</div>
                            <div class="row ml-3">Cento de custo: ${centro_custo} / Rateio: ${rateio} </div>
                            <div class="row ml-3">Valor processo: <b>${formatCurrency(tableData.valor)}</b></div>
                        </td>
                    </tr>
`;
        });
        return tabela;
    }

    function paginateDbResult(dbResult, tipo){
        var paginacao = `<nav>`+
            `<ul class="pagination">`;
        $.each(dbResult.links, function(index, links){
            var active = links.active == true ? "active" : "";
            paginacao += `<li class="page-item ${active}">`+
                `<a class="page-link ${tipo}" href="${links.url}">${links.label}</a>`+
                `</li>`;
        });
        paginacao += '</ul></nav>';
        return paginacao.replace('Previous', '').replace('Next', '');
    }

    function mudarAbas(tipo){
        var tabelaCorpo = $('#tabela-corpo');
        var paginate = $('div.pagination');
        tabelaCorpo.empty(); // Remove todas as linhas da tabela
        paginate.empty();

        $.ajax({
            url: "{{ route('financeiro.controle') }}",
            type: "GET",
            data: {
                tipo: tipo,
            },
            success: function(response) {
                tabela = tableData(response);
                tabelaCorpo.append(tabela);
                var paginacao = paginateDbResult(response, tipo);
                paginate.append(paginacao);
            },
        });
    }

    function enviarFormulario(action, data){
        showLoader();
        $.ajax({
            url: action,
            type: 'GET',
            data: data, // Envie os dados do formulário serializados
            processData: false,  //Não processe os dados
            contentType: false,  //Não defina o tipo de conteúdo
            success: function(response) {
                // Faça algo com a resposta do servidor, se necessário
                if(response.redirect){
                    window.location.href = response.redirect;
                }else{
                    tabela = tableData(response.faturas);
                    paginacao = paginateDbResult(response.faturas, aba);
                    $("#tabela-corpo").empty().append(tabela);
                    $('div.pagination').empty().append(paginacao);
                    $('#vencidos-valor').text(formatCurrency(response.valor_vencido))
                    $('#avencer-valor').text(formatCurrency(response.valor_vencer))
                    hideLoader();
                }

            },
            error: function(xhr, status, error) {
                // Lide com erros de solicitação, se ne scessário
                var errors = xhr.responseJSON.error;
                var errorMessage = "";
                $.each(errors, function(field, messages) {
                    errorMessage +=  messages.join(", ") + "<br>";
                });
                hideLoader();
                $(".form-setup-error").hide();
                $(".form-setup-error").html(`<p>${errorMessage}</p>`).addClass('alert alert-danger').show();
            }
        });
    }

    function validaData(dataInicial, dataFinal){
        $(".mensagem-erro").hide();
        dataIni = new Date(dataInicial);
        dataFim = new Date(dataFinal);
        if(dataFinal < dataInicial){
            $(".mensagem-erro").html("<p>A data final não pode ser menor que a data inicial</p>").addClass("alert alert-danger").show();
        }
    }

});
</script>
