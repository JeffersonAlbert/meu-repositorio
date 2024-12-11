<div class="dropdown">
    <button class="dropdown-number btn dropdown-toggle col-12 btn-transparent"
        type="button"
		id="dropdownFornecedorButton"
        data-toggle="dropdown"
        aria-haspopup="true"
        aria-expanded="false">
        Selecione um fornecedor
    </button>
    <div class="dropdown-menu p-2 col"
        style="max-height: 400px; overflow-y: auto;"
        aria-labelledby="dropdownMenuButton">
        <div
		style="position: sticky; top: 0; background-color: #f8f4f5; z-index: 100;">
            <input type="text" id="dropdown-fornecedores-input" class="form-control dropdown-fornecedores-input"
			    placeholder="">
            <div class="dropdown-divider"></div>
        </div>
        <div id="dropdown-fornecedor-items" @class(['dropdown-fornecedores-items'])>
            @foreach($fornecedores as $fornecedor)
            <a href="#" data-id="{{ $fornecedor->id }}"
                class="dropdown-fornecedor dropdown-item">{{ $fornecedor->nome }}</a>
            @endforeach
        </div>
        <div style="position: sticky; bottom: 0; background-color: white; z-index: 100;">
            <div class="dropdown-divider mt-2"></div>
            <button type="button" id="add-fornecedor-btn" data-target="#modalAddSupplier" data-toggle="modal"
                    class="btn btn-success btn-sm w-100">
                Novo fornecedor
            </button>
        </div>
    </div>
</div>
<input id='fornecedorVal' name='fornecedorId' value='' type='hidden'>
