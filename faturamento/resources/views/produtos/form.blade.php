@extends('produtos.js.sendForm-js')
@extends('produtos.js.fotos-js')
@extends('layout.newLayout')

@section('content')
<div class="row">
    <div class="form-error col" style="display: none"></div>
    <div class="col-12">
        <div class="row">
            <div class="sidebar-heading">
                <div class="font-regular-wt font-heading-bar">
                    {{ isset($processo) ? 'Edição:' : 'Cadastro:' }}
                </div>
            </div>
        </div>
        <div class="row mt-2">
            <div class="col-12">
                <div class="font-regular-wt text-processo">Produto</div>
            </div>
        </div>
        @if (isset($produto))
            <div class="row mt-2 mb-3">
                <div class="col-12">
                    <div class="font-regular-wt">{{ $produto->name }}</div>
                </div>
            </div>
        @endif
    </div>
</div>
<div class="row">
	<div class="col-12">
		<form id="enviarProduto" action="{{ isset($produto) ? route('produto.update', $produto->id) : route('produto.store') }}" method="post">
			@csrf
			@if (isset($produto))
				@method('PUT')
			@endif
			<div class="row cor-cinza-I">
				<div class="card h-100 shadown-number w-100">
					<div class="card-body" style="background: rgba(141, 148, 145, 0.10)">
						<div class="row cor-cinza-I">
							<div class="font-regular-wt text-processo">Informações do produto</div>
						</div>
                        <div class="row mt-3 mb-3">
                            <div class="col">
                                <div class="custom-control custom-switch">
                                  <input name="servico" type="checkbox" class="custom-control-input" id="switch-produto" {{ isset($produto) && $produto->tipo == 'servico' ? 'checked' : '' }}>
                                  <label class="custom-control-label lable-number" for="switch-produto">Serviço</label>
                                </div>
                            </div>
                        </div>
						<div class="row mt-3">
							<div class="col-12">
								<div class="row">
									<div class="col-3">
										<label class="label-number required" for="nome">Nome</label>
										<input name="nome" type="text" class="input-login form-control" placeholder="Nome do produto" value="{{ isset($produto) ? $produto->produto : null }}">
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
								<div class="row">
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
								<div class="row">
									<div class="col-12">
										<label class="label-number" for="descricao">Descrição do produto</label>
										<textarea name="descricao" class="input-login form-control" placeholder="Descrição do produto">{{ isset($produto) ? $produto->descricao : null }}</textarea>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="card shadown-number w-100 mt-3">
					<div class="card-body" style="background: rgba(141, 148, 145, 0.10)">
						<div class="row cor-cinza-I">
							<div class="font-regular-wt text-processo">Estoque</div>
						</div>
						<div class="row mt-3">
							<div class="col-12">
								<div class="row">
									<div class="col-3">
										<label class="label-number" for="quantidade">Quantidade disponível</label>
										<input name="quantidade" type="text" class="input-login form-control" placeholder="0" value="{{ isset($produto) ? $produto->quantidade : null }}">
									</div>
									<div class="col-3">
										<label class="label-number" for="valor_custo">Custo médio</label>
										<input name="valor_custo" type="text" class="input-login form-control" placeholder="0,00" value="{{ isset($produto) ? $produto->valor_custo : null }}">
									</div>
									<div class="col-3">
										<label class="label-number" for="estoque_maximo">Estoque máximo</label>
										<input name="estoque_maximo" type="text" class="input-login form-control" placeholder="0" value="{{ isset($produto) ? $produto->estoque_maximo : null }}">
									</div>
									<div class="col-3">
										<label class="label-number" for="estoque_minimo">Estoque minimo</label>
										<input name="estoque_minimo" type="text" class="input-login form-control" placeholder="0" value="{{ isset($produto) ? $produto->estoque_minimo : null }}">
									</div>
								</div>
								<div class="row">
									<div class="col-3">
										<label class="label-number" for="categoria">Categoria</label>
										<select name="categoria" class="input-login form-control">
											<option value="1">Embalagem</option>
											<option value="2">Material de Uso e consumo</option>
											<option value="3">Matéria-Prima</option>
											<option value="4">Mercadoria para revenda</option>
											<option value="5">Outras</option>
											<option value="6">Outros insumos</option>
											<option value="7">Produto acabado</option>
											<option value="8">Produto em processo</option>
											<option value="9">Produto intermediário</option>
											<option value="10">Serviços</option>
											<option value="11">Subproduto</option>
										</select>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="card shadown-number w-100 mt-3">
					<div class="card-body" style="background:rgba(141, 148, 145, 0.10)">
						<div class="row cor-cinza-I">
							<div class="font-regular-wt text-processo">Medidas</div>
						</div>
						<div class="row mt-3">
							<div class="col-3">
								<label class="label-number" for="altura">Altura:</label>
								<input name="altura" type="text" class="input-login form-control" placeholder="0,00" value="{{ isset($produto) ? $produto->altura : null }}">
							</div>
							<div class="col-3">
								<label class="label-number" for="largura">Largura</label>
								<input name="largura" type="text" class="input-login form-control" placeholder="0,00" value="{{ isset($produto) ? $produto->largura : null }}">
							</div>
							<div class="col-3">
								<label class="label-number" for="comprimento">Profundidade</label>
								<input name="comprimento" type="text" class="input-login form-control" placeholder="0,00" value="{{ isset($produto) ? $produto->comprimento: null }}">
							</div>
							<div class="col-3">
								<label class="label-number" for="peso_liquido">Peso liquído</label>
								<input name="peso_liquido" type="text" class="input-login form-control" placeholder="0,00" value="{{ isset($produto) ? $produto->peso_liquido : null }}">
							</div>
						</div>
						<div class="row">
							<div class="col-3">
								<label class="label-number" for="peso_bruto">Peso Bruto</label>
								<input name="peso_bruto" type="text" class="input-login form-control" placeholder="0,00" value="{{ isset($produto) ? $produto->peso_bruto : null }}">
							</div>
							<div class="col-3">
								<label class="label-number" for="volume">Volume</label>
								<input name="volume" type="text" class="input-login form-control" placeholder="0,00" value="{{ isset($produto) ? $produto->volume : null }}">
							</div>
						</div>
					</div>
				</div>
				<div class="card shadown-number w-100 mt-3">
					<div class="card-body" style="background:rgba(141, 148, 145, 0.10)">
						<div class="row cor-cinza-I">
								<div class="font-regular-wt text-processo">Fotos</div>
						</div>
						<div class="row mt-4">
							<div class="form-group col-4">
								<label for="file-upload" class="label-number">Arquivo</label>
								<label for="file-upload" class="btn input-login btn btn-transparent col">
									<span class="file-name">Escolha o arquivo</span>
									<i class="bi bi-paperclip"></i>
								</label>
								<input id="file-upload" type="file" name="files[]" class="d-none file-upload">
							</div>
							<div class="col-7">
								<label for="descricao_arquivo" class="label-number">Descrição</label>
								<input name="descricao_arquivo[]" id="descricao_arquivo" class="input-login form-control">
							</div>
							<div class="col-1">
								<label for="add-upload" class="label-number">Novo</label>
								<button id="add-upload" class="btn btn-md btn-success d-block">
									<i class="bi bi-plus"></i>
								</button>
							</div>
						</div>
						<div id="upload-adicionais"></div>
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
