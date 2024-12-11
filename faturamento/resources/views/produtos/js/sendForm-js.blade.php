<script>
    $(document).ready(function(){
        $('#enviarProduto').submit(function(e){
            e.preventDefault();
            let url = $(this).attr('action');
            showLoader();
            var dados = new FormData(this);
            $.ajax({
                type: "POST",
                url: url,
                data: dados,
                processData: false,
                contentType: false,
                success: function(data)
                {
                    hideLoader();
                    window.location.href = "{{ route('produto.index') }}";
                },
                error: function(xhr, status, error)
                {
                    var error = xhr.responseJSON.errors;
                    console.log(error);
                    var errorMessage = "";
                    $.each(error, function(field, messages) {
                        errorMessage += messages.join(", ")+"<br>";
                    });
                    $(".form-error").hide();
                    $(".form-error")
                        .html(`<p>${errorMessage}</p>`)
                        .addClass('alert alert-danger')
                        .show();
                    hideLoader();
                }
            });
            //return false;
        });
    })
</script>
