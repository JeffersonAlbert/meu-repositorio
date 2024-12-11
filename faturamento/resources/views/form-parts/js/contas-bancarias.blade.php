<script>
	$(document).ready(function(){
		$(document).on('input change', '#banco_nome, #banco_agencia, #banco_conta', function(){
			let banco_nome = $('#banco_nome').val();
			let banco_agencia = $('#banco_agencia').val();
			let banco_conta = $('#banco_conta').val();
			if(banco_nome != '' && banco_agencia != '' && banco_conta != ''){
				$('#inserirContaBancaria').attr('disabled', false);
			}			
		});

		$(document).on('click', '#add-conta-recebimento-btn', function(){
			$('#modal-cad-conta-bancaria').modal();
		});

		$(document).on('submit', '#enviarContaBancaria', function(e){
			e.preventDefault(); // Previne o comportamento padrão de envio do formulário
			console.log('submit');
			$('#inserirContaBancaria').attr('disabled', true);
			showLoader();

			let form = new FormData(this); // Cria o FormData a partir do elemento DOM
			let route = $(this).attr('action');
			
			$.ajax({
				url: route,
				type: 'POST',
				data: form,
				processData: false,
				contentType: false,
				success: function(data){
					$('#modal-cad-conta-bancaria').modal('hide');
					$('#banco_nome').val('');
					$('#banco_agencia').val('');
					$('#banco_conta').val('');
					console.log(data);
					let bancoId = data.id;
					let bancoNome = data.nome;
					let htmlAppend = `<a class="dropdown-item dropdown-bancos-item" data-id="${bancoId}" href="#">${bancoNome}</a>`;
					$('#dropdown-recebimento-items').append(htmlAppend); // Insere o novo item no dropdown
					hideLoader();
				},
				error: function(data){
					$('.messages-modal-cad-banco').html('');
					$.each(data.responseJSON.errors, function(key, value){
						$('.messages-modal-cad-banco').append('<div class="alert alert-danger">'+value+'</div>');
					});
					hideLoader();
				}
			});
		});
	})
</script>