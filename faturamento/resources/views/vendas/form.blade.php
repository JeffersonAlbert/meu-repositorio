@extends('layout.newLayout')

@push('scripts')
	@include('form-parts.js.clientes-input')
	@include('form-parts.js.categoria-financeira-select')
	@include('form-parts.js.centro-custo-select')
	@include('form-parts.js.produtos-select')
	@include('vendas.js.adicionar-produtos')
	@include('vendas.js.desconto')
	@include('vendas.js.totalizador-venda')
	@include('vendas.js.pagamento-produto')
	@include('form-parts.js.formas-pagamento')
	@include('form-parts.js.select-contas-recebimento')
	@include('form-parts.js.contas-bancarias')
    @include('vendas.js.enviar-formulario')
    @include('vendas.js.edit-vendas')
@endpush

@section('content')
@include('form-parts.modal.modal-cad-centro-custo')
@include('form-parts.modal.modal-cad-produtos')
@include('form-parts.modal.modal-cad-conta-bancaria')
<div class="row">
    <div class="form-error col" style="display: none"></div>
    <div class="col-12">
        <div class="row">
            <div class="sidebar-heading">
                <div class="font-regular-wt font-heading-bar">
                    {{ isset($venda) ? 'Edição:' : 'Cadastro:' }}
                </div>
            </div>
        </div>
        <div class="row mt-2">
            <div class="col-12">
                <div class="font-regular-wt text-processo">Vendas</div>
            </div>
        </div>
        @if (isset($venda))
            <div class="row mt-2 mb-3">
                <div class="col-12">
                    <div class="font-regular-wt">Codigo rastreio: {{ $venda->trace_code }}</div>
                </div>
            </div>
        @endif
    </div>
