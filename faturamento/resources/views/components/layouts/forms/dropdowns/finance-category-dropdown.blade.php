<div x-data="{ open: false }" @click.away="open = false"
     class="dropdown">
    <button @click="open = !open"
            class="dropdown-number btn dropdown-toggle col-12 btn-transparent"
            type="button" id="dropdownCategoriaButton"
            data-toggle="dropdown"
            aria-haspopup="true"
            aria-expanded="false">
        {{ isset($dreDescription) ? $dreDescription : 'Selecione a categoria financeira' }}
    </button>
    <div wire:ignore.self x-show="open" class="dropdown-menu p-2 col"
         style="max-height: 400px; overflow-y: auto;"
         aria-labelledby="dropdownCategoriaButton">
        <div x-data="{ dre: @entangle('queryDre') }"
            style="position: sticky; top: 0; background-color: #f8f4f5; z-index: 100;">
            <input x-model="dre"
                   x-on:input.debounce.500ms="$wire.searchDreByString()"
                type="text" id="dropdown-categoria-financeira-input"
                   class="form-control" placeholder="Digite sua opção">
            <div class="dropdown-divider"></div>
        </div>
        <div id="dropdown-categoria-financeira-items"
            @class(['dropdown-categoria-financeira-items'])>
            @foreach($dreList as $subDre)
                <a href="#" data-id="{{ $subDre['sub_id']}}"
                   wire:click.prevent="selectFinanceCategory({{ $subDre['sub_id'] }},
                   '{{ $subDre['sub_desc'] }}');
                   open = false;"
                   class="dropdown-item">
                    {{ $subDre['sub_desc'] }}
                </a>
            @endforeach
        </div>
        <div style="position: sticky; bottom: 0; background-color: white; z-index: 100;">
            <div class="dropdown-divider"></div>
            @if(count($dreList) > $this->limitDre) <!-- Se houver pelo menos 10 resultados -->
            <button x-on:click.stop wire:click.prevent="loadMoreDre"
                    class="btn btn-sm btn-success w-100 mb-1">
                Exibir mais
            </button>
            @endif
            <button type="button" id="add-categoria-financeira-btn"
                    data-target="#modalAddFinanceCategory" data-toggle="modal"
                    class="btn btn-sm btn-success w-100">Adicionar
            </button>
        </div>
    </div>
</div>
<input wire:model="financeCategoryId" id='categoriaFinanceiraVal' name='sub_categoria_dre' type='hidden'>
