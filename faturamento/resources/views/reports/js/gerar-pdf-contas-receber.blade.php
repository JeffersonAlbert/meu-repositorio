<script>
    $(document).ready(function(){
        $('#vencimentoInicial').val('{{ date('Y-m-01') }}');
        $('#vencimentoFinal').val('{{ date('Y-m-t') }}');

        $('#baixarExcel').on('click', function(){
            event.preventDefault();
            let periodo = $('#button-pai').text();
            let form = $('#formBuscaPadrao').serialize();
            let url = $(this).attr('href');
            showLoader();
            $.ajax({
                url: url,
                type: 'GET',
                data: form,
                xhrFields: {
                    responseType: 'blob'
                },
                success: function(data){
                    console.log(data)
                    let blob = new Blob([data], { type: 'application/vnd.ms-excel' });
                    let link = document.createElement('a');
                    link.href = window.URL.createObjectURL(blob);
                    link.download = "contas-receber.xls";
                    link.click();
                    hideLoader();
                },error: function(err) {
                    console.error('Erro ao gerar o Excel:', err);
                }
            });
        });

        $('#baixarPdf').on('click', function(){
            event.preventDefault();
            let periodo = $('#button-pai').text();
            let form = $('#formBuscaPadrao').serialize();
            let url = '{{ route('pdf-contas-receber') }}';
            showLoader();
            $.ajax({
                url: url,
                type: 'GET',
                data: form,
                xhrFields: {
                    responseType: 'blob'
                },
                success: function(data){
                    console.log(data)
                    let blob = new Blob([data], { type: 'application/pdf' });
                    let link = document.createElement('a');
                    link.href = window.URL.createObjectURL(blob);
                    link.download = "contas-receber.pdf";
                    link.click();
                    hideLoader();
                },error: function(err) {
                    console.error('Erro ao gerar o PDF:', err);
                }
            });
        });
    });
</script>
