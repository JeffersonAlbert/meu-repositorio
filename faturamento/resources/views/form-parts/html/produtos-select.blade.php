<label for="dropdownProdutosButton" class="label-number required">Produtos/Serviços</label>
<div class="dropdown">
    <button class="dropdown-number btn dropdown-toggle col-12 btn-transparent produto"
		type="button" id="dropdownProdutosButton"
		data-toggle="dropdown"
		aria-haspopup="true"
		aria-expanded="false">
        Selecione uma opção
    </button>
    <div class="dropdown-menu p-2 col"
		style="max-height: 400px; overflow-y: auto;"
		aria-labelledby="dropdownProdutosButton">
		<div
			style="position: sticky; top: 0; background-color: #f8f4f5; z-index: 100;">
        	<input type="text" id="dropdown-produtos-input" class="form-control dropdown-input" placeholder="Digite sua opção">
        	<div class="dropdown-divider"></div>
		</div>
        <div id="dropdown-produtos-items" class="dropdown-produtos-items">
        @foreach($produtos as $produto)
        <a href="#" data-id="{{ $produto->id}}" data-value="{{ $produto->valor }}"
			class="dropdown-produtos-item dropdown-item">{{ $produto->produto }}</a>
        @endforeach
        </div>
		<div style="position: sticky; bottom: 0; background-color: white; z-index: 100;">
            <div class="dropdown-divider mt-2"></div>
            <button type="button" id="add-produtos-btn" class="btn btn-sm btn-success w-100">Adicionar</button>
        </div>
    </div>
</div>
<input id='produtosVal' class="produto" name='produtoId[]' value='' type='hidden'>
