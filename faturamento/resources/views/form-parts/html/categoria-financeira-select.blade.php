<div class="dropdown">
    <button class="dropdown-number btn dropdown-toggle col-12 btn-transparent"
		type="button" id="dropdownCategoriaButton"
		data-toggle="dropdown"
		aria-haspopup="true"
		aria-expanded="false">
        Selecione uma categoria financeira
    </button>
    <div class="dropdown-menu p-2 col"
		style="max-height: 400px; overflow-y: auto;"
		aria-labelledby="dropdownCategoriaButton">
		<div
			style="position: sticky; top: 0; background-color: #f8f4f5; z-index: 100;">
        	<input type="text" id="dropdown-categoria-financeira-input" class="form-control" placeholder="Digite sua opção">
        	<div class="dropdown-divider"></div>
		</div>
        <div id="dropdown-categoria-financeira-items">
        @foreach($dre as $subDre)
        <a href="#" data-id="{{ $subDre->sub_id}}"
			class="dropdown-categoria-financeira-item dropdown-item">{{ $subDre->sub_desc }}</a>
        @endforeach
        </div>
		<div style="position: sticky; bottom: 0; background-color: white; z-index: 100;">
            <div class="dropdown-divider mt-2"></div>
            <button type="button" id="add-categoria-financeira-btn" class="btn btn-sm btn-success w-100">Adicionar</button>
        </div>
    </div>
</div>
<input id='categoriaFinanceiraVal' name='sub_categoria_dre' value='' type='hidden'>
