<script>
	$(document).ready(function(){
		$(document).on('click', '.dropdown-recebimento-item',function(e){
			e.preventDefault();

			// Obter o ID e o texto do item clicado
			let id = $(this).data('id');
			let text = $(this).text();

			// Truncar o texto se ele for maior que 22 caracteres
			var truncatedText = text.length > 22 ? text.substring(0, 22) + '...' : text;

			// Encontrar o dropdown correspondente e atualizar o texto do botão
			$(this).closest('.dropdown').find('.dropdown-number').text(truncatedText);

			// Atualizar o valor escondido (input hidden) com o ID selecionado
			$('#contaRecebimentoVal').val(id);

			console.log(id + ' ' + text);
		});
	});
</script>
