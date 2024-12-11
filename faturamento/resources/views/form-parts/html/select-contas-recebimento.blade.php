<label for="dropdownContasRecebimentoButton" class="label-number">Conta de recebimento</label>
<div class="dropdown">
    <button class="dropdown-number btn dropdown-toggle col-12 btn-transparent" 
		type="button" id="dropdownContasRecebimentoButton" 
		data-toggle="dropdown" 
		aria-haspopup="true" 
		aria-expanded="false">
        Selecione uma opção
    </button>
    <div class="dropdown-menu p-2 col" 
		style="max-height: 400px; overflow-y: auto;" 
		aria-labelledby="dropdownContasRecebimentoButton">
		<div 
			style="position: sticky; top: 0; background-color: #f8f4f5; z-index: 100;">
        	<input type="text" id="dropdown-recebimento-input" class="form-control dropdown-input" placeholder="Digite sua opção">
        	<div class="dropdown-divider"></div>
		</div>
        <div id="dropdown-recebimento-items" class="dropdown-recebimento-items">
        @foreach($bancos as $banco)
        <a href="#" data-id="{{ $banco->id}}"
			class="dropdown-recebimento-item dropdown-item">{{ $banco->nome }}</a>
        @endforeach
        </div>
		<div style="position: sticky; bottom: 0; background-color: white; z-index: 100;">
            <div class="dropdown-divider mt-2"></div>
            <button type="button" id="add-conta-recebimento-btn" class="btn btn-sm btn-success w-100">Adicionar</button>
        </div>
    </div>
</div>
<input id='contaRecebimentoVal' name='contaRecebimento' value='' type='hidden'>