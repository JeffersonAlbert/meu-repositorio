<script>
	$(document).ready(function(){
		let produto = null;
		$(document).on('click', '#adicionar-produtos', function(e){
			e.preventDefault();
			let $formContainer = $('#form-container');
            let i = $formContainer.children().length
			let $newRow = `<div class="row">
										<div class='col-3'>
											@include('form-parts.html.produtos-select')
										</div>
										<div class="col-2">
											<label class="label-number" for="observacao${i}">Detalhes do item</label>
											<input id="observacao${i}" name="observacao[]" type="text" class="input-login form-control" placeholder="" value="{{ isset($produto) ? $produto->quantidade : null }}">
										</div>
										<div class="col-2">
											<label class="label-number required" for="quantidade${i}">Quantidade</label>
											<input id="quantidade${i}" name="quantidade[]" type="text" class="input-login form-control quantidade" placeholder="0,00" value="{{ isset($produto) ? $produto->valor_custo : null }}">
										</div>
										<div class="col-2">
											<label class="label-number required" for="valor_unitario${i}">Valor unitário</label>
											<input id="valor_unitario${i}" name="valor_unitario[]" type="text" class="input-login form-control valor-unitario" placeholder="0" value="{{ isset($produto) ? $produto->estoque_maximo : null }}">
										</div>
										<div class="col-2">
											<label class="label-number required" for="valor_total${i}">Total</label>
											<input id="valor_total${i}" name="valor_total[]" type="text" class="total input-login form-control" placeholder="0" value="{{ isset($produto) ? $produto->estoque_minimo : null }}">
										</div>
										<div class="col-1 d-flex align-items-end">
											<button id="remove" class="btn btn-success btn-sm">X Remover</button>
										</div>
										<input type="hidden" class="produto" name="produto[]" value="">
									</div>`;
			$replacedNewRow = $newRow.replace(/dropdownProdutosButton/g, 'dropdownProdutosButton'+$formContainer.children().length)
                .replace(/id="dropdown-produtos-items"/g, 'id="dropdown-produtos-items'+$formContainer.children().length+'"')
                .replace(/dropdown-produtos-input/g, 'dropdown-produtos-input'+$formContainer.children().length);
			$formContainer.append($replacedNewRow);
		});

		$(document).on('click', '#remove', function(e){
			e.preventDefault();
			$(this).closest('.row').remove();
		});
	});
</script>
