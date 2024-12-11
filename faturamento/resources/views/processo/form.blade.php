@extends('processo.js.form-js')
@extends('processo.categoria-financeira.categoria-financeira-js')
@extends('processo.js')
@extends('layout.newLayout')

@section('content')
<div class="row">
    <!-- Area chart -->
    <div class="col-xl-12 col-lg-12">
        <div class="form-processo-error alert alert-danger" style="display: none;"></div>
        @if(count($workflow) == 0)
        <div class="alert alert-danger" style="display: block;">
            <p>Antes de lançar um novo processo, precisa criar os Workflows. <a href="{{ route('workflow.create') }}">Clique aqui!</a></p>
        </div>
        @endif
        @include('processo.newForm')

    </div>
</div>
@include('processo.modal')
@include('processo.modal.formCadTipoCobranca')
@include('processo.modal.formCadCentroCusto')
@include('processo.modal.formCadRateio')
@include('processo.modal.formCadCategoriaFinanceira')
@include('processo.modal.modalRemoveArquivo')
@endsection
