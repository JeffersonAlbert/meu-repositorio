<option value="contrato">
    <div class="col-12">
        <div class="row">
            <div class="sidebar-heading">
                </option>
                {{ isset($processo) ? 'Edição:' : 'Cadastro:'}}
                </div>
            </div>
        </div>
        <div class="row mt-2">
            <div class="col-12">
                <div class="font-regular-wt text-processo">Solicitação de Pagamento</div>
            </div>
        </div>
        @if(isset($processo))
            <div class="row mt-2 mb-3">
                <div class="col-12">
                    <div class="font-regular-wt">Identificação: {{ $processo->trace_code }}</div>
                </div>
            </div>
        @endif
    </div>
</div>
<div class="row">
    <div class="col-12">
        @if(isset($processo))
        <form id="uploadForm" method="POST" action="{{ route('processo.update', ['processo' => $processo->id]) }}" enctype="multipart/form-data">
            {{ method_field('PUT') }}
        @else
        <form id="uploadForm" method="POST" action="{{ route('processo.store') }}" enctype="multipart/form-data">
        @endif
            @csrf
            <div class="row cor-cinza-I">
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
                        <div class="row mt-3 mb-3">
                            <div class="col">
                                <div class="custom-control custom-switch">
                                  <input name="imposto" type="checkbox" class="custom-control-input" id="switch-imposto" {{ isset($processo) && $processo->f_id == 0 ? 'checked' : '' }}>
                                  <label class="custom-control-label lable-number" for="switch-imposto">Imposto ?</label>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-4 input-group">
                                <label class="label-number" for="busca_nome">Fornecedor</label>
                                <div class="input-group">
                                    <input id="busca_nome" name="name" type="text" class="input-login form-control" placeholder="Fornecedor Id Cfp/Cnpj ou nome" value="{{ (isset($processo) && $processo->f_id !== 0) ? $processo->f_id." - ".$processo->fornecedor_name." - ".$processo->f_doc : null}}" {{ (isset($processo) && $processo->f_id == 0) ? 'disabled' : null }}>
                                    <div class="input-group-append">
                                        <span class="input-group-text" id="cadFornecedor" data-toggle="modal" data-target="#exampleModal">
                                            <i class="bi bi-plus"></i>
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group col-2">
                                <label class="label-number" for="numero_nota" style="font-size: 14px; white-space: nowrap;">Numero nota fiscal</label>
                                <input name="numero_nota" type="text" class="input-login form-control" placeholder="Numero nota" value="{{ (isset($processo)) ? $processo->num_nota : null }}">
                            </div>
                            <div class="form-group col-2">
                                <label class="label-number" for="emissao_nota">Emissão nota</label>
                                <input name="emissao_nota" type="date" class="input-login form-control" value="{{ (isset($processo)) ? $processo->p_emissao : null }}">
                            </div>
                            <div class="form-group col-2">
                                <label class="label-number" for="competencia">Competência</label>
                                <input name="competencia" type="date" class="input-login form-control" value="{{ (isset($processo)) ? $processo->competencia : null }}">
                            </div>
                            <div class="form-group col-2">
                               <label class="label-number" for="valor_total" style="font-size: 14px; white-space: nowrap;">Valor total da nota</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text color-white-I">R$</span>
                                    </div>
                                    <input id="valor_total" name="valor" type="text" class="input-login form-control" placeholder="0,00" value="{{ (isset($processo)) ? App\Helpers\FormatUtils::formatMoney($processo->valor) : null }}">
                                </div>
                            </div>
                        </div>
                        <div class="row mt-3">
                            <div class="form-group col-1">
                                <label class="label-number" for="condicao">Condição</label>
                                <select id="condicaoSelect" name="condicao" class="input-login select-number form-control form-select" aria-label=".form-select">
                                    @if(isset($processo))
                                    <option value="{{ $processo->p_condicao }}">{{ $processo->p_condicao == 'vista' ? 'A vista' : 'A prazo' }}</option>
                                    @else
                                    <option>Selecione</option>
                                    @endif
                                    <option value="vista">A vista</option>
                                    <option value="prazo">A prazo</option>
                                </select>
                            </div>
                            <div class="form-group col-2">
                                <label class="label-number" for="valorPrimeiraParcela" style="font-size: 14px; white-space: nowrap;">Valor da 1ª</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text color-white-I">R$</span>
                                    </div>
                                    <input id="valorPrimeiraParcela" name="valor0" type="text" class="input-login form-control" placeholder="0,00" value="{{ (isset($processo)) ? json_decode($processo->parcelas)->valor0: null }}">
                                </div>
                            </div>
                            <div class="form-group col-2">
                                <label class="label-number" for="dataPrimeiraParcela">Data parcela 1</label>
                                <input id="dataPrimeiraParcela" name="data0" type="date" class="input-login form-control" value="{{ (isset($processo)) ? json_decode($processo->parcelas)->data0: null }}">
                            </div>
                            <div class="form-group col-1">
                                <label class="label-number" for="parcela">Parcelas</label>
                                <input id="parcela" name="parcela" type="text" class="input-login  form-control" placeholder="Qtde" value="{{ (isset($processo)) ? $processo->qtde_parcelas : null }}">
                            </div>
                            <div class="form-group col-2">
                                @include('processo.categoria-financeira.categoria-financeira')
                                {{--  <div id='dre'>
                                    <label class="label-number required" for='categoria_financeira'>Categoria financeira</label>
                                    <div class="input-group">
                                        <select name="categoria_financeira" class="input-login form-control form-select" aria-label=".form-select">
                                            @if(isset($processo))
                                            <option value="{{ $processo->sub_id}}">{{ $processo->sub_desc}}</option>
                                            <option value="">Selecione</option>
                                            @else
                                            <option value="">Selecione</option>
                                            @endif
                                            @foreach($subDre as $dre)
                                            <option value="{{ $dre->sub_id}}">{{ $dre->sub_desc}}</option>
                                            @endforeach
                                        </select>
                                        <div class="input-group-append">
                                            <span class="input-group-text" data-toggle="modal" data-target="#cad_categoria_financeira"><i class="bi bi-plus"></i></span>
                                        </div>
                                    </div>
                                </div> --}}
                            </div>
                            <div class="form-group col-2">
                                <div id="select_cobranca">
                                    <label class="label-number" for="tipo_cobranca">Tipo de cobrança</label>
                                    <div class="input-group">
                                        <select name="tipo_cobranca" class="input-login form-control form-select" aria-label=".form-select">
                                        @if(isset($processo))
                                            <option value="{{ $processo->tc_id }}">{{ $processo->tc_nome }}</option>
                                        @else
                                            <option>Selecione</option>
                                        @endif
                                        @foreach($tipo_cobranca as $tipo_c)
                                            <option value="{{ $tipo_c->id }}">{{ $tipo_c->nome }}</option>
                                        @endforeach
                                        </select>
                                        <div class="input-group-append">
                                            <span class="input-group-text" data-toggle="modal" data-target="#cadTipoCobranca"><i class="bi bi-plus"></i></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group col-2">
                                <label class="label-number" for="flow">Workflow</label>
                                <select name="flow" class="input-login form-control form-select" aria-label=".form-select">
                                @if(isset($processo))
                                <option value="{{ $processo->p_workflow }}">{{ $processo->workflow_nome }}</option>
                                @else
                                    <option>Selecione</option>
                                @endif
                                @forelse($workflow as $flow)
                                        <option value="{{ $flow->id }}">{{ $flow->nome}}</option>
                                @empty
                                    <option>Nada ainda</option>
                                @endforelse
                                </select>
                            </div>
                        </div>
                        <div class="row">
                            {{-- <div class="form-group col-6">
                                <label class="label-number" for="upload">Arquivos</label>
                                <div for="upload" class="file-input-number justify-content-center">
                                    <i for="upload" class="bi bi-custom-file-login mr-3"></i>
                                    <label class="text-file-input-number" for="upload">Escolher arquivo</label>
                                    <ul id="file-list"></ul>
                                    <input id="upload" name="files[]" type="file" multiple="">
                                </div>
                            </div>
                            @if(isset($processo))
                            <div class="form-group col-3">
                                <label class="label-number" for="arquivos-do-processo">Lista de arquivos</label>
                                <div id="arquivos-do-processo" class="file-input-number">
                                    <ul id="file-list">
                                        @foreach(json_decode($processo->doc_name) as $doc_name)
                                        <li style="word-break: break-all;">{{ $doc_name }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                            @endif --}}
                            <div class="form-group  col-4">
                                <div class="dropdown add_centro_custo">
                                    <div type="hidden" id="selected_centro_custo"></div>
                                    <label class="label-number" for="centro_custo">Centro de custo:</label>
                                    <button id="centro_custo" name="centro_custo" class="form-control btn dropdown-toggle col-12 btn-transparent" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        {{ isset($processo->centro_custo) ? $processo->centro_custo : "Selecione um centro de custo"}}
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
                            <div class="form-group  col-4">
                                <div class="dropdown add_rateio">
                                    <div id="hidden_rateio"></div>
                                    <label for="centro_custo" class="required label-number">Selecionar Rateio:</label>
                                    <button id="centro_custo-rateio" name="centro_custo" class="form-control btn dropdown-toggle col-12 btn-transparent" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        {{ isset($processo->rateio) ? $processo->rateio : "Selecione rateio" }}
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
                            <div class="form-group col-4">
                                <label class="label-number" for="observacao">Observação</label>
                                <textarea rows="4" class="input-login form-control" name="observacao">{{ isset($processo) ? $processo->p_observacao : null }}</textarea>
                            </div>
                        </div>
                        <div class="row">
                            @if(session()->has('filiais'))
                            <div class="form-group col-4">
                                <label for="filial">Selecione uma filial:</label>
                                <select id="filial" name="filial" class="input-login form-control form-select" aria-label=".form-select">
                                    @if(isset($processo))
                                     <option value="{{ $processo->id_filial }}">{{ $processo->nome_filial }}</option>
                                    @else
                                     <option>Selectione a filial</option>
                                    @endif
                                     @forelse(session()->get('filiais') as $filial)
                                    <option value="{{ $filial->f_id}}">{{ $filial->f_nome }}</option>
                                    @empty
                                    @endforelse
                                </select>
                            </div>
                            @endif
                        </div>
                        @if(auth()->user()->master)
                        <div class="row">
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
                        <div class="row">
                            <div class="col">
                                <div class="custom-control custom-switch switch-pago">
                                  <input type="checkbox" class="custom-control-input" id="switch-pago">
                                  <label class="custom-control-label lable-number" for="switch-pago">Pago ?</label>
                                </div>
                            </div>
                        </div>
                        <div class="row mt-3">
                            <div class="font-regular-wt">Anexo</div>
                        </div>
                        @if(isset($processo) and $processo->files_types_desc !== null)
                        @include('processo.files.list')
                        @endif
                        <div class="row mt-3">
                            <div class="form-group col-4">
                                <label for="file-upload" class="label-number">Arquivo</label>
                                <label for="file-upload" class="btn btn btn-transparent col">
                                    <span class="file-name">Escolha o arquivo</span>
                                    <i class="bi bi-paperclip"></i>
                                </label>
                                <input id="file-upload" type="file" name="files[]" class="d-none file-upload">
                            </div>
                            <div class="col-3">
                                <label for="tipo_anexo" class="label-number">Tipo anexo</label>
                                <select name="tipo_anexo[]" id="tipo_anexo" class="input-login form-control form-select">
                                    <option value="contrato">Contrato</option>
                                    <option value="documento_fiscal">Documento fiscal</option>
                                    <option value="documento_cobranca">Documento de cobrança</option>
                                    <option value="comprovante_pagamento">Comprovante Pagamento</option>
                                </select>
                            </div>
                            <div class="col-4">
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
                        <div id="info-adicionais" class="font-regula-wt row mt-6 texto-branco {{ isset($processo) ? 'mb-3' : 'mb-6' }}">
                            Informações adicionais
                        </div>
                        <div id="datasParcela" class="row {{ isset($processo) ? 'mb-3 mt-3' : 'mt-3' }}">
                            @if(isset($processo->parcelas))
                                @for($i = 0; $i < (count(json_decode($processo->parcelas,true))-2)/2; $i++)
                                    @if($i !== 0 and isset(json_decode($processo->parcelas, true)["data{$i}"]))
                                        <div id="vencimento_valor" class="form-group col-md-3">
                                            <input name="data{{ $i }}" type="date" class="form-control input-login" placeholder="Data parcela" value="{{ json_decode($processo->parcelas, true)["data{$i}"] }}"}>
                                        </div>
                                        <div class="form-group col-md-3">
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text color-white-I">R$</span>
                                                </div>
                                                <input name="valor{{ $i }}" type="text" class="form-control input-login" placeholder="Valor Parcela" value="{{ json_decode($processo->parcelas, true)["valor{$i}"] }}">
                                            </div>
                                        </div>
                                    @endif
                                @endfor
                            @endif
                        </div>
                        <div class="row">
                            <div class="col-2">
                                <button class="btn btn-md btn-success-number btn-success">{{ isset($processo) ? 'Editar' : 'Salvar' }} Solicitação</button>
                            </div>
                            <div class="col-2">
                                <a href="#" class="btn btn-md btn-back-number btn-success">Voltar</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
