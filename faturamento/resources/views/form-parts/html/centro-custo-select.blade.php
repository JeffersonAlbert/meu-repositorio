<div class="dropdown">
    <button class="dropdown-number btn dropdown-toggle col-12 btn-transparent"
		type="button" id="dropdownCentroCustoButton"
		data-toggle="dropdown"
		aria-haspopup="true"
		aria-expanded="false">
        Selecione uma opção
    </button>
    <div class="dropdown-menu p-2 col"
		style="max-height: 400px; overflow-y: auto;"
		aria-labelledby="dropdownCentroCustoButton">
        <div
		style="position: sticky; top: 0; background-color: #f8f4f5; z-index: 100;">
            <input type="text" id="dropdown-centro-custo-input" class="form-control mb-2" placeholder="Digite sua opção">
            <div class="dropdown-divider mb-2"></div>
        </div>
        <div id="dropdown-centro-custo-items" style="max-height: 200px; overflow-y: auto;">
            @foreach($centroCustos as $centroCusto)
            <a href="#" data-id="{{ $centroCusto->id}}"
				class="dropdown-centro-custo-item dropdown-item">{{ $centroCusto->nome }}</a>
            @endforeach
        </div>
        <div style="position: sticky; bottom: 0; background-color: white; z-index: 100;">
            <div class="dropdown-divider mt-2"></div>
            <button type="button" id="add-centro-custo-btn" class="btn btn-success btn-sm w-100">
				Adicionar
			</button>
        </div>
    </div>
</div>
<input id='centroCustoVal' name='centroCusto' value='' type='hidden'>
