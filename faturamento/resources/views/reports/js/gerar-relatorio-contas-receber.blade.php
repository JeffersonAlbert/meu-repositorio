<script>
    $(document).ready(function(){
        $('.dropdown-item.periodo').on('click', function(){
            let periodo = $('#button-pai').text();
            let dateRange = convertDateRange(periodo);
            console.log(dateRange);
            $('#vencimentoInicial').val(dateRange.startDate);
            $('#vencimentoFinal').val(dateRange.endDate);
        });

        $('#formBuscaPadrao').submit(function(e){
            e.preventDefault();
            let url = '{{ route('relatorio.contas-receber-relatorio') }}';
            let data = $(this).serialize();
            showLoader();
            $.ajax({
                url: url,
                type: 'GET',
                data: data,
                success: function(result){
                    console.log(result);
                    html = "";
                    $.each(result.data, function(key, value){
                        let status = value.status == null ? 'Pendente' : value.status;
                        let filial_nome = value.filial_nome == null ? 'Sem filial' : value.filial_nome;
                        let categoria_nome = value.categoria_nome == null ? 'Sem categoria' : value.categoria_nome;
                        let nome_contrato = value.nome_contrato == null ? 'Sem contrato' : value.nome_contrato;
                        let produto_nome = value.produto_nome == null ? 'Sem produto' : value.produto_nome;
                        let valor = formatCurrency(value.valor_vencimento);
                        html += `<tr>
                            <td>${value.trace_code}</td>
                            <td>${value.nome_cliente}</td>
                            <td>${filial_nome}</td>
                            <td>${categoria_nome}</td>
                            <td>${nome_contrato}</td>
                            <td>${value.vencimento_formatado}</td>
                            <td>${valor}</td>
                            <td>${produto_nome}</td>
                            <td>${status}</td>
                        </tr>`
                    });
                    $('.tabela-relatorio').empty().append(html);
                    hideLoader();
                }
            });
        });

        $('#gerarRelatorio').on('click', function(){
            let periodo = $('#button-pai').text();
            let dateRange = convertDateRange(periodo)
            let url = '{{ route('relatorio.contas-receber-relatorio') }}';
            showLoader();
            $.ajax({
                url: url,
                type: 'GET',
                data: {
                    vencimentoInicial: dateRange.startDate,
                    vencimentoFinal: dateRange.endDate
                },
                success: function(data){
                    console.log(data);
                    html = "";
                    $.each(data.data, function(key, value){
                        let status = value.status == null ? 'Pendente' : value.status;
                        let filial_nome = value.filial_nome == null ? 'Sem filial' : value.filial_nome;
                        let categoria_nome = value.categoria_nome == null ? 'Sem categoria' : value.categoria_nome;
                        let nome_contrato = value.nome_contrato == null ? 'Sem contrato' : value.nome_contrato;
                        let produto_nome = value.produto_nome == null ? 'Sem produto' : value.produto_nome;
                        let valor = formatCurrency(value.valor_vencimento);
                        html += `<tr>
                            <td>${value.trace_code}</td>
                            <td>${value.nome_cliente}</td>
                            <td>${filial_nome}</td>
                            <td>${categoria_nome}</td>
                            <td>${nome_contrato}</td>
                            <td>${value.vencimento_formatado}</td>
                            <td>${valor}</td>
                            <td>${produto_nome}</td>
                            <td>${status}</td>
                        </tr>`
                    });
                    $('.tabela-relatorio').empty().append(html);
                    hideLoader();
                }
            });
        });
    })
</script>
