@extends('grupoprocesso.js')
@extends('layout.newLayout')

@section('content')
    <div class="row">
        <!-- Area chart-->
        <div class="col-xl-12 col-lg-7">
            <div class="card shadow mb-4">
                <!-- Card header - dropdown -->
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">Cadastro de grupo</h6>
                    <div class="dropdown no-arrow">
                        <a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown"
                            aria-haspopup="true" aria-expanded="false">
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
                <!-- Fim header do card -->
                <!-- Card body -->
                <div class="card-body">
                    <div class="chart-area">
                        @if (isset($grupoprocesso))
                            <form id="grupoForm" method="POST"
                                action="{{ route('grupoprocesso.update', ['grupoprocesso' => $grupoprocesso->id]) }}">
                                <input name="_method" type="hidden" value="PUT">
                            @else
                                <form id="grupoForm" method="POST" action="{{ route('grupoprocesso.store') }}">
                        @endif
                        @csrf
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <input name="nome" type="text" class="input-login form-control"
                                    placeholder="Nome grupo"
                                    value="{{ isset($grupoprocesso) ? $grupoprocesso->nome : null }}">
                            </div>
                        </div>
                        @if (auth()->user()->master == true)
                            <div class="form-row">
                                <div class="form-group col-md-12">
                                    <select name="id_empresa" class="form-control form-select" aria-label=".form-select">
                                        @if (isset($empresas))
                                            <option value=''>Selecione uma empresa</option>
                                            @forelse($empresas as $empresa)
                                                <option value="{{ $empresa->id }}">{{ $empresa->razao_social }}</option>
                                            @empty
                                                <option value="">Nada aqui ainda</option>
                                            @endforelse
                                        @else
                                            <option value="">Nada aqui ainda</option>
                                        @endif
                                    </select>
                                </div>
                            </div>
                        @else
                            <div class="form-row">
                                <div class="form-group col-md-12">
                                    <input disabled name="nome_empresa" type="text" class="form-control"
                                        placeholder="Nome Empresa"
                                        value="{{ isset($empresa) ? $empresa->razao_social : null }}">
                                    <input hidden name="id_empresa" value="{{ isset($empresa) ? $empresa->id : null }}">
                                </div>
                            </div>
                        @endif
                        <div class="form-row">
                            <h6 class="form-group m-0 mb-3 font-weight-bold text-primary">Permissões</h6>
                        </div>

                        <div class="form-row">
                            <div class="form-group col-md-3">
                                <div class="input-group  mb-3">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text">
                                            <input
                                                {{ isset($grupoprocesso->criar_usuario) && $grupoprocesso->criar_usuario ? 'checked' : null }}
                                                type="checkbox" aria-label=".ccsChecked" name="criar_usuario">
                                        </div>
                                    </div>
                                    <input type="text" class="form-control .ccsChecked" value="Criar Usuario" disabled>
                                </div>
                            </div>
                            <div class="form-group col-md-3">
                                <div class="input-group  mb-3">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text">
                                            <input
                                                {{ isset($grupoprocesso->move_processo) && $grupoprocesso->move_processo ? 'checked' : null }}
                                                type="checkbox" aria-label=".ccsChecked" name="move_processo">
                                        </div>
                                    </div>
                                    <input type="text" class="form-control .ccsChecked" value="Move Processo" disabled>
                                </div>
                            </div>
                            <div class="form-group col-md-3">
                                <div class="input-group  mb-3">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text">
                                            <input
                                                {{ isset($grupoprocesso->deleta_processo) && $grupoprocesso->deleta_processo ? 'checked' : null }}
                                                type="checkbox" aria-label=".ccsChecked" name="deleta_processo">
                                        </div>
                                    </div>
                                    <input type="text" class="form-control .ccsChecked" value="Exclui Processo" disabled>
                                </div>
                            </div>
                            <div class="form-group col-md-3">
                                <div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text">
                                            <input
                                                {{ isset($grupoprocesso->criar_fluxo) && $grupoprocesso->criar_fluxo ? 'checked' : null }}
                                                type="checkbox" aria-label=".cssChecked" name="criar_fluxo">
                                        </div>
                                    </div>
                                    <input type="text" class="form-control .cssChecked" value="Cria Work Flow"
                                        disabled>
                                </div>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="mb-3">
                                @if (isset($grupoprocesso))
                                    <button class="btn btn-warning btn-submit">Alterar</button>
                                @else
                                    <button id="inserirGrupo" class="btn btn-success">Enviar</button>
                                @endif
                                <a class="btn btn-secondary" href="{{ url()->previous() }}">Voltar</a>
                            </div>
                        </div>
                        </form>
                    </div>
                </div>
                <div class="card-footer">

                </div>
            </div>
        </div>

    @endsection
