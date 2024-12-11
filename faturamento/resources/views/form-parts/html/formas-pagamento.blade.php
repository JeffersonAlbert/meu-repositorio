<label for="dropdownFormaPagamentoButton" class="label-number">Forma pagamento</label>
<div class="dropdown">
    <button class="dropdown-number btn dropdown-toggle col-12 btn-transparent" 
		type="button" 
		data-toggle="dropdown" 
		aria-haspopup="true" 
		aria-expanded="false">
        Selecione uma opção
    </button>
    <div class="dropdown-menu forma-pagamento p-2 col" 
		style="max-height: 400px; overflow-y: auto;">
		<div 
			style="position: sticky; top: 0; background-color: #f8f4f5; z-index: 100;">
        	<input type="text" class="form-control dropdown-forma-pagamento-input" placeholder="Digite sua opção">
        	<div class="dropdown-divider"></div>
		</div>
        <div class="dropdown-forma-pagamento-items">
        @foreach($formasPagamento as $formaPagamento)
        <a href="#" data-id="{{ $formaPagamento->id }}" 
			class="dropdown-forma-pagamento-item dropdown-item">{{ $formaPagamento->nome }}</a>
        @endforeach
        </div>
    </div>
</div>
<input class='formaPagamentoVal' name='forma-pagamento' value='' type='hidden'>