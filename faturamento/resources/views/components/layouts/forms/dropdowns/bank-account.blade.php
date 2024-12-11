<div @class(['dropdown'])>
    <button @class(['dropdown-number', 'btn', 'dropdown-toggle',
        'col-12', 'btn-transparent'])
            type="button" id="dropdownBancosButton"
            data-toggle="dropdown"
            aria-haspopup="true"
            aria-expanded="false">
        {{ isset($bankName) ? $bankName : 'Selecione o banco'}}
    </button>
    <div @class(['dropdown-menu', 'p-2', 'col'])
         style="max-height: 400px; overflow-y: auto;"
         aria-labelledby="dropdownBancosButton">
        <div
            style="position: sticky; top: 0; background-color: #f8f4f5; z-index: 100;">
            <input type="text" id="dropdown-bancos-input"
                   class="form-control dropdown-bancos-input"
                   placeholder="Digite sua opção">
            <div class="dropdown-divider"></div>
        </div>
        <div id="dropdown-bancos-items" @class(['dropdown-bancos-items'])>
            @foreach(\App\Models\Bancos::where('id_empresa',
                auth()->user()->id_empresa)->get() as $banco)
                <a href="#" data-id="{{ $banco->id}}"
                   wire:click.prevent="selectBank({{ $banco->id }}, '{{ $banco->nome }}')"
                   class="dropdown-bancos-item dropdown-item">
                    {{ $banco->nome }} {{ $banco->agencia }} {{ $banco->conta }}
                </a>
            @endforeach
        </div>
        <div style="position: sticky; bottom: 0; background-color: white; z-index: 100;">
            <div class="dropdown-divider mt-2"></div>
            <button type="button" id="add-bancos-btn" class="btn btn-sm btn-success w-100"
                    data-target="#modalAddBank" data-toggle="modal">Adicionar</button>
        </div>
    </div>
</div>
<input type="hidden" wire:model="bankId" val="{{ isset($bankId) ? $bankId : null }}" id="bankId">
