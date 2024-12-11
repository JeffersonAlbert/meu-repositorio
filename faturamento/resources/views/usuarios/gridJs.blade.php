<script>
    $(document).ready(function() {
        $('.openConfirmModalDisable').click(function() {
            var id = $(this).data('id');

            $('#confirmModalDisable').modal('show');
            $('#userId').val(id);
            $('#confirmModalDisable').modal('show');
        });

        $('.openConfirmModalEnable').click(function() {
            var id = $(this).data('id');

            $('#confirmModalEnable').modal('show');
            $('#userId').val(id);
            $('#confirmModalEnable').modal('show');
        });

        $(".btn-fechar").click(function() {
            $('#confirmModalEnable').modal('hide');
            $('#confirmModalDisable').modal('hide');

        });

        $('#confirmDesabilitar').click(function() {
            $('#confirmModalDisable').modal('hide');
            var route = "{{ route('usuarios.disable', ['usuario' => ':id', 'tipo' => 'disable' ]) }}";

            $.get(route.replace(':id', $('#userId').val()), function(response) {
                console.log(response);
                if (response.error) {
                    // Itera sobre as mensagens de erro
                    response.error.message.forEach(function(errorMessage) {
                        // Faça o que for necessário com cada mensagem de erro
                        $(".mensagem-erro").removeClass('alert-danger');
                        $(".mensagem-erro").removeClass('alert-success');
                        $(".mensagem-erro").html(errorMessage).addClass('alert alert-danger').show();
                    });
                }

                if (response.success) {
                    response.success.message.forEach(function(successMessage) {
                        console.log(successMessage);
                        $(".mensagem-erro").removeClass('alert-danger');
                        $(".mensagem-erro").removeClass('alert-success');
                        $(".mensagem-erro").html(successMessage).addClass('alert alert-success').show();
                        window.location.reload();
                    });
                }
            })
        });

        $('#confirmHabilitar').click(function() {
            $('#confirmModalEnable').modal('hide');
            var route = "{{ route('usuarios.disable', ['usuario' => ':id', 'tipo' => 'enable']) }}";

            $.get(route.replace(':id', $('#userId').val()), function(response) {
                console.log(response);
                if (response.error) {
                    // Itera sobre as mensagens de erro
                    response.error.message.forEach(function(errorMessage) {
                        // Faça o que for necessário com cada mensagem de erro
                        $(".mensagem-erro").removeClass('alert-danger');
                        $(".mensagem-erro").removeClass('alert-success');
                        $(".mensagem-erro").html(errorMessage).addClass('alert alert-danger').show();
                    });
                }

                if (response.success) {
                    response.success.message.forEach(function(successMessage) {
                        console.log(successMessage);
                        $(".mensagem-erro").removeClass('alert-danger');
                        $(".mensagem-erro").removeClass('alert-success');
                        $(".mensagem-erro").html(successMessage).addClass('alert alert-success').show();
                        window.location.reload();
                    });
                }
            })
        });
    });
</script>
