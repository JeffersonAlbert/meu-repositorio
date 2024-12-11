{{-- modal cadastro centro de custo --}}
<div class="modal fade" id="modal-cad-produtos" 
	tabindex="-1" role="dialog" aria-labelledby="modalProdutos" 
	aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="font-regular-wt text-processo" 
					id="modalProdutos">Cadastrar centro de custo</h5>
                <button type="button" class="close" 
					data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="enviaProduto" class="form-group" action="{{ route('produto.store') }}" method="POST">
				@csrf
				<div class="modal-body">
					<div class="messages-modal-cad-produtos"></div>
					<div class="form-row">
						<div class="col-3">
							<label class="label-number required" for="nome">Nome</label>
							<input id="nomeProduto" name="nome" type="text" class="input-login form-control" placeholder="Nome do produto" value="{{ isset($produto) ? $produto->produto : null }}">
						</div>
						<div class="col-3">
							<label class="label-number required" for="codigo_produto">Código/SKU</label>
							<input name="codigo_produto" type="text" class="input-login form-control" placeholder="Código do produto" value="{{ isset($produto) ? $produto->codigo_produto : null }}">
						</div>
						<div class="col-3">
							<label class="label-number required" for="valor">Valor venda</label>
							<input name="valor" type="text" class="input-login form-control" placeholder="0,00" value="{{ isset($produto) ? $produto->valor : null }}">
						</div>
						<div class="col-3">
							<label class="label-number" for="ean">Código de barras/EAN</label>
							<input name="ean" type="text" class="input-login form-control" placeholder="Código de barras/EAN" value="{{ isset($produto) ? $produto->codigo_produto : null }}">
						</div>
					</div>
					<div class="form-row">
						<div class="col-3">
							<label class="label-number" for="unidade_medida">Unidade de medida para venda</label>
							<select name="unidade_medida" class="input-login form-control">
								@if(isset($produto))
									<option value="{{ $produto->unidade_medida }}">{{ str_replace(
										['un', 'kg', 'g', 'l', 'ml', 'm', 'cm', 'mm'], 
										['Unidade', 'Kilograma', 'Grama', 'Litro', 'Milimitro', 'Metro', 'Centímetro', 'Milimímetro'], 
										$produto->unidade_medida) 
									}}</option>
								@endif
								<option value="">Selecione</option>
								<option value="un">Unidade</option>
								<option value="kg">Kilograma</option>
								<option value="g">Grama</option>
								<option value="l">Litro</option>
								<option value="ml">Mililitro</option>
								<option value="m">Metro</option>
								<option value="cm">Centímetro</option>
								<option value="mm">Milímetro</option>
							</select>
						</div>
					</div>
					<div class="form-row">
						<div class="col-12">
							<label class="label-number" for="descricao">Descrição do produto</label>
							<textarea name="descricao" class="input-login form-control" placeholder="Descrição do produto">{{ isset($produto) ? $produto->descricao : null }}</textarea>
						</div>
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-secondary font-weigtn-bold" 
						id="voltarProduto" data-dismiss="modal">
							Voltar
					</button>
					<button type="button" class="btn btn-success" 
						id="inserirProduto" disabled>
							Confirmar
					</button>
				</div>
            </form>
        </div>
    </div>
</div>
