@extends('setup.js')
@extends('layout.newLayout')

@section('content')
<div class="row">
    <div class="col-xl-12 col-lg-7">
        <div class="form-setup-error"></div>
        <div class="card w-100 shadow mb-4">
            <div class="card-header color-head-tab py-3 d-flex flex-row align-items-center justify-content-between">
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
            <div class="card-body" style="overflow-y: scroll;">
                @if(isset($setup))
                <form id="setupForm" action="{{ route('setup.update', ['setup' => $setup->id]) }}" method="POST">
                @method('PUT')
                @else
                <form id="setupForm" action="{{ route('setup.store') }}" method="POST">
                @endif
                @csrf
                    <div class="form-row">
                        <div class="form-group col-md-2">
                            <label for="dias_vencimento">Dias do vencimento</label>
                            <input name="diasVencimento" id="dias_vencimento" class="input-login form-control" type="text" value="{{ isset($setup) ? $setup->dias_antes_vencimento : null }}">
                        </div>
                        <div class="form-group col-md-2">
                            <label for="dias_inatividade">Dias inativo</label>
                            <input name="diasInatividade" id="dias_inatividade" class="input-login form-control" type="text" value="{{ isset($setup) ? $setup->dias_sem_movimentacao : null }}">
                        </div>
                        <div class="form-group col-md-2">
                            <div class="custom-control custom-switch">
                                <input type="checkbox" class="input-login custom-control-input" name="exige_ordem_processo" id="customSwitch1" {{ isset($setup) && $setup->exige_ordem_processo == true ? "checked": null }}>
                                <label class="custom-control-label" for="customSwitch1">Exigir ordem de aprovaçao no pagamento</label>
                            </div>
                        </div>
                        <div class="form-group col-md-2">
                            <label for="dias_vencimento_maximo">Dias Vencimento</label>
                            <input name="diasVencimentoMaximo" id="dias_vencimento_maximo" class="input-login form-control" type="text" value="{{ isset($setup) ? $setup->dias_vencimento_maximo : null }}">
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-2">
                            <label for="upload_logo" class="label-number">Enviar logo da empresa</label>
                            <input type="file" name="logo" id="upload_logo">
                        </div>
                    </div>
                </form>
            </div>
            <div class="card-footer">
                <button id="enviarSetup" class="btn bnt-sm btn-success">{{ isset($setup) ? "Alterar" : "Enviar" }}</button>
            </div>
        </div>
    </div>
</div>
@endsection
