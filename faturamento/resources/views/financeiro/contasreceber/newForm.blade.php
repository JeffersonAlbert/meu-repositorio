
<div class="row">
    <div class="col-12">
        <div class="row">
            <div class="sidebar-heading"#>
                <div class="font-regular-wt font-heading-bar">
                Cadastro:
                </div>
            </div>
        </div>
        <div class="row mt-2 mb-3">
            <div class="col-12">
                <div class="font-regular-wt text-processo">Contas a receber</div>
            </div>
        </div>
        @if(isset($contasReceber))
        <div class="row mb-3">
            <div class="col-12">
                <div class="font-regular-wt text-processo">{{ $contasReceber->trace_code}}</div>
            </div>
        </div>
        @endif
    </div>
</div>
<div class="row">
    <div class="col-12">
        <form class="form-group" id="{{ isset($contasReceber) ? 'formUpdateContaReceber': 'formAddContaReceber' }}" action="#" method="POST">
            @csrf
            @if(isset($contasReceber))
                @method('PUT')
            @endif
            <div class="card h-100 shadow-number w-100">
                <div class="card-body" style="background: rgba(141, 148, 145, 0.10);">
                    <div class="row cor-cinza-I">
                        <div class="font-regular-wt text-processo">Informações Principais</div>
                    </div>
                    <div class="row">
                        <div class="font-regular-wt font-heading-bar mt-3">
                            Os dados abaixo são muito importantes para o cadastro do seu processo de faturamento. Preencha-os com atenção.
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="form-group col-2">
                            <label class="label-number" for="competencia" class="required">Competência:</label>
                            <div class="input-group">
                                <input id="competencia" name="competencia" type="date" class="input-login form-control" value="{{ isset($contasReceber) ? $contasReceber->competencia : null }}">
                            </div>
                        </div>
                        <div class="form-group col-3">
                            <label for="busca_cliente" class="required label-number">Cliente:</label>
                            <div class="input-group">
                                <input id="busca_cliente" name="name" type="text" class="form-control input-login" placeholder="Cliente Id CPF/CNPJ" value="{{ isset($contasReceber) ? $contasReceber->id_cliente." - ".$contasReceber->nome_cliente." - ".$contasReceber->doc_cliente : null }}" required>
                                <div class="input-group-append">
                                    <span class="input-group-text" id="cadCliente" data-toggle="modal" data-target="#clienteModal">
                                        <i class="bi bi-person-plus-fill"></i>
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="form-group col-2">
                            <label for="descricao" class="required label-number">Descrição:</label>
                            <div class="input-group">
                                <input id="descricao" name="descricao" type="text" class="form-control input-login" placeholder="Breve descição aqui" value="{{ isset($contasReceber) ? $contasReceber->descricao : null}}" required>
                            </div>
                        </div>
                        <div class="form-group col-1">
                            <label for="condicao" class="required label-number">Condição:</label>
                            <div class="input-group">
                                <select id="condicao" name="condicao" class="form-control input-login">
                                    @if(isset($contasReceber))
                                    <option value="{{ $contasReceber->condicao }}">{{ $contasReceber->condicao == 'vista' ? 'A vista' : 'A prazo' }}</option>
                                    @else
                                    <option value="">Selecione</option>
                                    @endif
                                    <option value="vista">A vista</option>
                                    <option value="prazo">A prazo</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group col-2">
                            <label for="valor" class="required label-number">Valor total:</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">R$</span>
                                </div>
                                <input id="valor" name="valor_total" type="text" class="form-control input-login" placeholder="0,00" value="{{ isset($contasReceber) ? App\Helpers\FormatUtils::formatMoney($contasReceber->valor_total) : null }}">
                            </div>
                        </div>
                        <div class="form-group col-2">
                            <label for="competencia" class="required label-number">Vencimento:</label>
                            <div class="input-group">
                                <input id="vencimento" name="vencimento" type="date" class="input-login form-control" value="{{ isset($contasReceber) ? $contasReceber->vencimento : null }}">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                    <div class="form-group col-3">
                        @include('financeiro.contasreceber.form-parts.categoria-financeira')
                        {{-- <select id="sub_categoria_dre" name="sub_categoria_dre" class="form-control input-login">
                            @if(isset($contasReceber))
                            <option value='{{ $contasReceber->sub_id}}'>{{ $contasReceber->sub_desc }}</option>
                            <option value=''>Selecione</option>
                            @else
                            <option value="">Selecione</option>
                            @endif
                            @foreach($subDres as $subDre)
                            <option value="{{ $subDre->sub_id }}">{{ $subDre->sub_desc }}</option>
                            @endforeach
                        </select> --}}
                    </div>
                        <div class="form-group col-3">
                            <div class="hidden_categoria"></div>
                            <div class="dropdown add_categoria">
                                <label for="categoria" class="label-number">Categoria:</label>
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
                        <div class="form-group col-3">
                            <div class="dropdown add_centro_custo">
                                <div type="hidden" id="selected_centro_custo"></div>
                                <label class="label-number" for="centro_custo">Centro de custo:</label>
                                <button id="centro_custo" name="centro_custo" class="form-control btn dropdown-toggle col-12 btn-transparent" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
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
                        <div class="form-group col-3">
                            <label class="label-number" for="codigo_referencia">Código de refêrencia:</label>
                            <div class="input-group">
                                <input class="form-control input-login" id="codigo_referencia" name="codigo_referencia" value="{{ isset($contasReceber) ? $contasReceber->codigo_referencia : null}}">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        @include('financeiro.contratos.selectContratos')
                        @include('financeiro.produtos.selectProdutos')
                        <div class="form-group col-md-4">
                            <label class="label-number" for="observacao">Observação</label>
                            <div class="input-group">
                                <textarea class="form-control input-login" id="observacao" name="observacao">{{ isset($contasReceber) ? $contasReceber->observacao : null }}</textarea>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        @if(isset($filiais) and !is_null($filiais))
                            <div class="form-group col-4">
                                <label class="label-number" for="filial">Filial</label>
                                <select name="id_filial" id="filial" class="input-login select-number form-control form-select">
                                    <option value="0">MATRIZ</option>
                                @foreach($filiais as $filial)
                                    <option value="{{ $filial->id }}"> {{ $filial->nome }}</option>
                                @endforeach
                                </select>
                            </div>
                        @endif
                        <div class="form-group col-2">
                            <div class="custom-control custom-switch">
                              <input type="checkbox" class="custom-control-input" id="customSwitcheHabilitarRateio">
                              <label class="custom-control-label label-number" for="customSwitcheHabilitarRateio">Habilitar rateio</label>
                            </div>
                        </div>
                        <div class="form-group col-2">
                            <div class="custom-control custom-switch">
                              <input type="checkbox" class="custom-control-input" id="customSwitcheRepetirLancamento">
                              <label class="custom-control-label label-number" for="customSwitcheRepetirLancamento">Repetir lançamento</label>
                            </div>
                        </div>
                    </div>
                    @include('financeiro.contasreceber.formRateio')
                    @include('financeiro.contasreceber.formRepetirLancamento')
                    @include('financeiro.contasreceber.formularioUpload.formularioUpload')
                    <div class="row mt-3">
                        <div class="col-3">
                            <button class="btn btn-md btn-success-number btn-success">{{ isset($contasReceber) ? 'Atualizar' : 'Salvar' }}</button>
                        </div>
                        @if(isset($contasReceber) && auth()->user()->receber_contas)
                        <div class="col-3">
                            <a class="btn btn-success receber" href="#" data-id="{{ $contasReceber->id }}">Receber</a>
                        </div>
                        @endif
                        <div class="col-2">
                            <a href="#" class="btn btn-md btn-back-number btn-success">Voltar</a>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
