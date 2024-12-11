<script>
$(document).on('click', 'a.periodo', function(){
    let periodo = $('#button-pai').text();
    let dateRange = convertDateRange(periodo);
    console.log(dateRange);
    $('#vencimentoInicial').val(dateRange.startDate);
    $('#vencimentoFinal').val(dateRange.endDate);
    $('#formBuscar').submit();
});
</script>
