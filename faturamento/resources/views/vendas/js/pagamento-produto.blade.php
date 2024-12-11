<script>
	$(document).ready(function(){
		function checkConditions() {
			const condicaoPagamentoPrazo = $('#condicao_pagamento').val() === 'prazo';
			if (condicaoPagamentoPrazo) {
				$('#parcelas').prop('disabled', false);
				updateParcelas();
			} else {
				$('#parcelas').prop('disabled', true);
				$('#parcelas').val('1');
				updateParcelas();
			}
		}

		$('#condicao_pagamento').on('input change', function() {
			checkConditions();
		});


		$('#parcelas').on('blur', function() {
			updateParcelas();
		});


        $(document).on('blur', '.dinamicPercent', function() {
            let counter = $(this).data('counter');
            calcularParcelaPelaPorcentagem(counter);
        });

        function calcularParcelaPelaPorcentagem(counter) {
            let percentualAtual = parseFloat($('#representacao_percent' + counter).val()) || 0;
            let valorTotal = parseFloat(unFormatMoney($('#totalizadorValor').val())) || 0;
            let valorParcelaAtual = (valorTotal * percentualAtual) / 100;

            // Atualiza o valor da parcela atual
            $('#valor_receber' + counter).val(formatCurrency(valorParcelaAtual).replace('R$', '').trim());
        }

		function updateParcelas() {
			showLoader();
			const parcelas = $('#parcelas').val();
			const valorTotal = parseFloat(unFormatMoney($('#totalizadorValor').val()));
			let valorParcelas = formatCurrency((valorTotal)/(parcelas)).replace('R$', '').trim();
			let data_primeira_parcela = $('#dataVencimentoHoje').val();
			let html = '';
			let date = new Date();
			for (let i = 1; i < (parseFloat(parcelas)+1); i++) {
                let representacaoPercent = (((valorTotal/parcelas) / valorTotal) * 100).toFixed(2);
                let newDate = new Date(data_primeira_parcela);// Cria um novo objeto Date com a mesma data que a data original
				newDate.setMonth(newDate.getMonth() + (i)); // Adiciona o número de meses correspondente ao valor de i
				newDate.setFullYear(date.getFullYear() + Math.floor((date.getMonth() + (i+1)) / 12)); // Adiciona o número de anos correspondente ao valor de i
				let formattedDate = newDate.toISOString().substr(0, 10);
				html += `<div class="row">
                            <div class="col-2">
								<label class="label-number" for="data_vencimento">Data vencimento</label>
								<input id="data_vencimento${i}" name="data_vencimento[]" type="date" class="input-login form-control" value="${formattedDate}">
							</div>
							<div class="col-2">
								<label class="label-number" for="valor_receber">Valor a receber</label>
								<input id="valor_receber${i}" name="valor_receber[]" type="text" class="input-login form-control" placeholder="0,00" value="${valorParcelas}">
							</div>
                            <div class="col-2">
                                <label class="label-number" for="representacao_percent">Percentual</label>
                                <input id="representacao_percent${i}" name="representacao_percent[]"
                                    type="text" class="input-login form-control dinamicPercent"
                                    data-counter="${i}"
                                    value="${representacaoPercent}">
                            </div>
							<div class="col">
									<label class="label-number" for="descricao">Descricao</label>
									<input id="descricao" name="descricao[]" class="input-login form-control"></input>
							</div>
						</div>`;
			}
			$('#parcelasRows').empty().append(html);
			hideLoader();
		}
	})
</script>
