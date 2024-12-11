<div class="row w-100">
    <div class="col-7">
        <div class="card text-center h-100 w-auto" style="">
            <div class="card-body color-background-white-02">
                <div class="row">
                    <div class="col-1">
                        <div class="row">
                            <button id="prevButton" class="btn btn-sm btn-success btn-success-number">
                                <i class="bi bi-arrow-left"></i>
                            </button>
                        </div>
                        <div class="row mt-1">
                            <button id="nextButton" class="btn btn-sm btn-success btn-success-number">
                                <i class="bi bi-arrow-right"></i>
                            </button>
                        </div>
                        <div class="row mt-1">
                            <button id="viewFile" class="btn btn-sm btn-success btn-success-number">
                                <i class="bi bi-eye"></i>
                            </button>
                        </div>
                        <div class="row mt-1">
                            <button id="zoom-in-btn" class="btn btn-sm btn-success btn-success-number">
                                <i class="bi bi-zoom-in"></i>
                            </button>
                        </div>
                        <div class="row mt-1">
                            <button  id="zoom-out-btn" class="btn btn-sm btn-success btn-success-number">
                                <i class="bi bi-zoom-out"></i>
                            </button>
                        </div>
                        <div class="row mt-1">
                            <button id="rotateRight" class="btn btn-sm btn-success btn-success-number">
                                <i class="bi bi-arrow-clockwise"></i>
                            </button>
                        </div>
                        <div class="row mt-1">
                            <button id="rotateLeft" class="btn btn-sm btn-success btn-success-number">
                                <i class="bi bi-arrow-counterclockwise"></i>
                            </button>
                        </div>
                    </div>
                    <div class="col-8">
                        <div id="image-container">
                            <div id="image-scroll-wrapper">
                                <img id="zoomable-image" style="width: 318px; height: 448px" src="" alt="Imagem PNG">
                            </div>
                        </div>
                    </div>
                    <div class="col-3">
                        <div class="card" style="max-height: 448px;">
                            <div class="card-body overflow-auto miniaturas">
                            @foreach($arrayFiles as $file)
                                <div class="image-mini">
                                    <img id="miniatura" style="width: 70px; height: 110px" src='{{ route('r2.img', ['any' => $file]) }}' alt="Miniatura">
                                </div>
                                <div class="divider mb-3 mt-3"></div>
                            @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-3">
        <div class="card text-center h-100 w-auto" style="">
            <div class="card-body color-background-white-02 text-center">
                <div class="font-regular-wt font-heading-bar">Dados do processo:</div>
                <div class="row justify-content-center mt-2">
                    <span class="d-flex align-items-center"><b class="background-trace-code font-size-jeff modal-tx-color">Identificação: {{  $processo->trace_code  }}</b></span>
                </div>
                <div class="row justify-content-center">
                    <span class="d-flex align-items-center text-center font-regular-wt">Criador: <b>{{ $processo->u_name }}</b></span>
                </div>
                <div class="row justify-content-center">
                    <span class="font-regular-wt">Edição: <b>{{ date('d/m/Y', strtotime($processo->updated_at)) }}</b></span>
                </div>
                <hr class="sidebar-divider">
                <div class="font-regular-wt font-heading-bar">Fornecedor:</div>
                <div class="row justify-content-center">
                    <div class="font-regular-wt">Nome/Doc: <b>{{ $processo->fornecedor_name }} {{ $processo->f_doc }}</b></div>
                </div>
                <hr class="sidebar-divider">
                <div class="font-regular-wt font-heading-bar">Dados da nota</div>
                <div class="row ml-0 mt-1">
                    <span class="d-flex align-items-center text-center font-regular-wt">Nº Nota: <b> {{  $processo->num_nota  }} </b></span>
                </div>
                <div class="row ml-0">
                    <span class="font-regular-wt">Processo: R$ <b>{{ App\Helpers\FormatUtils::formatMoney($processo->valor) }}</b></span>
                </div>
                <div class="row ml-0">
                    <span class="font-regular-wt">Parcela: R$ <b>{{ App\Helpers\FormatUtils::formatMoney($processo->vparcela) }}</b></span>
                </div>
                <div class="row ml-0">
                    <span class="font-regular-wt">Competencia: <b>{{ isset($processo->competencia) ? $processo->competenmcia : date('m/Y', strtotime($processo->created_at)) }}</b></span>
                </div>
                <hr class="sidebar-divider">
                <div class="font-regular-wt font-heading-ba black">Vencimento</div>
                <div class="row justify-content-center">
                    <span class="font-regular-wt"><b>{{ date('d/m/Y', strtotime($processo->pvv_dtv)) }}</b></span>
                </div>
                @if($processo->pago == true)
                <hr class="sidebar-divider">
                <div class="font-regular-wt font-heading-bar black">Data pagamento</div>
                <div class=" justify-content-center">
                    <span class="font-regular-wt"><b>{{ date('d/m/Y', strtotime($processo->data_pagamento)) }}</b></span>
                </div>
                @endif
                @if($processo->p_observacao)
                <hr class="sidebar-divider">
                <div class="font-regular-wt font-heading-bar black">Observação</div>
                <div class=" justify-content-center">
                    <span class="font-regular-wt"><b>{{ $processo->p_observacao }}</b></span>
                </div>
                @endif
            </div>
        </div>
    </div>
    <div class="col-2">
        <div class="card text-center h-100 overflow-auto w-auto">
            <div class="card-body color-background-white-02">
                @if(auth()->user()->financeiro)
                <div class="row mb-3">
                    @if($processo->pago == false)
                    <button {{ ($processo->pvv_aprovado == true && $processo->pago == false) ? null : 'disabled' }}
                        id="aprovacaoFinanceiro" name="{{ $processo->pvv_id }}" class="btn btn-primary" data-toggle="tooltip"
                        title="{{ $processo->pvv_aprovado == true ? "Aprovar pagamento" : "Ainda faltam etapas de aprovação"}}"  style="width: 200px">
                        <i class="bi bi-currency-dollar">Realizar pagamento</i>
                    </button>
                    @endif
                    @if($processo->pago == true)
                    <button id="aprovacaoFinanceiro" name="{{ $processo->pvv_id }}" class="btn btn-primary" data-toggle="tooltip"
                        title="{{ $processo->pvv_aprovado == true ? "Aprovar pagamento" : "Ainda faltam etapas de aprovação"}}"  style="width: 200px">
                        <i class="bi bi-currency-dollar">Editar pagamento</i>
                    </button>
                    @endif
                </div>
                @endif
                <div id="insert-button" class="mb-3"></div>
            </div>
        </div>
    </div>
