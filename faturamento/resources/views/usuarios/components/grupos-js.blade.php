<script>
    $(document).ready(function() {
        $('#dropdownInput').on('input', function() {
            var searchText = $(this).val().toLowerCase();
            var items = $('#dropdownItems .dropdown-item').toArray();

            // Clear previous highlights and show all items
            items.forEach(item => {
                $(item).html($(item).text());
                $(item).show();
            });

            // Filter and sort items based on the input
            var matchedItems = items.filter(item => $(item).text().toLowerCase().includes(searchText));
            var unmatchedItems = items.filter(item => !$(item).text().toLowerCase().includes(
                searchText));

            // Move matched items to the top and highlight the matches
            $('#dropdownItems').empty();
            matchedItems.forEach(item => {
                var highlightedText = $(item).text().replace(new RegExp(searchText, "gi"), (
                    match) => `<span class="highlight">${match}</span>`);
                $(item).html(highlightedText);
                $('#dropdownItems').append(item);
            });
            unmatchedItems.forEach(item => {
                $('#dropdownItems').append(item);
            });
        });

        $(document).on('click', '.dropdown-item', function(e) {
            e.preventDefault();
            let id = $(this).data('id');
            let text = $(this).text();
            console.log(id + ' ' + text);
            var truncatedText = text.length > 18 ? text.substring(0, 15) + '...' : text;
            $('#dropdownMenuButton').text(truncatedText);

            if ($('#grupoVal .input-tag input[name="grupo[]"][value="' + id + '"]').length === 0) {

                if ($('.group-alert').length > 0) {
                    $('.group-alert').remove();
                }


                $('#grupoVal').append("<div class='input-tag'><input value='" + text +
                    "' readonly><span class='remove-tag'>&times;</span><input name='grupo[]' value='" +
                    id + "' type='hidden'></div>");
            } else {
                $('.form-workflow-error').html(
                    '<div class="alert alert-danger group-alert">Grupo ja adicionado</div>');
            }
        });

        $(document).on('click', '.remove-tag', function() {
            $(this).parent('.input-tag').remove();
        });
    });
</script>
