@extends('processo.jsGrid')
@extends('processo.js.grid-js')
@extends('layout.newLayout')

@section('content')
    <input type='hidden' id='tipo' name='all' value='all'>
    <div class="row">
        <div class="col-12">
            <span class="titulo-grid-number font-regular-wt">Lista de processos</span>
        </div>
    </div>
    <div class="row">
        <div class="col-6">
            <a href="{{ route('processo.index') }}" class="ml-1 btn-novos-number btn btn-sm btn-success-number"
                style="text-decoration: underline;">
                Novos
                <span class="badge badge-success badge-counter">{{ $qtdeNovosProcessos }}</span>
            </a>
            <a href="{{ route('processo.pendentes') }}" class="ml-1 btn-pendentes-number btn btn-sm btn-success-number">
                Pendentes
                <span class="badge badge-warning badge-counter">{{ $qtdeProcessosPendentes }}</span>
            </a>
            <a href="{{ route('financeiro.index') }}" class="ml-1 btn-financeiro-number btn btn-sm btn-success-number">
                Financeiro
                <span
                    class="badge badge-dark badge-counter">{{ isset($qtdeFinanceiro) ? $qtdeFinanceiro : $processoFinanceiro }}</span>
            </a>
            <a href="{{ route('processo.completo') }}" class="ml-1 btn-concluidos-number btn btn-sm btn-success-number">
                Concluidos
                <span class="badge badge-blue badge-counter">{{ isset($qtdePago) ? $qtdePago : $processoPago }}</span>
            </a>
        </div>
        <div class="col-3">
            @include('acoesRapidas.data')
        </div>
        <div class="col-3">
            <button id="exibeFormBuscaGrid" class="btn btn-sm search-btn mr-1">
                <i class="bi bi-search"></i>
                Pesquisa
            </button>
            <button onclick="window.location.href='{{ route('processo.create') }}';"
                class="btn btn-sm btn-success btn-success-number">
                <i class="bi bi-plus"></i>
                Adicionar
            </button>
        </div>
    </div>
    <div @class(['row', 'mt-3'])>
        <div class="col-2">
            <div class="btn-group">
                <button type="button" class="btn btn-back-number btn-success dropdown-toggle btn-sm"
                        data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    Linhas {{ auth()->user()->linhas_grid }}
                </button>
                <div class="dropdown-menu">
                    <a class="dropdown-item grid-lines" data-value="10" href="#">10</a>
                    <a class="dropdown-item grid-lines" data-value="25" href="#">25</a>
                    <a class="dropdown-item grid-lines" data-value="50" href="#">50</a>
                    <a class="dropdown-item grid-lines" data-value="100" href="#">100</a>
                </div>
            </div>
        </div>
    </div>
    <div class="row mt-3">
        @include('processo.formSearchNovosProcessos')
    </div>
    <div class="row mt-3">
        @include('processo.table')
    </div>
@endsection
