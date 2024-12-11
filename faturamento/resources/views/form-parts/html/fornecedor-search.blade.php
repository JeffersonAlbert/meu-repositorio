<div class="dropdown">
    <button class="dropdown-number btn dropdown-toggle col-12 btn-transparent"
        type="button"
		id="dropdownFornecedorButton"
        data-toggle="dropdown"
        aria-haspopup="true"
        aria-expanded="false">
        {{ $fornecedorName ?? 'Selecione um fornecedor' }}
    </button>
    <div class="dropdown-menu p-2 col"
        style="max-height: 400px; overflow-y: auto;"
        aria-labelledby="dropdownMenuButton">
        <div
		style="position: sticky; top: 0; background-color: #f8f4f5; z-index: 100;">
            <input type="text" id="dropdown-fornecedores-input" class="form-control"
			    placeholder="Digite sua opção">
            <div class="dropdown-divider"></div>
        </div>
        <div id="dropdown-fornecedores-items">
            @foreach($fornecedores as $fornecedor)
            <a href="#" data-id="{{ $fornecedor->id }}"
                class="dropdown-fornecedor dropdown-item">{{ $fornecedor->nome }}</a>
            @endforeach
        </div>
        <div style="position: sticky; bottom: 0; background-color: white; z-index: 100;">
            <div class="dropdown-divider mt-2"></div>
        </div>
    </div>
</div>
<input wire:model="supplier" id='fornecedorVal' name='fornecedorId' value='' type='hidden'>
<input wire:model="supplierName" id='fornecedorValName' name='fornecedorIdName' value='' type='hidden'>
