@extends('contratos.jsContratos')
@extends('layout.newLayout')

@section('content')
<div class="row">
    <div class="col-xl-12">
        <div class="card text-left">
            {{-- card header --}}
            <div class="card-header color-head-tab py-3 d-flex flex-row align-items-center justify-content-between">
                <h6 class="m-0 font-weight-bold text-primary">Controle de contratos</h6>
                <div class="dropdown no-arrow">
                    <a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="fas fa-ellipsis-v fa-sm fa-fw text-primary"></i>
                    </a>
                    <div class="dropdown-menu dropdown-menu-ight shadow animated--fade-in" aria-labelledby="dropdownMenuLink">
                        <div class="dropdown-header color-00b269">Menu</div>
                        <a class="dropdown-item backgound-rgba-0000-10 text-primary" href="#">Novo Contrato</a>
                        <div class="dropdown-divider"></div>
                    </div>
                </div>
            </div>
            {{-- card header --}}
            {{-- card cody --}}
            <div class="card-body"  style="overflow-y: scroll">
                <div class="conttrato-messages"></div>
                @if(isset($contrato))
                <form id="formAddContrato" method="POST" action="{{ route('contrato.update', ['contrato' => $contrato->id]) }}" enctype="multipart/form-data">
                    @method('put')
                @else
                <form id="formAddContrato" method="POST" action="{{ route('contrato.store') }}" enctype="multipart/form-data">
                @endif
                    @csrf
                    <div class="contrato-messages"></div>
                    <div class="form-row">
                        <div class="form-group col-md-4">
                            <label for="contrato-name" class="col-form-label required text-primary">Contrato:</label>
                            <input name="nome" type="text" class="input-login form-control" id="contrato-name" value="{{ isset($contrato) ? $contrato->nome : null }}">
                        </div>
                        @if(isset($contrato))
                        <div class="form-group col-md-4">
                            <label class="label-number" for="search_cliente" class="label-number col-form-label required text-primary">Cliente:</label>
                            <input type="text" name="cliente_contrato" class="input-login form-control" id="search_cliente" value="{{ isset($contrato) ? $contrato->id_cliente. ' - '.$contrato->cliente_cnpj. ' - '.  $contrato->cliente_nome : null }}">
                        </div>
                        @else
                        <div class="form-group col-md-4 input-group">
                            <label for="search_cliente" class="col-form-label required text-primary">Cliente:</label>
                            <div class="input-group">
                                <input id="search_cliente" name="cliente_contrato" type="text" data-route="{{ route('autocomplete.cliente-typeahead') }}" class="input-login form-control search-cliente text-rimary" placeholder="Cliente Id CPF/CNPJ" value="{{ isset($contasreceber) ? $contasreceber->searchable : null }}" required>
                                <div class="input-group-append text-primary">
                                    <span class="input-group-text open-modal" id="cad-cliente" data-toggle="modal" data-target="#clienteModal">
                                        <i class="bi bi-person-plus-fill"></i>
                                    </span>
                                </div>
                            </div>
                        </div>
                        @endif
                        <div class="form-group col-md-4 text-primary">
                            <label for="indice" class="col-form-label required text-primary">Indice:</label>
                            <select id="indice" name="indice" class="input-login form-control text-primary">
                                <option value="{{ isset($contrato) ? $contrato->indice : null }}">{{ isset($contrato) ? strtoupper($contrato->indice) : "Selecione o indice" }}</option>
                                @forelse($indices as $indice)
                                    <option value="{{ $indice }}">{{ $indice }}</option>
                                @empty
                                    <option>Selecione o indice</option>
                                @endforelse
                            </select>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-3">
                          <label for="vigencia_inicial" class="required col-form-label">Data inicial da vigencia:</label>
                          <input class="input-login form-control" type="date" name="vigencia_inicial" id="vigencia_inicial" value="{{ isset($contrato) ? date('Y-m-d', strtotime($contrato->vigencia_ini)) : null }}">
                        </div>
                        <div class="form-group col-md-3">
                          <label for="vigencia_final" class="required col-form-label">Data final da vigencia:</label>
                          <input class="input-login form-control" type="date" name="vigencia_final" id="vigencia_final" value="{{ isset($contrato) ? date('Y-m-d', strtotime($contrato->vigencia_fim)) : null }}">
                        </div>

                        @if(isset($contrato))
                        <div class="form-group input-group col-md-3">
                            <label for="upload_contrato" class="col-form-label required">Arquivo:</label>
                            <button id="upload_contrato" name="upload_contrato" class="btn dropdown-toggle col-12 btn-transparent" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                Arquivos
                            </button>
                        @else
                        <div class="form-group col-md-3">
                            <label for="upload_contrato" class="col-form-label required">Arquivo:</label>
                            <input id="upload_contrato" class="input-login form-control" type="file" multiple name="files[]">
                        @endif
                        </div>
                        <div class="form-group col-md-3">
                          <label for="valor_contrato" class="col-form-label required">Valor contrato:</label>
                          <div class="input-group">
                              <div class="input-group-prepend">
                                  <span class="input-group-text">R$</span>
                              </div>
                              <input type="text" class="input-login form-control formatMoney" name='valor' id="valor_contrato" value="{{ isset($contrato) ? App\Helpers\FormatUtils::formatMoney($contrato->valor) : null }}">
                          </div>
                        </div>
                    </div>
            </div>
            {{-- card body --}}
            {{-- card footer --}}
            <div class="card-footer color-footer-wt">
                <a href="{{ url()->previous() }}" class="btn btn-success btn-back-number">Voltar</a>
                <button class="btn btn-success">Enviar</button>
                </form>
            </div>
            {{-- card footer --}}
        </div>
    </div>
</div>
@if(isset($contrato))
@include('contratos.modalFiles')
@endif
@include('contratos.modalCadClientes')
@endsection
