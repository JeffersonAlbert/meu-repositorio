@if ($processo->pago == true)
    <form id="formPagarProcesso" method="POST"
        action="{{ route('processo.edit-pagamento', ['id_processo' => $processo->id, 'id_pvv' => $processo->pvv_id]) }}"
        enctype="multipart/form-data">
        {{ method_field('PUT') }}
    @else
        <form id="formPagarProcesso" method="POST" action="{{ route('processo.pagamento') }}"
            enctype="multipart/form-data">
@endif
@csrf
<div class="form-row">
    <div class="form-group col-md-6">
        <label for="data_pagamento" class="label-number">Data pagamento</label>
        <input class="form-control input-login" type="date" name="data_pagamento"
            value="{{ date('Y-m-d', strtotime($processo->pvv_dtv)) }}">
    </div>
    <div class="form-group col-md-6">
        <label for="valor_pago" class="label-number">Valor pago</label>
        <input id="valor_pago" class="form-control input-login" type="text" name="valor_pago"
            value="{{ App\Helpers\FormatUtils::formatMoney($processo->vparcela) }}">
    </div>
</div>
{{-- dd($processo) --}}
<div class="form-row">
    <div class="form-group col-md-6">
        <label for="forma_pagamento" class="label-number">Forma pagamento</label>
        <select id="forma_pagamento" name="forma_pagamento" class="form-control select-number input-login">
            @if (isset($processo->id_forma_pagamento) and !is_null($processo->id_forma_pagamento))
                <option value="{{ $processo->id_forma_pagamento }}">{{ $processo->forma_pagamento_nome }}</option>
            @else
                <option value="0">Selecione a forma de pagamento</option>
            @endif
            @foreach ($formas_pagamento as $forma_pagamento)
                <option value="{{ $forma_pagamento->id }}">{{ $forma_pagamento->nome }}</option>
            @endforeach
        </select>
    </div>
    <div class="form-group col-md-6">
        <label for="banco_origem" class="label-number">Banco origem</label>
        <div class="dropdown add_banco">
            @if (isset($processo->id_banco))
                <input name="id_banco" value="{{ $processo->id_banco }}" type="hidden">
                <button name="banco_origem" class="btn dropdown-toggle col-12 btn-transparent" type="button"
                    id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    {{ $processo->banco_nome }} - {{ $processo->banco_agencia }} - {{ $processo->banco_conta }}
                </button>
            @else
                <button name="banco_origem" class="btn dropdown-toggle col-12 btn-transparent" type="button"
                    id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    Selecione uma opção
                </button>
            @endif
            <div class="dropdown-menu dropdown-banco col-12" aria-labelledby="dropdownMenuButton"
                style="max-height: 200px; overflow-y: auto;">
                <a id="cadBanco" class="dropdown-item" href="#"><i
                        class="bi bi-plus-circle-fill text-success"></i> Adicionar</a>
                <div class="dropdown-divider"></div>
                @foreach ($bancos as $banco)
                    <a id="{{ $banco->id }}" class="dropdown-item" href="#">
                        <i class="bi bi-x-circle-fill text-danger deleteBanco"></i>
                        <i class="bi bi-pencil-fill text-warning editaBanco"></i>
                        {{ $banco->nome }} - {{ $banco->agencia }} - {{ $banco->conta }}
                    </a>
                @endforeach
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="form-group col-6">
        <label class="label-number" for="_upload">Arquivos</label>
        <div for="_upload" class="file-input-number justify-content-center">
            <i for="_upload" class="bi bi-custom-file-login mr-3"></i>
            <label class="text-file-input-number" for="_upload">Escolher arquivo</label>
            <ul id="file-list"></ul>
            <input id="_upload" name="_files[]" type="file" multiple="">
        </div>
    </div>
    <div class="form-group col-md-6">
        <label class="label-number" for="observacao">Observação</label>
        <div class="input-group">
            <textarea name="observacao" class="form-control input-login">{{ $processo->pagamento_obs }}</textarea>
        </div>
    </div>
</div>
<input hidden name="id_processo" value="{{ $processo->id }}">
<input hidden name="id_processo_vencimento_valor" value="{{ $processo->pvv_id }}">
<input hidden name="id_empresa" value="{{ $processo->e_id }}">
</form>
@include('processo.modalConfirmDeleteBank')
