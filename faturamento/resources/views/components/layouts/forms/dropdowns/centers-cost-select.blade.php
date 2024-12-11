<div x-data="{ open: false }" @click.away="open = false"
     @class(['dropdown'])>
    <button @click="open = !open"
        @class(['dropdown-number', 'btn', 'dropdown-toggle',
        'col-12', 'btn-transparent'])
            type="button" id="dropdownCentroCustoSelectButton"
            data-toggle="dropdown"
            aria-haspopup="true"
            aria-expanded="open">
        {{ isset($centerCostName) ? $centerCostName: 'Selecione o centro de custo' }}
    </button>
    <div wire:ignore.self x-show="open"
         @class(['dropdown-menu', 'p-2', 'col'])
         style="max-height: 400px; overflow-y: auto;"
         aria-labelledby="dropdownCentroCustoSelectButton">
        <div x-data="{ centerCost: @entangle('queryCenterCost'), pageCenterCost: @entangle('pageCenterCostSearch') }"
            style="position: sticky; top: 0; background-color: #f8f4f5; z-index: 100;">
            <input x-model="centerCost"
                   x-on:input.debounce.500ms="$wire.searchCenterCostByString()"
                   type="text"
                   class="form-control"
                   placeholder="Digite sua opção">
            <div class="dropdown-divider"></div>
        </div>
        <div id="dropdown-centro-custo-select-items" @class(['dropdown-centro-custo-select-items'])>
            @foreach($centerCostList as $centerCost)
                <a href="#" data-id="{{ $centerCost->id}}"
                   wire:click.prevent="selectCenterCost({{ $centerCost->id }}, '{{ $centerCost->nome }}'); open = false;"
                   class="dropdown-item">{{ $centerCost->nome }}</a>
            @endforeach
        </div>
    </div>
</div>
@if(isset($index))
<input type="hidden" wire:model="centerCostId.{{$index}}" val="" id="centerCost">
@else
<input type="hidden" wire:model="centerCostId" val="" id="centerCost">
@endif
