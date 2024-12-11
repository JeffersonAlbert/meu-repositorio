@extends('processo.jsGrid')
@extends('processo.js.grid-js')
@extends('layout.newLayout')

@section('content')
<input type='hidden' id='tipo' name='financeiro' value='financeiro'>
<div class="row">
    <div class="col-12">
        <span class="titulo-grid-number font-regular-wt">Lista de processos</span>
    </div>
</div>
<div class="row">
    <div class="col-6">
        <a href="{{ route('processo.index') }}" class="ml-1 btn-novos-number btn btn-sm btn-success-number" style="text-decoration: none;">
            Novos
            <span class="badge badge-success badge-counter">{{ $qtdeNovosProcessos }}</span>
        </a>
        <a href="{{ route('processo.pendentes') }}" class="ml-1 btn-pendentes-number btn btn-sm btn-success-number" style="text-decoration: none;">
            Pendentes
            <span class="badge badge-warning badge-counter">{{ $qtdeProcessosPendentes }}</span>
        </a>
        <a href="{{ route('financeiro.index') }}" class="ml-1 btn-financeiro-number btn btn-sm btn-success-number" style="text-decoration: underline;">
            Financeiro
            <span class="badge badge-dark badge-counter">{{ isset($qtdeFinanceiro) ? $qtdeFinanceiro : $processoFinanceiro }}</span>
        </a>
        <a href="{{ route('processo.completo') }}" class="ml-1 btn-concluidos-number btn btn-sm btn-success-number">
            Concluidos
            <span class="badge badge-blue badge-counter">{{ isset($qtdePago) ? $qtdePago : $processoPago }}</span>
        </a>
    </div>
    <div class="col-3">
    @include('processo.acoesRapidas.datas')
    </div>
    <div class="col-3">
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
<div class="row mt-3">
    @include('processo.formSearchNovosProcessos')
</div>
<div class="row mt-3">
    @include('processo.table')
</div>
@endsection
