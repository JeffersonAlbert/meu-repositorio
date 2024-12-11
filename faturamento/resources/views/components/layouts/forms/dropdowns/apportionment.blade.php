<div x-data="{ open: false }" @click.away="open = false"
    @class(['dropdown'])>
    <button @click="open = !open"
        @class(['dropdown-number', 'btn', 'dropdown-toggle',
        'col-12', 'btn-transparent'])
            type="button" id="dropdownApportionmentButton"
            data-toggle="dropdown"
            aria-haspopup="true"
            aria-expanded="false">
    {{ isset($apportionmentName) ? $apportionmentName : 'Selecione o rateio'}}
    </button>
    <div wire:ignore.self x-show="open"
         @class(['dropdown-menu', 'p-2', 'col'])
         style="max-height: 400px; overflow-y: auto;"
         aria-labelledby="dropdownApportionmentButton">
        <div x-data="{ supplier: @entangle('queryApportionment'), pageApportionment: @entangle('pageApportionmentSearch') }"
            style="position: sticky; top: 0; background-color: #f8f4f5; z-index: 100;">
            <input x-model="supplier"
                   x-on:input.debounce.500ms="$wire.searchApportionmentByString()"
                   type="text" id="dropdown-apportionment-input"
                   class="form-control"
                   placeholder="Digite sua opção">
            <div class="dropdown-divider"></div>
        </div>
        <div id="dropdown-apportionment-items">
            @foreach($apportionmentList as $apportionment)
                <a href="#" data-id="{{ $apportionment['id']}}"
                   wire:click.prevent="selectApportionment({{ $apportionment['id'] }}, '{{ $apportionment['nome'] }}'); open = false;"
                   class="dropdown-apportionment-item dropdown-item">
                    {{ $apportionment['nome'] }}
                </a>
            @endforeach
        </div>
        <div style="position: sticky; bottom: 0; background-color: white; z-index: 100;">
            <div class="dropdown-divider mt-2"></div>
            <button href="#" type="button" id="add-apportionment-btn"
                    data-target="#modalApportionment" data-toggle="modal"
                    class="btn btn-sm btn-success w-100">
                Adicionar
            </button>
        </div>
    </div>
</div>
<input type="hidden" wire:model="apportionmentId" val="" id="apportionment">
