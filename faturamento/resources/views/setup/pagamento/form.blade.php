@extends('setup.pagamento.js')
@extends('layout.newLayout')

@section('content')
<div class="row">
    <div class="col-xl-12 col-lg-7">
        <div class="form-setup-error"></div>
        <div class="card w-100 shadow mb-4">
            <div class="card-header-wt color-background-c1e5d3 py-3 d-flex flex-row align-items-center justify-content-between">
                <h6 class="mb-0 font-weight-bold text-primary">Setup da empresa</h6>
                <div class="dropdown no-arrow">
                    <a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink">
                        <i class="fas fa-ellipsis-v fa-sm fa-fw text-gray-400"></i>
                    </a>
                    <div class="dropdown-menu dropdown-menu-right shadown animated--fade-in" aria-labelledby="dropdownMenuLink">
                        <div class="dropdown-header">Dropdown Header:</div>
                    </div>
                </div>
            </div>
            <div class="card-body-wt" style="overflow-y: scroll;">
                @if(isset($formaPagamento))
                <form id="formaPagamentoForm" action="{{ route('forma-pagamento.update', ['forma_pagamento' => $formaPagamento->id]) }}" method="POST">
                @method('PUT')
                @else
                <form id="formaPagamentoForm" action="{{ route('forma-pagamento.store') }}" method="POST">
                @endif
                @csrf
                    <div class="form-row">
                        <div class="form-group col-md-2">
                            <label for="nome">Forma de pagamento</label>
                            <input name="nome" id="nome" class="form-control" type="text" value="{{ isset($formaPagamento) ? $formaPagamento->nome : null }}">
                        </div>
                    </div>
                </form>
            </div>
            <div class="card-footer-wt">
                <button id="enviarFormaPagamento" class="btn bnt-sm btn-success">{{ isset($formaPagamento) ? "Alterar" : "Enviar" }}</button>
            </div>
        </div>
    </div>
</div>
@endsection
