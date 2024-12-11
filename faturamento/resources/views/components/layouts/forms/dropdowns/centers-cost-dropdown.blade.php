<div x-data="{ open: false }" @click.away="open = false"
    @class(['dropdown'])>
    <button @click="open = !open"
        @class(['dropdown-number', 'btn', 'dropdown-toggle',
        'col-12', 'btn-transparent'])
            type="button" id="dropdownCentroCustoButton"
            data-toggle="dropdown"
            aria-haspopup="true"
            aria-expanded="open">
        {{ isset($centerCostName) ? $centerCostName : 'Selecione o centro de custo'}}
    </button>
    <div wire:ignore.self x-show="open"
         @class(['dropdown-menu', 'p-2', 'col'])
         style="max-height: 400px; overflow-y: auto;"
         aria-labelledby="dropdownCentroCustoButton">
        <div x-data="{ centerCost: @entangle('queryCenterCost'), pageCenterCost: @entangle('pageCenterCostSearch') }"
            style="position: sticky; top: 0; background-color: #f8f4f5; z-index: 100;">
            <input x-model="centerCost"
                   x-on:input.debounce.500ms="$wire.searchCenterCostByString()"
                   type="text" id="dropdown-centro-custo-input"
                   class="form-control"
                   placeholder="Digite sua opção">
            <div class="dropdown-divider"></div>
        </div>
        <div id="dropdown-centro-custo-items">
            @foreach($centerCostList as $centerCost)
                <a href="#" data-id="{{ $centerCost['id']}}"
                   wire:click.prevent="selectCenterCost({{ $centerCost['id'] }}, '{{ $centerCost['nome'] }}'); open = false;"
                   class="dropdown-centro-custo-item dropdown-item">
                    {{ $centerCost['nome'] }}
                </a>
            @endforeach
        </div>
        <div style="position: sticky; bottom: 0; background-color: white; z-index: 100;">
            <div class="dropdown-divider mt-2"></div>
            <button type="button" id="add-centro-custo-btn" class="btn btn-sm btn-success w-100"
                    data-target="#modalCenterCost" data-toggle="modal">Adicionar</button>
        </div>
    </div>
</div>
<input type="hidden" wire:model="centerCostId" val="{{ isset($centerCostId) ? $centerCostId : null }}" id="centerCost">
