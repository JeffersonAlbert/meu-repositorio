<div @class(['dropdown'])>
    <button @class(['dropdown-number', 'btn', 'dropdown-toggle',
        'col-12', 'btn-transparent'])
        type="button" id="dropdownCobrancaButton"
        data-toggle="dropdown"
        aria-haspopup="true"
        aria-expanded="false">
        Selecione tipo de cobrança
    </button>
    <div @class(['dropdown-menu', 'p-2', 'col'])
        style="max-height: 400px; overflow-y: auto;"
        aria-labelledby="dropdownCobrancaButton">
        <div
            style="position: sticky; top: 0; background-color: #f8f4f5; z-index: 100;">
            <input type="text" id="dropdown-tipo-cobranca-input" class="form-control" placeholder="Digite sua opção">
            <div class="dropdown-divider"></div>
        </div>
        <div id="dropdown-tipo-cobranca-items">
            @foreach(\App\Models\TipoCobranca::where('id_empresa',
                auth()->user()->id_empresa)->get() as $tipo)
            <a href="#" data-id="{{ $tipo->id}}"
                class="dropdown-tipo-cobranca-item dropdown-item">{{ $tipo->nome }}</a>
            @endforeach
        </div>
        <div style="position: sticky; bottom: 0; background-color: white; z-index: 100;">
            <div class="dropdown-divider mt-2"></div>
            <button type="button" id="add-tipo-cobranca-btn" class="btn btn-sm btn-success w-100">Adicionar</button>
        </div>
    </div>
</div>