</div>
<div class="row mt-3 w-100">
    <div class="col-6">
        <div class="card w-100">
            <div class="card-body color-background-white-02">
                <div class="chart-area">
                    <canvas id="barChart"></canvas>
                </div>
            </div>
        </div>
    </div>
    <div class="col-6">
        <div class="card" style="">
            <div class="card-body color-background-white-02">
                <div class="row">
                    <div class="col-1">
                        <div class="row">
                            <button class="btn btn-success btn-success-number" data-toggle="modal" data-target="#modalComentario" tabindex="0" data-toogle="tooltip" title="Adicionar comentario ao processo">
                                <i class="bi bi-chat-text"></i>
                            </button>
                        </div>
                        <div class="row mt-1">
                            <button class="btn btn-success btn-success-number" tabindex="0" data-toggle="modal" data-target="#modalUpload" data-toogle="tooltip" title="Adicionar arquivos">
                                <i class="bi bi-upload"></i>
                            </button>
                        </div>
                        <div class="row mt-1">
                            <button class="btn btn-success btn-success-number" tabindex="0" data-toggle="modal" data-target="#modalPendencia" data-toogle="tooltip" title="Adicionar pendencia ao processo">
                                <i class="bi bi-arrow-return-left"></i>
                            </button>
                        </div>
                        <div class="row mt-1">
                            <button {{
                                    isset($userPermission['grupo_deleta_processo']) &&
                                    $userPermission["grupo_deleta_processo"] == true || auth()->user()->financeiro ?
                                    null : "disabled"
                                }}
                                class="btn btn-success btn-success-number" tabindex="0" data-toggle="modal" data-target="#modalDeletar" data-toogle="tooltip" title="Deletar processo">
                                <i class="bi bi-trash"></i>
                            </button>
                        </div>
                        <div class="row mt-1">
                            <button class="btn btn-success btn-success-number mr-3" tabindex="0" data-toggle="modal" data-target="#modalHistorico" data-toogle="tooltip" title="Exibir historico do processo">
                                <i class="bi bi-clock"></i>
                            </button>
                        </div>
                        <div class="row mt-1">
                            <a href="{{ route('processo.protocolo', ['processo' => $processo->id]) }}" class="btn btn-success btn-success-number" role='button' data-toggle="tooltip" title="Imprimir protocolo">
                                <i class="bi bi-printer"></i>
                            </a>
                        </div>
                        <div class="row mt-1">
                            <button onclick="window.location.href='{{ route('processo.editar-processo', ['processo' => $processo->id, 'data' => $processo->pvv_dtv]) }}'" class="btn btn-success btn-success-number" tabindex="0" data-toggle="modal" data-target="#modalEdicao" data-toogle="tooltip" title="Editar valores dos campos do processo">
                                <i class="bi bi-pen"></i>
                            </button>
                        </div>
                    </div>
                    <div class="col-11">
                        <div class="card h-100">
                            <div class="card-body color-background-white-02">
                                <div class="row">
                                    <div class="font-regular-wt font-heading-bar">Empresa/Doc</div>
                                </div>
                                <div class="row mt-1">
                                    <div class="font-regular-wt">{{ $processo->e_name }} {{ App\Helpers\FormatUtils::formatDoc($processo->e_doc) }} <button id="btnDadosPagamento" class="btn btn-sm btn-success btn-success-number">Dados pagamento</button></div>
                                </div>
                                @if(isset($processo->nome_filial))
                                <div class="row mt-1">
                                    <div class="font-regular-wt font-heading-bar black">Filial</div>
                                </div>
                                <div class="row mt-1">
                                    <div class="font-regular-wt">{{ $processo->nome_filial }} {{ App\Helpers\FormatUtils::formatDoc($processo->cnpj_filial) }}</div>
                                </div>
                                @endif
                                <div class="row mt-1">
                                    <div class="font-regular-wt font-heading-bar black">Workflow</div>
                                </div>
                                <div class="row mt-1">
                                    <div class="font-regular-wt">{{ $processo->w_nome }}</div>
                                </div>
                                <div class="row mt-1">
                                    <div class="font-regular-wt font-heading-bar black">Grupo</div>
                                </div>
                                <div class="row mt-1">
                                    <div class="font-regular-wt">
                                        @foreach(session('permissions') as $permission)
                                        {{ $permission->grupo_nome }},
                                        @endforeach
                                    </div>
                                </div>
                                <div class="row mt-1">
                                    <div class="font-regular-wt font-heading-bar">Centro Custo</div>
                                </div>
                                <div class="row mt-1">
                                    <div class="font-regular-wt">
                                        @if(!is_null($processo->centrocusto_nome))
                                        {{ $processo->centrocusto_nome }}
                                        @else
                                        N/A
                                        @endif
                                    </div>
                                </div>
                                <div class="row mt-1">
                                    <div class="font-regular-wt font-heading-bar">Rateio</div>
                                </div>
                                <div class="row mt-1">
                                    <div class="font-regular-wt">
                                        @if(!is_null($processo->rateio_nome))
                                        {{ $processo->rateio_nome }}
                                        @else
                                        N/A
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
