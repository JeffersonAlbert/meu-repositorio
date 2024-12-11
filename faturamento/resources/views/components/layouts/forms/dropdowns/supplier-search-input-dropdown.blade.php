<div x-data="{ open: false }" @click.away="open = false"
     class="dropdown">
    <button @click="open = !open"
            class="dropdown-number btn dropdown-toggle col-12 btn-transparent"
            type="button" id="dropdowntesteButton"
            data-toggle="dropdown"
            aria-haspopup="true"
            aria-expanded="open">
        {{ isset($supplierName) ? $supplierName : 'Selecione o fornecedor' }}
    </button>
    <div wire:ignore.self x-show="open"
         class="dropdown-menu p-2 col"
         style="max-height: 400px; overflow-y: auto;"
         aria-labelledby="dropdownSupplierButton">
        <div x-data="{ supplier: @entangle('querySupplier'),  pageSupplier: @entangle('pageSupplierSearch') }"
            style="position: sticky; top: 0; background: white; z-index: 100;">
            <input x-model="supplier"
                x-on:input.debounce.500ms="$wire.searchSupplierByString()"
                type="text" id="dropdown-teste-input"
                class="form-control dropdown-teste-input"
                style="margin: 0; padding: 0;"
                placeholder="Digite sua opção">
            <div class="dropdown-divider"></div>
        </div>
        <div id="dropdown-supplier-items"
            @class(['dropdown-teste-items'])>
            @foreach($supplierList as $supplier)
                <a href="#" wire:click.prevent="selectSupplier({{ $supplier['id'] }},
                   '{{ $supplier['nome'] }}');
                   open = false;"
                   class="dropdown-supplier-item dropdown-item">
                    {{ $supplier['nome'] }}
                </a>
            @endforeach
        </div>
        <div style="position: sticky; bottom: 0; background-color: white; z-index: 100;">
            <div class="dropdown-divider mt-2"></div>
            @if(count($supplierList) >= $this->limitSuppliers) <!-- Se houver pelo menos 10 resultados -->
            <button x-on:click.stop wire:click.prevent="loadMoreSupplier"
                    class="btn btn-sm btn-success w-100 mb-1">
                Exibir mais
            </button>
            @endif
            <button type="button" id="add-supplier"
                    data-target="#modalAddSupplier" data-toggle="modal"
                    class="btn btn-sm btn-success w-100">Adicionar
            </button>
        </div>
    </div>
</div>
<input wire:model="supplierId" id='categoriaFinanceiraVal' name='sub_categoria_dre' type='hidden'>
