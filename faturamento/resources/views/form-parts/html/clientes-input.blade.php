<div class="dropdown">
    <button class="dropdown-number btn dropdown-toggle col-12 btn-transparent"
        type="button"
		id="dropdownClienteButton"
        data-toggle="dropdown"
        aria-haspopup="true"
        aria-expanded="false">
        Selecione um cliente
    </button>
    <div class="dropdown-menu p-2 col"
        style="max-height: 400px; overflow-y: auto;"
        aria-labelledby="dropdownMenuButton">
        <div
		style="position: sticky; top: 0; background-color: #f8f4f5; z-index: 100;">
            <input type="text" id="dropdown-clientes-input" class="form-control"
			    placeholder="Digite sua opção">
            <div class="dropdown-divider"></div>
        </div>
        <div id="dropdown-clientes-items">
            @foreach($clientes as $cliente)
            <a href="#" data-id="{{ $cliente->id }}"
                class="dropdown-cliente dropdown-item">{{ $cliente->nome }}</a>
            @endforeach
        </div>
        <div style="position: sticky; bottom: 0; background-color: white; z-index: 100;">
            <div class="dropdown-divider mt-2"></div>
            <button type="button" id="add-cliente-btn" class="btn btn-success btn-sm w-100">
                Adicionar
            </button>
        </div>
    </div>
</div>
<input id='clienteVal' name='clienteId' value='' type='hidden'>
