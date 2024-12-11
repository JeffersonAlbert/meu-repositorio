<script>
    $(document).ready(function(){
        $(document).on('submit', '#enviarVendaOuOrcamento', function(e){
            showLoader();
            e.preventDefault();
            console.log('teste');
            let form = new FormData(this);
            let url = $(this).attr('action');
            $.ajax({
                type: 'POST',
                url: url,
                data: form,
                processData: false,
                contentType: false,
                success: function(response) {
                    console.log(response);
                    window.location.href = response.success.redirect;
                },
                error: function(xhr, status, error) {
                    console.log(xhr.responseJSON.errors);
                    var errors = xhr.responseJSON.errors;
                    var errorMessage = "";
                    $.each(errors, function(field, messages) {
                        errorMessage += messages.join(", ")+"<br>";
                    });
                    $('.form-error').empty().addClass('alert alert-danger').append(errorMessage).show();
                    hideLoader();
                }
            });
        });
    })
</script>
