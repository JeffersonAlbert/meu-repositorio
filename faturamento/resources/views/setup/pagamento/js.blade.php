<script>
$(document).ready(function(){
    $("#enviarFormaPagamento").on('click', function(){
        showLoader();
        let form = new FormData(document.getElementById('formaPagamentoForm')); // Use document.getElementById para obter o formulário
        let action = $("#formaPagamentoForm").attr("action");
        sendFormPost(action, form);
    });
});
</script>
