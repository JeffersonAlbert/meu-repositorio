<script>
	$(document).ready(function(){
		$(document).on('click', '#add-centro-custo-btn', function(){
			//abrir um modal aqui
			$('#modal-cad-centro-custo').modal('show');
		});

		$(document).on('click', '#voltarCentroCusto', function(){
			$('#modal-cad-centro-custo').modal('hide');
			$('#nome').val('');
			$('#descricao_centro_custo').val('');
			$('#inserirCentroCusto').attr('disabled', false);
		});

		$(document).on('input', '#nome_centro_custo', function(){
			if($(this).val() != ''){
				$('#inserirCentroCusto').attr('disabled', false);
			}else{
				$('#inserirCentroCusto').attr('disabled', true);
			}
		});

		$(document).on('click', '#inserirCentroCusto', function(e){
			e.preventDefault();
			showLoader();
			$('#inserirCentroCusto').attr('disabled', true);
			let form = new FormData($('#enviaCentroCusto')[0]);
			$.ajax({
				url: $('#enviaCentroCusto').attr('action'),
				type: 'POST',
				data: form,
				processData: false,
				contentType: false,
				success: function(result){
					$('#modal-cad-centro-custo').modal('hide');
					$('#nome_centro_custo').val('');
					$('#descricao_centro_custo').val('');
					let centroCustoId = result.success.id;
					let centroCustoNome = result.success.nome;
					let htmlAppend = `<a class="dropdown-item dropdown-centro-custo-item" data-id="${centroCustoId}" href="#">${centroCustoNome}</a>`;
					$('#dropdown-centro-custo-items').append(htmlAppend);
					hideLoader();
				},
				error: function(xhr, status, error){
					console.log(xhr.responseText);
				} 
			});
		});

		$('#dropdown-centro-custo-input').on('input', function() {
			  var searchText = $(this).val().toLowerCase();
			  var items = $('#dropdown-centro-custo-items .dropdown-item').toArray();
  
			  // Clear previous highlights and show all items
			  items.forEach(item => {
				  $(item).html($(item).text());
				  $(item).show();
			  });
  
			  // Filter and sort items based on the input
			  var matchedItems = items.filter(item => $(item).text().toLowerCase().includes(searchText));
			  var unmatchedItems = items.filter(item => !$(item).text().toLowerCase().includes(searchText));
  
			  // Move matched items to the top and highlight the matches
			  $('#dropdown-centro-custo-items').empty();
			  matchedItems.forEach(item => {
				  var highlightedText = $(item).text().replace(new RegExp(searchText, "gi"), (match) => `<span class="highlight">${match}</span>`);
				  $(item).html(highlightedText);
				  $('#dropdown-centro-custo-items').append(item);
			  });
			  unmatchedItems.forEach(item => {
				  $('#dropdown-centro-custo-items').append(item);
			  });
		  });
  
		  $(document).on('click', '.dropdown-centro-custo-item',function(e){
			  e.preventDefault();
			  let id = $(this).data('id');
			  let text = $(this).text();
			  console.log(id +' '+ text);
			  var truncatedText = text.length > 22 ? text.substring(0, 22) + '...' : text;
			  $('#dropdownCentroCustoButton').text(truncatedText);
			  $('#centroCustoVal').val(id);
		  });
		
	});
</script>