</div>
<div class="row">
	<div class="col-12">
		<form id="enviarVendaOuOrcamento" action="{{ isset($venda) ? route('vendas.update', $venda->id) : route('vendas.store') }}" method="post">
			@csrf
			@if (isset($venda))
				@method('PUT')
                <input type="hidden" value="{{ $venda->trace_code }}" name="trace_code">
			@endif
			<div class="row cor-cinza-I">
				<div class="card h-100 shadown-number w-100">
					<div class="card-body" style="background: rgba(141, 148, 145, 0.10)">
						<div class="row cor-cinza-I">
							<div class="font-regular-wt text-processo">Informações da venda</div>
						</div>
                        <div class="row mt-3 mb-3">
                            <div class="col">
                                <div class="custom-control custom-switch">
                                  <input name="orcamento" type="checkbox" class="custom-control-input" id="switch-produto" {{ isset($venda) && json_decode($venda->dados_venda)->orcamento == 'on' ? 'checked' : '' }}>
                                  <label class="custom-control-label lable-number" for="switch-produto">Orçamento</label>
                                </div>
                            </div>
                        </div>
						<div class="row mt-3">
							<div class="col-12">
								<div class="row">
									<div class="col-3">
										@include('form-parts.html.clientes-input')
									</div>
									<div class="col-2">
										<label class="label-number required" for="data">Competencia</label>
										<input id="competencia" name="data" type="date" class="input-login form-control" value="{{ isset($venda) ? $venda->competencia : date('Y-m-d') }}">
									</div>
									<div class="col-3">
										@include('form-parts.html.categoria-financeira-select')
									</div>
									<div class="col-3">
										@include('form-parts.html.centro-custo-select')
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="card shadown-number w-100 mt-3">
					<div class="card-body" style="background: rgba(141, 148, 145, 0.10)">
						<div class="row cor-cinza-I">
							<div class="font-regular-wt text-processo">Itens da venda</div>
						</div>
						<div class="row mt-3">
							<div class="col-12">
								<div id="form-container">
									<div class="row">
										<div class='col-3'>
											@include('form-parts.html.produtos-select')
										</div>
										<div class="col-3">
											<label class="label-number" for="observacao">Detalhes do item</label>
											<input name="observacao[]" type="text" class="descricao input-login form-control" placeholder="" value="{{ isset($produto) ? $produto->quantidade : null }}">
										</div>
										<div class="col-2">
											<label class="label-number required" for="quantidade">Quantidade</label>
											<input name="quantidade[]" type="text" class="quantidade input-login form-control" placeholder="0,00" value="{{ isset($produto) ? $produto->valor_custo : null }}">
										</div>
										<div class="col-2">
											<label class="label-number required" for="valor_unitario">Valor unitário</label>
											<input name="valor_unitario[]" type="text" class="input-login form-control valor-unitario" placeholder="0" value="{{ isset($produto) ? $produto->estoque_maximo : null }}">
										</div>
										<div class="col-2">
											<label class="label-number required" for="valor_total">Total</label>
											<input name="valor_total[]" type="text" class="valor-total total input-login form-control" placeholder="0" value="{{ isset($produto) ? $produto->estoque_minimo : null }}">
										</div>
									</div>
								</div>
								<div class="row mt-3">
									<div class="col-2">
										<button id="adicionar-produtos" class="btn btn-success btn-sm">Adicionar novo produto</button>
									</div>
								</div>
								<div class="row">
									<div class="col-3">
										<div class="col-auto">
											<label for="currency-type" class="col-form-label label-number">Tipo de desconto:</label>
										</div>
										<div class="col-auto">
											<div class="btn-group" role="group" id="currency-type">
												<button type="button" class="desconto ativo btn btn-success btn-currency">R$</button>
												<button type="button" class="desconto btn btn-light btn-percent">%</button>
											</div>
                                            <input name="tipo_desconto" id="tipo_desconto" value="" type="hidden">
										</div>
									</div>
									<div class="col-2">
										<label class="label-number" for="desconto">Desconto</label>
										<div class="input-group mb-3">
											<div class="input-group-prepend">
												<span class="input-group-text desconto">R$</span>
											</div>
											<input id="desconto" name="desconto" type="text" class="input-login form-control" placeholder="0,00" value="{{ isset($produto) ? $produto->desconto : null }}">
										</div>
									</div>
									<div class="col-2">
										<label class="label-number" for="valor_adicional">Frete</label>
										<div class="input-group mb-3">
											<div class="input-group-prepend">
												<span class="input-group-text">R$</span>
											</div>
											<input id="frete" name="frete" type="text" class="input-login form-control" placeholder="0,00" value="{{ isset($produto) ? $produto->frete : null }}">
										</div>
									</div>
								</div>
								<div class="row mt-3 border border-success">
									<div class="col">
										<div class="row mt-3">
											<div class="col-2">
												<label class="label-number" for="produtos">Total venda</label>
												<div id="produtos" class="font-regular-wt">0 produtos</div>
											</div>
											<div class="col-2">
												<label class="label-number" for="valor_itens">Itens da venda(R$)</label>
												<div id="valor_itens" class="font-bold-wt">R$ 0,00</div>
											</div>
											<div class="col-1">
												<div class="row mt-3 font-bold-wt">
													<i class="fas fa-plus"></i>
												</div>
											</div>
											<div class="col-2">
												<label class="label-number" for="valor_adicional">Adicionais(R$)</label>
												<div id="valor_adicional" class="font-bold-wt" style="color: green">R$ 0,00</div>
											</div>
											<div class="col-1">
												<div class="row mt-3 font-bold-wt">
													<i class="fas fa-minus"></i>
												</div>
											</div>
											<div class="col-2">
												<label class="label-number" for="valor_desconto">Desconto(R$)</label>
												<div id="valor_desconto" class="font-bold-wt" style="color: red">R$ 0,00</div>
											</div>
											<div class="col-1">
												<div class="row mt-3 font-bold-wt">
													<i class='fas fa-equals'></i>
												</div>
											</div>
											<div class="2">
												<label class="label-number" for="valor_total_venda">Total(R$)</label>
												<div id="valor_total_venda" class="font-bold-wt">R$ 0,00</div>
											</div>
										</div>
										<div class="row mt-3"></div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="card shadown-number w-100 mt-3">
					<div class="card-body" style="background:rgba(141, 148, 145, 0.10)">
						<div class="row cor-cinza-I">
							<div class="font-regular-wt text-processo">Informações de pagamento</div>
						</div>
						<div id="forma_pagamento">
							<input id="totalizadorValorAdicional" type="hidden" name="totalizador_valor_adicional" value="">
							<input id="totalizadorDesconto" type="hidden" name="totalizador_desconto" value="">
							<input id="totalizadorItens" type="hidden" name="totalizador_itens" value="">
							<input id="totalizadorValor" name="totalizador_valor" type="hidden" value="">
							<input id="dataVencimentoHoje" type="hidden" value="{{ date('Y-m-d') }}">
							<div class="row mt-3">
								<div class="col-3">
									@include('form-parts.html.formas-pagamento')
								</div>
								<div class="col-3">
									@include('form-parts.html.select-contas-recebimento')
								</div>
								<div class="col-1">
									<label class="label-number" for="condicao_pagamento">Condição de pagamento</label>
									<select id="condicao_pagamento" name="condicao_pagamento" class="input-login form-control">
										<option value="vista">À vista</option>
										<option value="prazo">Parcelado</option>
									</select>
								</div>
								<div class="col-1">
									<label class="label-number" for="parcelas">Qtde. parcelas</label>
									<input id="parcelas" class="input-login form-control" name="parcelas" placeholder="0" disabled>
								</div>
							</div>
						</div>
						<div id="parcelasRows">
							<div class="row mt-2">
								<div class="col-2">
									<label class="label-number" for="valor_receber">Valor a receber</label>
									<input id="valor_receber" name="valor_receber[]" type="text" class="input-login form-control" placeholder="0,00" value="{{ isset($produto) ? $produto->comprimento: null }}">
								</div>
								<div class="col-2">
									<label class="label-number" for="data_vencimento">Data vencimento</label>
									<input id="data_vencimento" name="data_vencimento[]" type="date" class="input-login form-control" value="{{ date('Y-m-d') }}">
								</div>
								<div class="col">
									<label class="label-number" for="descricao">Descricao</label>
									<input id="descricao" name="descricao[]" class="input-login form-control"></input>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
            <div class="row">
                <div class="col-2">
                    <button type="submit" class="btn btn-md btn-success mt-3">
                        <i class="bi bi-save"></i> Salvar
                    </button>
                </div>
            </div>
		</form>
	</div>
</div>
@endsection
