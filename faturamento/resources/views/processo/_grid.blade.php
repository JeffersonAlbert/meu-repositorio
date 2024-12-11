@extends('processo.jsGrid')
@extends('layout.newLayout')

@section('content')
<div class="row">
<!-- Area Chart -->
    <div class="col-xl-12 col-lg-12">
        <div class="row mt-3">
            <div class="container">
                <div class="row">
                    <div class="col-12">
                        <span class="titulo-grid-number font-regular-wt">Lista de processos</span>
                    </div>
                </div>
                <div class="row mt-3">
                    <div class="col-6">
                        <a class="ml-1 btn-novos-number btn btn-sm btn-success-number
                        {{ (isset($pendencia) && $pendencia == false &&
                        !isset($novosProcessos)) ||
                        isset($novosProcessos) &&
                        $novosProcessos == true ? "active" : null }}"
                        href="{{ route('processo.index') }}" style="color : white; {{ (isset($pendencia) &&
                            $pendencia == false &&
                            !isset($novosProcessos)) ||
                            isset($novosProcessos) &&
                            $novosProcessos == true ? "text-decoration: underline;" : null }}">Novos
                            <span class="badge badge-success badge-counter">{{ $qtdeNovosProcessos }}</span>
                        </a>
                        <a class="ml-1 btn-pendentes-number btn btn-sm btn-success-number
                            {{ (isset($pendencia) &&
                            $pendencia == true) ? "active" : null }}"
                            href="{{ route('processo.pendentes') }}"
                            style="color: white; {{ (isset($pendencia) &&
                            $pendencia == true) ? "text-decoration: underline;" : null }}">Pendentes
                            <span class="badge badge-warning badge-counter">{{ $qtdeProcessosPendentes }}</span>
                        </a>
                        <a class="ml-1 btn-financeiro-number btn btn-sm btn-success-number
                            {{ isset($qtdeFinanceiro) ||
                            (isset($searchAprovado) &&
                            $searchAprovado == true) ? "active" : null }}"
                            href="{{ route('financeiro.index') }}" style="color: white; {{ isset($qtdeFinanceiro) ||
                            (isset($searchAprovado) &&
                            $searchAprovado == true) ? "text-decoration: underline" : null }}">Financeiro
                            <span class="badge badge-dark badge-counter">{{ isset($qtdeFinanceiro) ? $qtdeFinanceiro : $processoFinanceiro }}</span>
                        </a>
                        <a class="ml-1 btn-concluidos-number btn btn-sm btn-success-number
                            {{ isset($qtdePago) ? "active" : null }}"
                            href="{{ route('processo.completo') }}" style="color: white; {{ isset($qtdePago) ? "text-decoration: underline" : null}}">Concluidos
                            <span class="badge badge-blue badge-counter">{{ isset($qtdePago) ? $qtdePago : $processoPago }}</span>
                        </a>
                    </div>
                    <div class="col-2">

                    </div>
                    <div class="col-1 text-right">

                    </div>
                    <div class="col-3 text-right">
                        <div class="row justify-content-end">
                            <button id="exibeFormBuscaGrid" class="btn btn-sm search-btn mr-1">
                                <i class="bi bi-search"></i>
                                Pesquisa
                            </button>
                            <button onclick="window.location.href='{{ route('processo.create') }}';" class="btn btn-sm btn-success btn-success-number">
                                <i class="bi bi-plus"></i>
                                Adicionar
                            </button>
                        </div>
                    </div>
                </div>
                <div class="row">
                    @include('processo.formBuscaGrid')
                </div>
                <div class="row mt-3">
                    @include('processo.table')
                </div>
            </div>
        </div>
        <!-- <div class="card h-100 shadow-number mb-4" > -->
            <!-- Card header - dropdow -->
            <!-- <div
                class="card-header-wt py-3 d-flex flex-row align-items-center justify-content-between">
                <h6 class="m-0 font-weight-bold text-number-wt">Lista processos</h6>
                <div class="dropdown no-arrow">
                    <button id="exibeFormBuscaGrid" class="btn btn-success btn-sm"><i class="bi bi-search"></i></button>
                    <a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink"
                        data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="fas fa-ellipsis-v fa-sm fa-fw text-gray-400"></i>
                    </a>
                    <div class="dropdown-menu dropdown-menu-right shadow animated--fade-in"
                        aria-labelledby="dropdownMenuLink">
                        <div class="dropdown-header">Dropdown Header:</div>
                        <a class="dropdown-item" href="{{ route("processo.create") }}">Add Processo</a>
                        <a class="dropdown-item" href="#">Another action</a>
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item" href="#">Something else here</a>
                    </div>
                </div>
            </div>
            @include('processo.formBuscaGrid') -->
            <!-- Card Body -->
            <!-- <div class="card-body-wt" style="overflow-y: scroll;">
                <ul class="nav nav-tabs-wt nav-justified">
                  <li class="nav-item">
                    <a class="nav-link-wt
                        {{ (isset($pendencia) && $pendencia == false &&
                        !isset($novosProcessos)) ||
                        isset($novosProcessos) &&
                        $novosProcessos == true ? "active" : null }}"
                        href="{{ route('processo.index') }}" style="color : blue; {{ (isset($pendencia) &&
                        $pendencia == false &&
                        !isset($novosProcessos)) ||
                        isset($novosProcessos) &&
                        $novosProcessos == true ? "text-decoration: underline;" : null }}">Novos
                        <span class="badge badge-primary badge-counter">{{ $qtdeNovosProcessos }}</span>
                    </a>
                  </li>
                  <li class="nav-item">
                    <a class="nav-link-wt
                        {{ (isset($pendencia) &&
                        $pendencia == true) ? "active" : null }}"
                        href="{{ route('processo.pendentes') }}"
                        style="color: red; {{ (isset($pendencia) &&
                        $pendencia == true) ? "text-decoration: underline;" : null }}">Pendentes
                        <span class="badge badge-danger badge-counter">{{ $qtdeProcessosPendentes }}</span>
                    </a>
                  </li>
                  <li class="nav-item">
                    <a class="nav-link-wt
                        {{ isset($qtdeFinanceiro) ||
                        (isset($searchAprovado) &&
                        $searchAprovado == true) ? "active" : null }}"
                        href="{{ route('financeiro.index') }}" style="color: black; {{ isset($qtdeFinanceiro) ||
                        (isset($searchAprovado) &&
                        $searchAprovado == true) ? "text-decoration: underline" : null }}">Financeiro
                        <span class="badge badge-dark badge-counter">{{ isset($qtdeFinanceiro) ? $qtdeFinanceiro : $processoFinanceiro }}</span>
                    </a>
                  </li>
                  <li class="nav-item">
                    <a class="nav-link-wt
                        {{ isset($qtdePago) ? "active" : null }}"
                        href="{{ route('processo.completo') }}" style="color: green; {{ isset($qtdePago) ? "text-decoration: underline" : null}}">Processo Concluido
                        <span class="badge badge-success badge-counter">{{ isset($qtdePago) ? $qtdePago : $processoPago }}</span>
                    </a>
                  </li>
                </ul>
                <div class="chart-area">
                    <table class="table table-responsive-sm">
                        <thead class="text-number-wt">
                            <th scope="col">Thumb</th>
                            <th scope="col">Fornecedor</th>
                            <th scope="col">Usuarios</th>
                            <th scope="col">Valor</th>
                            <th scope="col">Vencimento</th>
                            <th scope="col">Ações</th>
                        </thead>
                        <tbody> -->
                        {{-- dd($processos) --}}
                        <!-- @forelse($processos as $processo)
                            @if(file_exists(json_decode($processo->file)[0]))
                            <div hidden><img src="{{ route('pdf-thumbnail', ['pdf' => json_decode($processo->file)[0]]) }}"></div>
                            @endif
                            <tr>
                                <td>
                                    <a href="{{ route('processo.aprovacao', ['id' => $processo->id, 'vencimento' => $processo->pvv_dtv])}}">
                                        <img style="max-width: 100px; max-height: 135px;" src="{{ file_exists("thumbnails/".str_replace('.pdf','.jpg',json_decode($processo->file)[0])) ? asset('thumbnails/'.str_replace('.pdf','.jpg',json_decode($processo->file)[0])) : asset('thumbnails/error.jpg') }}">
                                    </a>
                                </td>
                                <td><b>Identificação: </b>{{ $processo->trace_code }}<br>
                                    <b>Nome:</b> {{ $processo->f_name }}<br>
                                    <b>Numero nota:</b> {{ $processo->num_nota }}<br>
                                    <b>Doc:</b> {{ $processo->f_doc }}<br>
                                    <b>Emissao:</b> {{ date('d/m/Y', strtotime($processo->p_emissao)) }}
                                </td>
                                <td>
                                    <b>Criador:</b> {{$processo->u_name}}<br>
                                    <b>Ultima alteração</b> {{$processo->u_last_modification}}<br>
                                    <b>Lançamento:</b> {{ date('d/m/Y H:i:s',strtotime($processo->created_at)) }}<br>
                                    <b>Filial:</b> {{ $processo->filial_nome }}
                                </td>
                                <td>
                                    <b>Valor do mes:</b> R$ {{ App\Helpers\FormatUtils::formatMoney($processo->vparcela) }}<br>
                                    <b>Valor do processo:</b> R$ {{ App\Helpers\FormatUtils::formatMoney($processo->valor) }}<br>
                                </td>
                                <td>{{date('d/m/Y', strtotime($processo->pvv_dtv))}}
                                <td>
                                    <div class="row m-2">
                                        <a href="{{ route('processo.protocolo', ['processo' => $processo->id]) }}" class="btn btn-warning btn-sm" role='button'><i class="bi bi-pencil"></i> Protocolo</a>
                                    </div>
                                    @if(isset($processo->status_aprovacao))
                                    <div class="row m-2">
                                       <button class="btn btn-sm btn-danger"
                                        {{ $processo->status_aprovacao ? "hidden" : null }}>
                                        <i class="bi bi-exclamation-circle"></i>
                                        {{ $processo->status_aprovacao ? "Aprovado" : "Pendente" }}
                                        </button>
                                    </div>
                                    @endif
                                </td>
                            </tr>
                        @empty
                        @endforelse
                        </tbody>
                    </table>
                </div>
            </div> -->
            <!-- card body -->
            <!-- <div class="card-footer-wt">
                <div class="row" >
                    <div class="pagination">
                        {{ $processos->links() }}
                    </div>
                </div>
                <div class="row">
                    <a href="{{ url()->previous() }}" class="btn btn-secondary">Voltar</a>
                </div>
            </div>-->
        <!-- </div> -->
    </div>
</div>
@include('processo.modal')
@endsection
