<script>
	$(document).ready(function(){
		console.log('tamo no input');

		$(document).on('input', '.dropdown-forma-pagamento-input', function() {
			var searchText = $(this).val().toLowerCase();

			// Encontra o contêiner dropdown correspondente
			var $dropdownContainer = $(this).closest('.forma-pagamento');
			var $itemsContainer = $dropdownContainer.find('.dropdown-forma-pagamento-items');
			var items = $itemsContainer.find('.dropdown-forma-pagamento-item').toArray();

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

		$(document).on('click', '.dropdown-forma-pagamento-item',function(e){
			e.preventDefault();

			// Obter o ID e o texto do item clicado
			let id = $(this).data('id');
			let text = $(this).text();

			// Truncar o texto se ele for maior que 22 caracteres
			var truncatedText = text.length > 22 ? text.substring(0, 22) + '...' : text;

			// Encontrar o dropdown correspondente e atualizar o texto do botão
			$(this).closest('.dropdown').find('.dropdown-number').text(truncatedText);

			// Atualizar o valor escondido (input hidden) com o ID selecionado
			$('.formaPagamentoVal').val(id);

			console.log(id + ' ' + text);
		});
	});
</script>
