
<script>
	$(document).ready(function(){
		$(document).on('click', '#add-produtos-btn', function(){
			$('#modal-cad-produtos').modal('show');
		});

		$(document).on('click', '#voltarProduto', function(){
			$('#modal-cad-produtos').modal('hide');
		});

		$(document).on('input', '#nomeProduto', function(){
			if($(this).val() != ''){
				$('#inserirProduto').attr('disabled', false);
			}else{
				$('#inserirProduto').attr('disabled', true);
			}
		});

		$(document).on('click', '#inserirProduto', function(e){
			e.preventDefault();
			showLoader();
			$.ajax({
				url: "{{ route('produto.store') }}",
				type: "POST",
				data: $('#enviaProduto').serialize(),
				success: function(response){
					console.log(response);
					$('#modal-cad-produtos').modal('hide');
					let produtoId = response.success.id;
					let produtoNome = response.success.produto;
					let htmlAppend = `<a class="dropdown-item dropdown-produtos-item" data-id="${produtoId}" href="#">${produtoNome}</a>`;
					$('#dropdown-produtos-items').append(htmlAppend);
					console.log(htmlAppend);
					hideLoader();
					console.log('sucesso');
				},
				error: function(response){
					console.log('erro');
					$('.messages-modal-cad-produtos').html('');
					$.each(response.responseJSON.errors, function(key, value){
						$('.messages-modal-cad-produtos').append('<div class="alert alert-danger">'+value+'</div>');
					});
					hideLoader();
				}
			});
		});

		$(document).on('input', '.dropdown-input', function() {
			var searchText = $(this).val().toLowerCase();

			// Encontra o contêiner dropdown correspondente
			var $dropdownContainer = $(this).closest('.dropdown-menu');
			var $itemsContainer = $dropdownContainer.find('.dropdown-produtos-items');
			var items = $itemsContainer.find('.dropdown-item').toArray();

			// Limpa os destaques anteriores e mostra todos os itens
			items.forEach(item => {
				$(item).html($(item).text());
				$(item).show();
			});

			// Filtra e organiza os itens com base no input
			var matchedItems = items.filter(item => $(item).text().toLowerCase().includes(searchText));
			var unmatchedItems = items.filter(item => !$(item).text().toLowerCase().includes(searchText));

			// Move os itens correspondentes para o topo e destaca as correspondências
			$itemsContainer.empty();
			matchedItems.forEach(item => {
				var highlightedText = $(item).text().replace(new RegExp(searchText, "gi"), (match) => `<span class="highlight">${match}</span>`);
				$(item).html(highlightedText);
				$itemsContainer.append(item);
			});
			unmatchedItems.forEach(item => {
				$itemsContainer.append(item);
			});
		  });

		  $(document).on('click', '.dropdown-produtos-item',function(e){
                e.preventDefault();

                // Obter o ID e o texto do item clicado
                let id = $(this).data('id');
                let text = $(this).text();

                // Truncar o texto se ele for maior que 22 caracteres
                var truncatedText = text.length > 22 ? text.substring(0, 22) + '...' : text;

                // Encontrar o dropdown correspondente e atualizar o texto do botão
                $(this).closest('.dropdown').find('.dropdown-number').text(truncatedText);

                // Atualizar o valor escondido (input hidden) com o ID selecionado
                $(this).closest('.row').find('.produto').val(id);

                console.log(id + ' ' + text);
		  });
	});
</script>
