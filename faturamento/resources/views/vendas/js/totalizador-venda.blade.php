<script>
	$(document).ready(function(){

		$(document).on('blur', '.quantidade', function(){
			let $row = $(this).closest('.row');

			// Obtém os valores dos campos da linha atual
			let valorUnitario = $row.find('.valor-unitario').val();
			let quantidade = $(this).val();

			// Converte os valores para números, garantindo que sejam válidos
			valorUnitario = parseFloat(valorUnitario) || 0;
			quantidade = parseFloat(quantidade) || 0;

			// Calcula o total
			let total = valorUnitario * quantidade;

			// Atualiza o campo total na mesma linha
			$row.find('.total').val(formatCurrency(total).replace('R$', '').trim());

			atualizarTotalItens();
			atualizarDesconto();
			atualizarItens();
		});

		$(document).on('blur', '#frete', function(){
			atualizarFrete();
			atualizarDesconto();
			atualizarItens();
		});

		$(document).on('click', '#remove', function(e){
			e.preventDefault();
			$(this).closest('.row').remove();
			atualizarTotalItens();
			atualizarDesconto();
			atualizarItens();
		});

		$(document).on('click', '.dropdown-produtos-item', function(e){
			console.log('click');
			e.preventDefault();
			let valor = formatCurrency($(this).data('value')).replace('R$','').trim();
			let lastRow = $('#form-container .row').last();
    		lastRow.find('.valor-unitario').val(valor);
		});

		$(document).on('blur', '#desconto', function(){
			atualizarDesconto();
			atualizarItens();
		});

		function atualizarItens() {
			let totalItems = 0;
			$('.quantidade').each(function() {
				let valor = parseFloat($(this).val()) || 0;
				totalItems += valor;
			});

			$("#produtos").text(`${totalItems} produtos`);
		}

		function atualizarTotalItens() {
			let totalGeral = 0;

			// Itera sobre cada campo com a classe .total e soma seus valores
			$('.total').each(function() {
				let valor = unFormatMoney($(this).val()) || 0;
				totalGeral = parseFloat(valor)+parseFloat(totalGeral);
			});

			let formattedTotal = formatCurrency(totalGeral).replace('R$', '').trim();
			$('#valor_itens').text('R$ '+formattedTotal);
			$('#totalizadorItens').val(formattedTotal);
			atualizarTotal();
		}

		function atualizarFrete() {
			let frete = $('#frete').val()|| 0;
			$('#valor_adicional').text(formatCurrency(frete));
			$('#totalizadorValorAdicional').val(formatCurrency(frete).replace('R$', '').trim());
			atualizarTotal();
		}

		function atualizarDesconto() {
            showLoader();
			let desconto = null;
			$('.desconto.ativo').each(function() {
				if ($(this).hasClass('btn-currency')) {
					console.log('O botão ativo é do tipo: btn-currency');
					console.log($('#desconto').val());
					desconto = parseFloat($('#desconto').val()) || 0;
				} else if ($(this).hasClass('btn-percent')) {
					console.log('O botão ativo é do tipo: btn-percent');
					console.log($('#desconto').val());
					//precisa calcular o valor do desconto do id valor_items + valor adiconal e aplicar o desconto
					let totalGeral = parseFloat(unFormatMoney($('#totalizadorItens').val())) || 0;
					let frete = parseFloat($('#valor_adicional').text()) || 0;
					let descontoPercent = parseFloat($('#desconto').val()) || 0;
					let valorDesconto = (totalGeral) * (descontoPercent / 100);
					desconto = valorDesconto;
					console.log(totalGeral+' + '+frete+" - "+descontoPercent+"% = "+valorDesconto);
				} else {
					console.log('Nenhum botão ativo');
					desconto = 0;
				}
			});
            hideLoader();

			console.log(desconto);
			$('#valor_desconto').text(formatCurrency(desconto));
			$('#totalizadorDesconto').val(formatCurrency(desconto).replace('R$', '').trim());
			atualizarTotal();
		}

		function atualizarTotal() {
			let totalGeral = parseFloat(unFormatMoney($('#totalizadorItens').val())) || 0;
			let frete = parseFloat(unFormatMoney($('#totalizadorValorAdicional').val())) || 0;
			let desconto = parseFloat(unFormatMoney($('#totalizadorDesconto').val())) || 0;
			let total = totalGeral + frete - desconto;
			console.log(totalGeral+' + '+frete+' - '+desconto+' = '+total);
			$('#valor_total_venda').text(formatCurrency(total));
			$('#valor_receber').val(formatCurrency(total).replace('R$', '').trim());
			$('#totalizadorValor').val(formatCurrency(total).replace('R$', '').trim());
		}

	});
</script>
