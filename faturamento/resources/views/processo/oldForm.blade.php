<div class="card w-100 h-100 shadow mb-4">
    <!-- Card Header - Dropdown -->
        <div
            class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
            <h6 class="m-0 font-weight-bold text-primary">Cadastro processo faturamento</h6>
            <div class="dropdown no-arrow">
                <a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink"
                    data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <i class="fas fa-ellipsis-v fa-sm fa-fw text-gray-400"></i>
                </a>
                <div class="dropdown-menu dropdown-menu-right shadow animated--fade-in"
                    aria-labelledby="dropdownMenuLink">
                    <div class="dropdown-header">Dropdown Header:</div>
                    <a class="dropdown-item" href="#">Action</a>
                    <a class="dropdown-item" href="#">Another action</a>
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item" href="#">Something else here</a>
                </div>
            </div>
        </div>
        <!-- Fim header do card -->
        <!-- Card Body -->
        <div class="card-body" style="overflow-y: scroll;">
            <div class="chart-area">
            @if(isset($processo))
                <form id="uploadForm" method="POST" action="{{ route('processo.update', ['processo' => $processo->id]) }}" enctype="multipart/form-data">
                {{ method_field('PUT') }}
            @else
                <form id="uploadForm" method="POST" action="{{ route('processo.store') }}" enctype="multipart/form-data">
            @endif
                @csrf
                    <div class="form-row">
                        <div class="form-group col-md-6 input-group">
                            <label for="busca_nome">Fornecedor</label>
                            <div class="input-group">
                                <input id="busca_nome" name="name" type="text" class="form-control" placeholder="Fornecedor Id Cfp/Cnpj ou nome" value="{{ (isset($processo)) ? $processo->id_fornecedor." - ".$processo->f_nome." - ".$processo->cpf_cnpj : null}}" {{ isset($processo) ? "disabled" : null }}>
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
                            <input name="numero_nota" type="text" class="form-control" placeholder="Numero nota" value="{{ (isset($processo)) ? $processo->numero_nota : null }}" {{ isset($processo) ? "disabled" : null }}>
                        </div>
                        <div class="form-group col-md-3">
                            <label for="emissao_nota">Emissão nota</label>
                            <input name="emissao_nota" type="date" class="form-control" value="{{ (isset($processo)) ? $processo->emissao_nota : null }}" {{ isset($processo) ? "disabled" : null }}>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-2">
                            <label for="valor_total">Valor total da nota</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">R$</span>
                                </div>
                                <input id="valor_total" name="valor" type="text" class="form-control" placeholder="Valor" value="{{ (isset($processo)) ? App\Helpers\FormatUtils::formatMoney($processo->valor) : null }}" {{ isset($processo) ? "disabled" : null }}>
                            </div>
                        </div>

                        <div class="form-group col-md-3">
                            <label for="condicao">Condição</label>
                            @if(isset($processo))
                            <input class="form-control" value="{{ $processo->condicao }}" disabled>
                            @else
                            <select id="condicaoSelect" name="condicao" class="form-control form-select" aria-label=".form-select">
                                <option>Selecione</option>
                                <option value="vista">A vista</option>
                                <option value="prazo">A prazo</option>
                            </select>
                            @endif
                        </div>
                        <div class="form-group col-md-3">
                            <label for="dataPrimeiraParcela">Data parcela 1</label>
                            <input id="dataPrimeiraParcela" name="data0" type="date" class="form-control" value="{{ (isset($processo)) ? json_decode($processo->dt_parcelas)->data0: null }}" {{ isset($processo) ? "disabled" : null }}>
                        </div>
                        <div class="form-group col-md-2">
                            <label for="valorPrimeiraParcela">Valor parcela 1</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">R$</span>
                                </div>
                                <input id="valorPrimeiraParcela" name="valor0" type="text" class="form-control" value="{{ (isset($processo)) ? json_decode($processo->dt_parcelas)->valor0: null }}" {{ isset($processo) ? "disabled" : null}}>
                            </div>
                        </div>
                       <div class="form-group col-md-2">
                            <label for="parcela">Qtde parc</label>
                            <input id="parcela" name="parcela" type="text" class="form-control" placeholder="Qtde parcelas" value="{{ (isset($processo)) ? $processo->parcelas : null }}" disabled>
                        </div>
                    </div>
                    <div id="datasParcela" class="form-row">
                    @if(isset($processo->dt_parcelas))
                        @for($i = 0; $i < count(json_decode($processo->dt_parcelas,true))/2; $i++)
                        @if($i !== 0)
                        <div id="vencimento_valor" class="form-group col-md-3">
                            <input disabled name="data[]" type="date" class="form-control" placeholder="Data parcela" value="{{ json_decode($processo->dt_parcelas, true)["data{$i}"] }}"}>
                        </div>
                        <div class="form-group col-md-3">
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">R$</span>
                                </div>
                                <input disabled name="valor[]" type="text" class="form-control" placeholder="Valor Parcela" value="{{ json_decode($processo->dt_parcelas, true)["valor{$i}"] }}">
                            </div>
                        </div>
                        @endif
                        @endfor
                    @endif
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
                            <input id="upload" class="form-control input-file" name="files[]" type="file" multiple="" {{ isset($processo) ? "disabled" : null }}>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="flow">Fluxo ao qual a cobrança pertence</label>
                            @if(isset($processo))
                            <input name="flow" type="hidden" value="{{ $workflow->id }}">
                            <input class="form-control" type="text" value="{{ $workflow->nome }}" disabled>
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
                        <div class="form-group col-md-6">
                            <label for="observacao">Observação</label>
                            <textarea class="form-control" name="observacao" {{ isset($processo) ? "disabled" : null }}>{{ isset($processo) ? $processo->observacao : null }}</textarea>
                        </div>
                    </div>
                    <div class="form-row">
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
                            <div class="dropdown add_rateio">
                                <div id="hidden_rateio"></div>
                                <label for="centro_custo" class="required">Selecionar Rateio:</label>
                                <button id="centro_custo-rateio" name="centro_custo" class="btn dropdown-toggle col-12 btn-transparent" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    {{ isset($contasReceber->rateio) ? $contasReceber->rateio : "Selecione rateio" }}
                                </button>
                                <div id="centro_custo-rateio" class="dropdown-menu dropdown-rateio col-12" aria-labelledby="centro_custo" style="max-height: 200px; overflow-y: auto;">
                                    <a id="addRateioCentroCusto" class="dropdown-item" href="#"><i class="bi bi-plus-circle-fill text-success"></i> Adicionar</a>
                                    <div class="dropdown-divider"></div>
                                    @foreach($rateios as $rateio)
                                    <a id="{{ $rateio->id }}" class="dropdown-item" href="#">
                                        <i class="bi bi-x-circle-fill text-danger deleteCentroCusto"></i>
                                        <i class="bi bi-pencil-fill text-warning editaCentroCusto"></i>
                                        {{ $rateio->nome}}
                                    </a>
                                    <div class="m-1 dropdown-divider"></div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                        @if(session()->has('filiais'))
                        <div class="form-group col-md-4">
                            <label for="filial">Selecione uma filial:</label>
                            <select id="filial" name="filial" class="form-control form-select" aria-label=".form-select">
                                <option>Selectione a filial</option>
                                @forelse(session()->get('filiais') as $filial)
                                <option value="{{ $filial->f_id}}">{{ $filial->f_nome }}</option>
                                @empty
                                @endforelse
                            </select>
                        </div>
                        @endif
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
            </div>
        </div>
        <!-- fim do body do card -->
        <div class="card-footer">
            <div class="mb-3">
                    @if(isset($processo))
                        <button class="btn btn-warning btn-submit">Alterar</button>
                    @else
                        <button id="inserirProcesso" class="btn btn-success">Inserir</button>
                    @endif
                    <a href="{{ url()->previous() }}" class="btn btn-secondary">Voltar</a>
                    <button id="editar" class="btn btn-danger">Editar</button>
            </div>
            </form>

        </div>
    </div>
