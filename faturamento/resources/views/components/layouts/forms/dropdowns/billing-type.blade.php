<div x-data="{ open: false }" @click.away="open = false"
    @class(['dropdown'])>
    <button @click="open = !open"
            @class(['dropdown-number', 'btn', 'dropdown-toggle',
        'col-12', 'btn-transparent'])
            type="button" id="dropdownCobrancaButton"
            data-toggle="dropdown"
            aria-haspopup="true"
            aria-expanded="open">
        {{isset($billingTypeName) ? $billingTypeName : 'Selecione tipo de cobrança'}}
    </button>
    <div wire:ignore.self x-show="open" @class(['dropdown-menu', 'p-2', 'col'])
         style="max-height: 400px; overflow-y: auto;"
         aria-labelledby="dropdownCobrancaButton">
        <div x-data="{ billingType: @entangle('queryBillingType') }"
            style="position: sticky; top: 0; background-color: #f8f4f5; z-index: 100;">
            <input x-model="billingType"
                   x-on:input.debounce.500ms="$wire.searchBillingTypeByString()"
                   type="text"
                   class="form-control"
                   placeholder="Digite sua opção">
            <div class="dropdown-divider"></div>
        </div>
        <div>
            @foreach($billingTypeList as $tipo)
                <a href="#" data-id="{{ $tipo['id']}}"
                   wire:click.prevent="selectBillingType({{ $tipo['id'] }}, '{{ $tipo['nome'] }}')"
                   class=" dropdown-item">
                    {{ $tipo['nome'] }}
                </a>
            @endforeach
        </div>
        <div style="position: sticky; bottom: 0; background-color: white; z-index: 100;">
            <div class="dropdown-divider mt-2"></div>
            <button type="button" id="add-tipo-cobranca-btn" class="btn btn-sm btn-success w-100"
                    data-target="#modalBillingType" data-toggle="modal">
                Adicionar
            </button>
        </div>
    </div>
</div>
<input type="hidden" wire:model="billingTypeId" val="{{ isset($billingTypeId) ? $billingTypeId : null}}" id="billingType">
