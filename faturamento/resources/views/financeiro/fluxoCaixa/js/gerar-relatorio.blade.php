<script>
    $(document).ready(function(){
        $("#gerarRelatorio").click(function(){
            let banco = $("#selecionarBanco").val();
            let mesAno = formatMonthYear($("#datepicker-month-year").val());
            let url = "{{ route('financeiro.fluxo-caixa', ['mes' => '__mes__']) }}";
            url = url.replace('__mes__', mesAno);
            let urlPdf = "{{ route('pdf-fluxo-caixa', ['date' => '__DATE__']) }}";
            $('#pdfFluxoCaixa').attr('href', urlPdf.replace('__DATE__', mesAno));
            let urlExcel = "{{ route('excel-fluxo-caixa', ['date' => '__DATE__']) }}";
            $('#excelFluxoCaixa').attr('href', urlExcel.replace('__DATE__', mesAno));
            showLoader();
            $.get(url, function(data) {
                let html = '';
                $.each(data, function(key, value){
                    let keyDate = formatDate(key);
                    let entradas = formatCurrency(value.entradas);
                    let saidas = formatCurrency(value.saidas);
                    let total = formatCurrency(value.total);
                    html += `<tr>
                        <td>${keyDate}</td>
                        <td>${entradas}</td>
                        <td>${saidas}</td>
                        <td>${total}</td>
                    </tr>`;
                });
                $('.tabela-fluxo-diario').empty().append(html);
                hideLoader();
            });
        });
    });
</script>
