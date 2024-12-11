<form id="formEditarProcesso" method="POST" action="{{ route('processo.update', ['processo' => $processo->id]) }}" enctype="multipart/form-data">
    {{ method_field('PUT') }}
    @csrf
    <div class="form-row">
        <div class="form-group col-md-6 input-group">
            <label for="busca_nome">Fornecedor</label>
            <div class="input-group">
            <input id="busca_nome" name="name" type="text" class="form-control" placeholder="Fornecedor Id Cfp/Cnpj ou nome" disabled value="{{ $processo->f_id }} - {{ $processo->fornecedor }} - {{ $processo->f_doc }}">
                <div class="input-group-append">
                    <span class="input-group-text" id="cadFornecedor" data-toggle="modal" data-target="#exampleModal">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-plus" viewBox="0 0 16 16">
                            <path d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4z"/>
                        </svg>
                    </span>
                </div>
            </div>
        </div>
        <div class="form-group col-md-3">
            <label for="numero_nota">Numero nota fiscal</label>
            <input name="numero_nota" type="text" class="form-control" placeholder="Numero nota" value="{{ $processo->num_nota }}" disabled >
        </div>
        <div class="form-group col-md-3">
            <label for="emissao_nota">Emissão nota</label>
            <input name="emissao_nota" type="date" class="form-control" value="{{ $processo->p_emissao }}" disabled>
        </div>
    </div>
    <div class="form-row">
        <div class="form-group col-md-2">
            <label for="valor_total">Valor total da nota</label>
            <div class="input-group">
                <div class="input-group-prepend">
                    <span class="input-group-text">R$</span>
                </div>
                <input id="valor_total" name="valor" type="text" class="form-control" placeholder="Valor" value="{{ App\Helpers\FormatUtils::formatMoney($processo->valor) }}" disabled>
            </div>
        </div>

        <div class="form-group col-md-3">
            <label for="condicao">Condição</label>
            <select disabled id="condicaoSelect" name="condicao" class="form-control form-select" aria-label=".form-select">
                <option>Selecione</option>
                <option value="vista">A vista</option>
                <option value="prazo">A prazo</option>
            </select>
        </div>
        <div class="form-group col-md-3">
            <label for="dataPrimeiraParcela">Data parcela 1</label>
            <input id="dataPrimeiraParcela" name="data0" type="date" class="form-control" value="{{ json_decode($processo->parcelas, true)[0]['data0'] }}", disabled>
        </div>
        <div class="form-group col-md-2">
            <label for="valorPrimeiraParcela">Valor parcela 1</label>
            <div class="input-group">
                <div class="input-group-prepend">
                    <span class="input-group-text">R$</span>
                </div>
                <input id="valorPrimeiraParcela" name="valor0" type="text" class="form-control" value="{{ json_decode($processo->parcelas, true)[0]['valor0'] }}" disabled>
            </div>
        </div>
       <div class="form-group col-md-2">
            <label for="parcela">Qtde parc</label>
            <input id="parcela" name="parcela" type="text" class="form-control" placeholder="Qtde parcelas" value="{{ $processo->qtde_parcelas }}" disabled>
        </div>
    </div>
    <div id="datasParcela" class="form-row">
        <div id="inserted" class="form-row">
    @if(isset($processo->parcelas))
        @for($i = 0; $i < count(json_decode($processo->parcelas,true))/2; $i++)
        @if($i !== 0 and isset(json_decode($processo->parcelas, true)["data{$i}"]) or $i !== 0 and isset(json_decode($processo->parcelas, true)["valor{$i}"]))
        <div id="vencimento_valor" class="form-group col-md-3">
            <input disabled name="data[]" type="date" class="form-control" placeholder="Data parcela" value="{{ json_decode($processo->parcelas, true)["data{$i}"] }}"}>
        </div>
        <div class="form-group col-md-3">
            <div class="input-group">
                <div class="input-group-prepend">
                    <span class="input-group-text">R$</span>
                </div>
                <input disabled name="valor[]" type="text" class="form-control" placeholder="Valor Parcela" value="{{ json_decode($processo->parcelas, true)["valor{$i}"] }}">
            </div>
        </div>
        @endif
        @endfor
    @endif
        </div>
    </div>
    <div class="form-row">
        <div class="form-group col-md-6">
            <div id="select_cobranca">
                <label for="tipo_cobranca">Tipo de cobrança</label>
                @if(isset($processo))
                <input hidden name="tipo_cobranca" value="{{ $processo->tc_id }}">
                <input name="show_tipo_cobranca" class="form-control" value="{{ $processo->tc_nome }}" disabled>
                @else
                <div class="input-group">
                    <select name="tipo_cobranca" class="form-control form-select" aria-label=".form-select">
                        <option>Selecione o tipo da cobranca</option>
                @foreach($tipo_cobranca as $tipo_c)
                        <option value="{{ $tipo_c->id }}">{{ $tipo_c->nome }}</option>
                @endforeach
                    </select>
                    <div class="input-group-append">
                        <span class="input-group-text" data-toggle="modal" data-target="#cadTipoCobranca"><i class="bi bi-plus"></i></span>
                    </div>
                </div>
                @endif
            </div>
        </div>
        <div class="form-group col-md-6">
            <label for="upload">Arquivos que compõe a cobrança</label>
            <input id="uploadFormEdit" class="form-control input-file" name="files[]" type="file" multiple="" disabled>
        </div>
    </div>
    <div class="form-row">
        <div class="form-group col-md-6">
            <div id="select_flow">
            <label for="flow">Fluxo ao qual a cobrança pertence</label>
            @if(isset($processo))
            <input name="flow" type="hidden" value="{{ $processo->p_workkflow }}">
            <input name="show_flow" class="form-control" type="text" value="{{ $processo->w_nome }}" disabled>
            @else
            <select name="flow" class="form-control form-select" aria-label=".form-select">
                <option>Selecione o fluxo de trabalho</option>
            @forelse($workflow as $flow)
                    <option value="{{ $flow->id }}">{{ $flow->nome}}</option>
            @empty
                <option>Nada ainda</option>
            @endforelse
            </select>
            @endif
            </div>
        </div>
        <div class="form-group col-md-6">
            <label for="observacao">Observação</label>
            <textarea id="observacao" class="form-control" name="observacao" {{ isset($processo) ? "disabled" : null }}>{{ isset($processo) ? $processo->p_observacao : null }}</textarea>
        </div>
    </div>
    @if(auth()->user()->master)
    <div class="form-row">
        <div class="form-group col-md-12">
            <select class="form-control form-select" aria-label=".form-select">
                <option>Selecione a empresa</option>
            @forelse($empresas as $empresa)
                    <option value="{{ $empresa->id }}">{{ empty($empresa->nome) ? $empresa->razao_social : $empresa->nome}}</option>
            @empty
                <option>Nada ainda</option>
            @endforelse
            </select>
        </div>
    </div>
    @endif
    <div class="form-row">
    </div>
    <div class="mb-3">
    </div>
</form>

