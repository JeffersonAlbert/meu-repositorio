$(document).ready(function() {
    $(document).on('input', '.dropdown-fornecedores-input',function() {

        var searchText = $(this).val().toLowerCase();
        var items = $(this).closest('.dropdown')
            .find('.dropdown-fornecedores-items .dropdown-item')
            .toArray();
        // Clear previous highlights and show all items
        items.forEach(item => {
            $(item).html($(item).text());
            $(item).show();
        });

        // Filter and sort items based on the input
        var matchedItems = items.filter(item => $(item).text().toLowerCase().includes(searchText));
        var unmatchedItems = items.filter(item => !$(item).text().toLowerCase().includes(searchText));

        // Move matched items to the top and highlight the matches
        $('.dropdown-fornecedores-items').empty();
        matchedItems.forEach(item => {
            var highlightedText = $(item).text().replace(new RegExp(searchText, "gi"), (match) => `<span class="highlight">${match}</span>`);
            $(item).html(highlightedText);
            $('.dropdown-fornecedores-items').append(item);
        });
        unmatchedItems.forEach(item => {
            $('.dropdown-fornecedores-items').append(item);
        });
    });

    /*$(document).on('click', '.dropdown-fornecedor',function(e){
        e.preventDefault();
        let id = $(this).data('id');
        let text = $(this).text();
        console.log(id +' '+ text);
        var truncatedText = text.length > 22 ? text.substring(0, 22) + '...' : text;
        //$('#dropdownFornecedorButton').text(truncatedText);
        $(this).closest('.dropdown').find('.dropdown-number').text(truncatedText);
        $(this).closest('.row').find('#fornecedorVal').val(id);
        $(this).closest('.row').find('#fornecedorValName').val(text);
    });*/

    $(document).on('click', '.periodo',function(e){
        e.preventDefault();
        let text = $(this).text();
        $('.dropdownPeriodoButton').text(text);
    });

    $(document).on('click', '.relatorio',function(e){
        e.preventDefault();
        let text = $(this).text();
        console.log(text);
        $('.dropdownRelatorioButton').text(text);
    });

    $(document).on('input', '.dropdown-categoria-financeira-input',function() {
        var searchText = $(this).val().toLowerCase();
        var items = $('.dropdown-categoria-financeira-items .dropdown-item').toArray();
        // Clear previous highlights and show all items
        items.forEach(item => {
            $(item).html($(item).text());
            $(item).show();
        });

        // Filter and sort items based on the input
        var matchedItems = items.filter(item => $(item).text().toLowerCase().includes(searchText));
        var unmatchedItems = items.filter(item => !$(item).text().toLowerCase().includes(searchText));

        // Move matched items to the top and highlight the matches
        $('.dropdown-categoria-financeira-items').empty();
        matchedItems.forEach(item => {
            var highlightedText = $(item).text().replace(new RegExp(searchText, "gi"), (match) => `<span class="highlight">${match}</span>`);
            $(item).html(highlightedText);
            $('.dropdown-categoria-financeira-items').append(item);
        });
        unmatchedItems.forEach(item => {
            $('.dropdown-categoria-financeira-items').append(item);
        });
        console.log(items);
    });

    /*$(document).on('click', '.dropdown-categoria-financeira-item',function(e){
        e.preventDefault();
        let id = $(this).data('id');
        let text = $(this).text();
        console.log(id +' '+ text);
        var truncatedText = text.length > 22 ? text.substring(0, 22) + '...' : text;
        //$('#dropdownFornecedorButton').text(truncatedText);
        $(this).closest('.dropdown').find('.dropdown-number').text(truncatedText);
        $(this).closest('.row').find('#categoriaFinanceiraVal').val(id);
    });*/

    $(document).on('input', '.dropdown-tipo-cobranca-input',function() {
        var searchText = $(this).val().toLowerCase();
        var items = $('.dropdown-tipo-cobranca-items .dropdown-item').toArray();
        // Clear previous highlights and show all items
        items.forEach(item => {
            $(item).html($(item).text());
            $(item).show();
        });

        // Filter and sort items based on the input
        var matchedItems = items.filter(item => $(item).text().toLowerCase().includes(searchText));
        var unmatchedItems = items.filter(item => !$(item).text().toLowerCase().includes(searchText));

        // Move matched items to the top and highlight the matches
        $('.dropdown-tipo-cobranca-items').empty();
        matchedItems.forEach(item => {
            var highlightedText = $(item).text().replace(new RegExp(searchText, "gi"), (match) => `<span class="highlight">${match}</span>`);
            $(item).html(highlightedText);
            $('.dropdown-tipo-cobranca-items').append(item);
        });
        unmatchedItems.forEach(item => {
            $('.dropdown-tipo-cobranca-items').append(item);
        });
        console.log(items);
    });


    $(document).on('click', '.dropdown-tipo-cobranca-item',function(e){
        e.preventDefault();
        let id = $(this).data('id');
        let text = $(this).text();
        console.log(id +' '+ text);
        var truncatedText = text.length > 22 ? text.substring(0, 22) + '...' : text;
        //$('#dropdownFornecedorButton').text(truncatedText);
        $(this).closest('.dropdown').find('.dropdown-number').text(truncatedText);
        $(this).closest('.row').find('#billingType').val(id);
    });

    $(document).on('input', '.dropdown-workflow-input', function() {
        var searchText = $(this).val().toLowerCase();
        var items = $('.dropdown-workflow-items .dropdown-item').toArray();
        // Clear previous highlights and show all items
        items.forEach(item => {
            $(item).html($(item).text());
            $(item).show();
        });

        // Filter and sort items based on the input
        var matchedItems = items.filter(item => $(item).text().toLowerCase().includes(searchText));
        var unmatchedItems = items.filter(item => !$(item).text().toLowerCase().includes(searchText));

        // Move matched items to the top and highlight the matches
        $('.dropdown-workflow-items').empty();
        matchedItems.forEach(item => {
            var highlightedText = $(item).text().replace(new RegExp(searchText, "gi"), (match) => `<span class="highlight">${match}</span>`);
            $(item).html(highlightedText);
            $('.dropdown-workflow-items').append(item);
        });
        unmatchedItems.forEach(item => {
            $('.dropdown-workflow-items').append(item);
        });
        console.log(items);
    });

    $(document).on('click', '.dropdown-workflow-item',function(e){
        e.preventDefault();
        let id = $(this).data('id');
        let text = $(this).text();
        console.log(id +' '+ text);
        var truncatedText = text.length > 22 ? text.substring(0, 22) + '...' : text;
        //$('#dropdownFornecedorButton').text(truncatedText);
        $(this).closest('.dropdown').find('.dropdown-number').text(truncatedText);
        $(this).closest('.row').find('#workflow').val(id);
    });

    $(document).on('input', '.dropdown-centro-custo-input', function() {
        var searchText = $(this).val().toLowerCase();
        var items = $('.dropdown-centro-custo-items .dropdown-item').toArray();
        // Clear previous highlights and show all items
        items.forEach(item => {
            $(item).html($(item).text());
            $(item).show();
        });

        // Filter and sort items based on the input
        var matchedItems = items.filter(item => $(item).text().toLowerCase().includes(searchText));
        var unmatchedItems = items.filter(item => !$(item).text().toLowerCase().includes(searchText));

        // Move matched items to the top and highlight the matches
        $('.dropdown-centro-custo-items').empty();
        matchedItems.forEach(item => {
            var highlightedText = $(item).text().replace(new RegExp(searchText, "gi"), (match) => `<span class="highlight">${match}</span>`);
            $(item).html(highlightedText);
            $('.dropdown-centro-custo-items').append(item);
        });
        unmatchedItems.forEach(item => {
            $('.dropdown-centro-custo-items').append(item);
        });
        console.log(items);
    });

    $(document).on('click', '.dropdown-centro-custo-item',function(e){
        e.preventDefault();
        let id = $(this).data('id');
        let text = $(this).text();
        console.log(id +' '+ text);
        var truncatedText = text.length > 22 ? text.substring(0, 22) + '...' : text;
        //$('#dropdownFornecedorButton').text(truncatedText);
        $(this).closest('.dropdown').find('.dropdown-number').text(truncatedText);
        $(this).closest('.row').find('#centerCost').val(id);
    });

    $(document).on('input', '.dropdown-centro-custo-select-input', function() {
        var searchText = $(this).val().toLowerCase();
        var items = $(this).closest('.dropdown')
            .find('.dropdown-centro-custo-select-items .dropdown-item')
            .toArray();
        // Clear previous highlights and show all items
        items.forEach(item => {
            $(item).html($(item).text());
            $(item).show();
        });

        // Filter and sort items based on the input
        var matchedItems = items.filter(item => $(item).text().toLowerCase().includes(searchText));
        var unmatchedItems = items.filter(item => !$(item).text().toLowerCase().includes(searchText));

        // Move matched items to the top and highlight the matches
        $('.dropdown-centro-custo-select-items').empty();
        matchedItems.forEach(item => {
            var highlightedText = $(item).text().replace(new RegExp(searchText, "gi"), (match) => `<span class="highlight">${match}</span>`);
            $(item).html(highlightedText);
            $('.dropdown-centro-custo-select-items').append(item);
        });
        unmatchedItems.forEach(item => {
            $('.dropdown-centro-custo-select-items').append(item);
        });
        console.log(items);
    });

    $(document).on('click', '.dropdown-centro-custo-select-item',function(e){
        e.preventDefault();
        let id = $(this).data('id');
        let text = $(this).text();
        console.log(id +' '+ text);
        var truncatedText = text.length > 22 ? text.substring(0, 22) + '...' : text;
        //$('#dropdownFornecedorButton').text(truncatedText);
        $(this).closest('.dropdown').find('.dropdown-number').text(truncatedText);
        $(this).closest('.row').find('#centerCost').val(id);
    });

    $(document).on('input', '.dropdown-bancos-input', function() {
        var searchText = $(this).val().toLowerCase();
        var items = $(this).closest('.dropdown')
            .find('.dropdown-bancos-items .dropdown-item')
            .toArray();
        // Clear previous highlights and show all items
        items.forEach(item => {
            $(item).html($(item).text());
            $(item).show();
        });

        // Filter and sort items based on the input
        var matchedItems = items.filter(item => $(item).text().toLowerCase().includes(searchText));
        var unmatchedItems = items.filter(item => !$(item).text().toLowerCase().includes(searchText));

        // Move matched items to the top and highlight the matches
        $('.dropdown-bancos-items').empty();
        matchedItems.forEach(item => {
            var highlightedText = $(item).text().replace(new RegExp(searchText, "gi"), (match) => `<span class="highlight">${match}</span>`);
            $(item).html(highlightedText);
            $('.dropdown-bancos-items').append(item);
        });
        unmatchedItems.forEach(item => {
            $('.dropdown-bancos-items').append(item);
        });
        console.log(items);
    });
});
