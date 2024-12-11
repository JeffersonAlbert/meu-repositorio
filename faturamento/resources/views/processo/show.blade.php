@extends('layout.newLayout')

@section('content')
<div class="container">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    @if($processo->pendencia)
    <div class="alert alert-danger">
        <p>{{ date('d/m/Y H:i:s', strtotime($processo->pendencia_data)) }} {{ $processo->pendencia_obs }}</p>
    </div>
    @endif
    <div class="error-show"></div>
    <div class="row">
        @include('processo.aprovacaoProcesso')
    </div>
    <div class="row"></div>

    <!-- <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div
                    class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary" id="nomeArquivo">Cadastro processo faturamento</h6>
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
                <div class="card-body">
                    <button class="btn btn-primary" id="prevButton" tabindex="0" data-toogle="tooltip" title="Pagina anterior">
                        <i class="bi bi-skip-backward" style="font-size: 1rem; color: white;"></i>
                    </button>
                    <button class="btn btn-primary" id="nextButton" tabindex="0" data-toogle="tooltip" title="Proxima pagina">
                        <i class="bi bi-skip-forward" style="font-size: 1rem; color: white;"></i>
                    </button>
                    <button class="btn btn-secondary" id="zoom-in-btn" tabindex="0" data-toogle="tooltip" title="Aumenta o documento">
                        <i class="bi bi-zoom-in" style="font-size: 1rem; color: white;"></i>
                    </button>
                    <button class="btn btn-secondary" id="zoom-out-btn" tabindex="0" data-toogle="tooltip" title="Diminui o documento">
                        <i class="bi bi-zoom-out" style="font-size: 1rem; color: white;"></i>
                    </button>
                    <button class="btn btn-secondary" id="rotateRight" tabindex="0" data-toogle="tooltip" title="Virar o documento 90">
                        <i class="bi bi-arrow-clockwise"></i>
                    </button>
                     <button class="btn btn-secondary" id="rotateLeft" tabindex="0" data-toogle="tooltip" title="Virar o documento 90">
                        <i class="bi bi-arrow-counterclockwise"></i>
                    </button>

                    <div class="mt-3">
                        <div id="image-container">
                            <div id="image-scroll-wrapper">
                                <img id="zoomable-image" src="" alt="Imagem PNG">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4"> --><!-- inicio da linha dos botoes e informacoes -->
            <!-- <div class="row">
                <div class="card">
                    <div class="card-body">
                        <div class="row mb-3"> -->
                            <!-- <div class="chart-line" style="max-width: 400px;">
                                <canvas id="barChart"></canvas>
                            </div> -->
                        <!-- </div>
                        <div class="row">
                            <div class="text-center" style="max-width: 400px;">
                                Cedente: <b>{{ $processo->fornecedor_name }}</b>
                            </div>
                        </div>
                        <div class="row">
                            <div class="text-center" style="max-width: 400px;">
                                Empresa: <b>{{ $processo->e_name }}</b>
                            </div>
                        </div>
                        <div class="row">
                            <div class="text-center" style="max-width: 400px;">
                                Filial: <b>{{ isset($processo->f_filial) ? $processo->f_filial : 'NÃO HÁ FILIAL PARA ESSA EMPRESA' }}</b>
                            </div>
                        </div>
                        <div class="row">
                            <div class="text-center" style="max-width: 400px">
                                Nome Workflow: <b>{{ $processo->w_nome }}</b>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="text-center" style="max-width: 400px;">
                                Meu grupo: <b>
                                @foreach(session('permissions') as $permission)
                                {{ $permission->grupo_nome }},
                                @endforeach
                                </b>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <button class="btn btn-primary mr-3" data-toggle="modal" data-target="#modalComentario" tabindex="0" data-toogle="tooltip" title="Adicionar comentario ao processo">
                                <i class="bi bi-chat-text" style="font-size: 1rem; color: white;"></i>
                            </button> -->

                            <!----Parte importante     --->
                            <!-- <button class="btn btn-primary mr-3" tabindex="0" data-toggle="modal" data-target="#modalUpload" data-toogle="tooltip" title="Adicionar arquivos">
                                <i class="bi bi-cloud-upload" style="font-size: 1rem; color: white;"></i>
                            </button>
                            <button class="btn btn-danger mr-3" tabindex="0" data-toggle="modal" data-target="#modalPendencia" data-toogle="tooltip" title="Adicionar pendencia ao processo">
                                <i class="bi bi-arrow-return-left" style="font-size: 1rem; color: white;"></i>
                            </button>
                            <button {{
                                    isset($userPermission['grupo_deleta_processo']) &&
                                    $userPermission["grupo_deleta_processo"] == true || auth()->user()->financeiro ?
                                    null : "disabled"
                                }}
                                class="btn btn-danger mr-3" tabindex="0" data-toggle="modal" data-target="#modalDeletar" data-toogle="tooltip" title="Deletar processo">
                                <i class="bi bi-trash2-fill" style="font-size: 1rem; color: white;"></i>
                            </button>
                            <button class="btn btn-primary mr-3" tabindex="0" data-toggle="modal" data-target="#modalHistorico" data-toogle="tooltip" title="Exibir historico do processo">
                                <i class="bi bi-clock" style="font-size: 1rem; color: white;"></i>
                            </button>
                            <button class="btn btn-warning" tabindex="0" data-toggle="modal" data-target="#modalEdicao" data-toogle="tooltip" title="Editar valores dos campos do processo">
                                <i class="bi bi-pen" style="font-size: 1rem; color: white;"></i>
                            </button>
                        </div>
                        <div class="row mb-3">
                            <a href="{{ route('processo.protocolo', ['processo' => $processo->id]) }}" class="btn btn-warning" role='button' data-toggle="tooltip" title="Imprimir protocolo">
                                <i class="bi bi-printer" style="font-size: 1rem; color: white;"></i>
                            </a>
                        </div>
                        @if(auth()->user()->financeiro)
                        <div class="row mb-3">
                            <button {{ ($processo->pvv_aprovado == true && $processo->pago == false) ? null : 'disabled' }} id="aprovacaoFinanceiro" name="{{ $processo->pvv_id }}" class="btn btn-primary" data-toggle="tooltip" title="{{ $processo->pvv_aprovado == true ? "Aprovar pagamento" : "Ainda faltam etapas de aprovação"}}"  style="width: 400px">
                                <i class="bi bi-currency-dollar">Realizar pagamento</i>
                            </button>
                        </div>
                        @endif

                        <div id="insert-button" class="mb-3"></div>
                        <div class="row">
                            <div class="text-center" style="max-width: 400px;">
                                Identificação: <b>{{ $processo->trace_code }}</b>
                                @if($processo->p_observacao !== null)
                                    <i id="showObservacao" class="bi bi-envelope-exclamation-fill text-warning" data-toogle="tooltip" title="{{ $processo->p_observacao }}"></i>
                                @endif
                            </div>
                        </div>
                        <div class="row">
                            <div class="text-center" style="max-width: 400px;">
                                Numero da nota: <b>{{ $processo->num_nota }}</b>
                            </div>
                        </div>
                        <div class="row">
                            <div class="text-center" style="max-width: 400px;">
                                Data criação: <b>{{ date('d/m/Y H:i:s', strtotime($processo->created_at)) }}</b>
                            </div>
                        </div>
                        <div class="row">
                            <div class="text-center" style="max-width: 400px;">
                                Valor Processo: <b>R$ {{ App\Helpers\FormatUtils::formatMoney($processo->valor) }}</b>
                            </div>
                        </div>
                        <div class="row">
                            <div class="text-center" style="max-width: 400px;">
                                Valor Parcela: <b>R$ {{ App\Helpers\FormatUtils::formatMoney($processo->vparcela) }}</b>
                            </div>
                        </div>
                        <div class="row">
                            <div class="text-center" style="max-width: 400px;">
                                Vencimento: <b>{{ date('d/m/Y', strtotime($processo->pvv_dtv)) }}</b>
                            </div>
                        </div>
                        <div class="row">
                            <div class="text-center" style="max-width: 400px;">
                                Criador Processo: <b>{{ $processo->u_name }}</b>
                            </div>
                        </div>
                        <div class="row">
                            <div class="text-center" style="max-width: 400px;">
                                Ultima alteração: <b>{{ $processo->u_last_modification }}</b>
                            </div>
                        </div>
                        <div class="row">
                            <div class="text-center" style="max-width: 400px;">
                                Data alteração: <b>{{ date('d/m/Y H:i:s', strtotime($processo->updated_at)) }}</b>
                            </div>
                        </div>
                    </div>
                <div class="card-footer">
                    <a href="{{ url()->previous() }}" class="btn btn-secondary">Voltar</a>
               </div>
            </div>
        </div>--> <!--final da linha dos botoes e informacoes -->
    <!-- </div> -->
</div>
@include('processo.pdf')
@include('processo.modal')
@include('processo.grafico')
@include('processo.modalShowPdf')
@include('processo.modal.avisoPagamentoSemArquivo')
@include('processo.modal.modalVizualizaDocumentos')
@include('processo.modal.modalDadosPagamento')
@endsection
