<script>
    $(document).ready(function(){
        $('#periodo').on('change', function(){
            var periodo = $(this).val();
            if(periodo == 'yearly') {
                $('#container').show();
                $('#container2').show();
            } else {
                $('#container').show();
                $('#container2').hide();
            }
        });

        $("#gerarRelatorio").on('click', function(){
            var period = $('#periodo').val();
            var startYear = $('#startYear').val();
            var endYear = $('#endYear').val();
            var url = '{{ route('relatorio.dre', [':periodo', ':startYear', ':endYear']) }}';
            url = url.replace(':periodo', period)
                .replace(':startYear', startYear)
                .replace(':endYear', endYear);
            window.location.href = url;
        });
    })
</script>
