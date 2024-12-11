<div class="card shadow mb-4">
    {{--card header--}}
        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
            <h6 class="m-0 font-weight-bold text-primary">Cadastro conta receber</h6>
            <div class="dropdown no-arrow">
                <a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <i class="fas fa-ellipsis-v fa-sm fa-fw text-gray-400"></i>
                </a>
                <div class="dropdown-menu dropdown-menu-right shadow animated--fade-in" aria-labelledby="dropdownMenuLink">
                    <div class="dropdown-header">Dropdown Header:</div>
                    <a class="dropdown-header" href="#">Action</a>
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item" href="#">Algo aqui</a>
                </div>
            </div>
        </div>
    {{--card header--}}
    {{--card body--}}
   @if(isset($contasReceber))
    <form class="form-group" id="formEditContaReceber" action="#" method="POST">
        {{ method_field('PUT') }}
    @else
    <form class="form-group" id="formAddContaReceber" action="#" method="POST">
    @endif
        @csrf
        <div class="card-body w-100">
            <div class="form-row">
                <div class="form-group col-md-2">
                    <label for="competencia" class="required">Competência:</label>
                    <div class="input-group">
                        <input id="competencia" name="competencia" type="date" class="form-control" value="{{ isset($contasReceber) ? $contasReceber->competencia : null }}">
                    </div>
                </div>
                <div class="form-group col-md-3 input-group">
                    <label for="busca_cliente" class="required">Cliente:</label>
                    <div class="input-group">
                        <input id="busca_cliente" name="name" type="text" class="form-control" placeholder="Cliente Id CPF/CNPJ" value="{{ isset($contasReceber) ? $contasReceber->id_cliente." - ".$contasReceber->nome_cliente." - ".$contasReceber->doc_cliente : null }}" required>
                        <div class="input-group-append">
                            <span class="input-group-text" id="cadCliente" data-toggle="modal" data-target="#clienteModal">
                                <i class="bi bi-person-plus-fill"></i>
                            </span>
                        </div>
                    </div>
                </div>
                <div class="form-group col-md-3 input-group">
                    <label for="descricao" class="required">Descrição:</label>
                    <div class="input-group">
                        <input id="descricao" name="descricao" type="text" class="form-control" placeholder="Breve descição aqui" value="{{ isset($contasReceber) ? $contasReceber->descricao : null}}" required>
                    </div>
                </div>
                <div class="form-group col-md-2 input-group">
                    <label for="valor" class="required">Valor total:</label>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text">R$</span>
                        </div>
                        <input id="valor" name="valor_total" type="text" class="form-control" placeholder="0,00" value="{{ isset($contasReceber) ? App\Helpers\FormatUtils::formatMoney($contasReceber->valor_total) : null }}">
                    </div>
                </div>
                <div class="form-group col-md-2">
                    <label for="competencia" class="required">Vencimento:</label>
                    <div class="input-group">
                        <input id="vencimento" name="vencimento" type="date" class="form-control" value="{{ isset($contasReceber) ? $contasReceber->vencimento : null }}">
                    </div>
                </div>

            </div>
            <div class="form-row">
                <div class="form-group input-group col-md-4">
                    <div class="hidden_categoria"></div>
                    <div class="dropdown add_categoria">
                        <label for="categoria" class="required">Categoria:</label>
                        <button id="categoria" name="categoria" class="btn dropdown-toggle col-12 btn-transparent" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            {{ isset($contasReceber) ? $contasReceber->categoria : "Selecione uma categoria" }}
                        </button>
                        <div class="dropdown-menu dropdown-categoria col-12" aria-labelledby="categoria" style="max-height: 200px; overflow-y: auto;">
                            <a id="cadCategoria" class="dropdown-item" href="#"><i class="bi bi-plus-circle-fill text-success"></i> Adicionar</a>
                            <div class="dropdown-divider"></div>
                            @foreach ($categorias as $categoria)
                            <a id="{{ $categoria->id }}" class="dropdown-item" href="#">
                                <i class="bi bi-x-circle-fill text-danger deleteCategoria"></i>
                                <i class="bi bi-pencil-fill text-warning editaCategoria"></i>
                                {{ $categoria->categoria }}
                            </a>
                            <input id="cat_descricao_{{$categoria->id}}" type="hidden" value="{{ $categoria->descricao }}">
                            <div class="m-1 dropdown-divider"></div>
                            @endforeach
                        </div>
                    </div>
                </div>
                <div class="form-group input-group col-md-4">
                    <div class="dropdown add_centro_custo">
                        <div type="hidden" id="selected_centro_custo"></div>
                        <label for="centro_custo">Centro de custo:</label>
                        <button id="centro_custo" name="centro_custo" class="btn dropdown-toggle col-12 btn-transparent" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            {{ isset($contasReceber->centro_custo) ? $contasReceber->centro_custo : "Selecione um centro de custo"}}
                        </button>
                        <div class="dropdown-menu dropdown-centro_custo col-12" aria-labelledby="centro_custo" style="max-height: 200px; overflow-y: auto;">
                            <a id="cadCentroCusto" class="dropdown-item" href="#"><i class="bi bi-plus-circle-fill text-success"></i> Adicionar</a>
                            <div class="dropdown-divider m-0"></div>
                            @foreach($centrosCusto as $centro_custo)
                            <a id="{{ $centro_custo->id }}" class="dropdown-item" href="#">
                                <i class="bi bi-x-circle-fill text-danger deleteCentroCusto"></i>
                                <i class="bi bi-pencil-fill text-warning editaCentroCusto"></i>
                                {{ $centro_custo->nome }}
                            </a>
                            <input id="descricao_{{$centro_custo->id}}" type="hidden" value="{{ $centro_custo->descricao }}">
                            <div class="m-1 dropdown-divider"></div>
                            @endforeach
                        </div>
                    </div>
                </div>
                <div class="form-group input-group col-md-4">
                    <label for="codigo_referencia">Código de refêrencia:</label>
                    <div class="input-group">
                        <input class="form-control" id="codigo_referencia" name="codigo_referencia" value="{{ isset($contasReceber) ? $contasReceber->codigo_referencia : null}}">
                    </div>
                </div>
            </div>
            <div class="form-row">
                @include('financeiro.contratos.selectContratos')
                @include('financeiro.produtos.selectProdutos')
                <div class="form-group input-group col-md-4">
                    <label for="observacao">Observação</label>
                    <div class="input-group">
                        <textarea class="form-control" id="observacao" name="observacao">{{ isset($contasReceber) ? $contasReceber->observacao : null }}</textarea>
                    </div>
                </div>
            </div>
            <div class="form-row">
                <div class="form-group input-group col-md-2">
                    <div class="custom-control custom-switch">
                      <input type="checkbox" class="custom-control-input" id="customSwitcheHabilitarRateio">
                      <label class="custom-control-label" for="customSwitcheHabilitarRateio">Habilitar rateio</label>
                    </div>
                </div>
                <div class="form-group input-group col-md-2">
                    <div class="custom-control custom-switch">
                      <input type="checkbox" class="custom-control-input" id="customSwitcheRepetirLancamento">
                      <label class="custom-control-label" for="customSwitcheRepetirLancamento">Repetir lançamento</label>
                    </div>
                </div>
            </div>
        </div>
    {{--card body--}}
        @include('financeiro.contasreceber.formRateio')
        @include('financeiro.contasreceber.formRepetirLancamento')
    {{--card footer--}}
        <div class="card-footer">
            <div class="row">
                <div class="form-group mr-1">
                    <button class="btn btn-success">Enviar</button>
                </div>
                @if(isset($contasReceber) && auth()->user()->receber_contas)
                <div class="form-group">
                    <a class="btn btn-success receber" href="#" data-id="{{ $contasReceber->id }}">Receber</a>
                </div>
                @endif
            </div>
        </div>
    </form>
    </div>
