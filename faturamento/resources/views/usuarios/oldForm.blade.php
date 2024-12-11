@extends('usuarios.js')
@extends('layout.newLayout')

@section('content')
@include('usuarios.newForm')
<div class="row">
    <!-- Area chart -->
    <div class="col-xl-12 col-lg-7">
        <div class="form-usuario-error"></div>
        <div class="card shadow mb-4" >
        <!-- Card Header - Dropdown -->
            <div
                class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                <h6 class="m-0 font-weight-bold text-primary">Cadastro usuarios</h6>
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
            <!-- Fim header do card -->
            <!-- Card Body -->
            <div class="card-body" style="overflow-y: scroll;">
                <div class="chart-area">
                @if(isset($user))
                    <form id="usuarioForm" method="POST" action="{{ route('usuarios.update', ['usuario' => $user->id]) }}">
                    {{ method_field('PUT') }}
                @else
                    <form id="usuarioForm" method="POST" action="{{ route('usuarios.store') }}">
                @endif
                    @csrf
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <input name="name" type="text" class="form-control" placeholder="Nome" value="{{ (isset($user)) ? $user->name : null}}">
                            </div>
                            <div class="form-group col-md-6">
                                <input name="last_name" type="text" class="form-control" placeholder="Sobrenome" value="{{ (isset($user)) ? $user->last_name : null }}">
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-4">
                                <input name="email" type="text" class="form-control" placeholder="E-mail" value="{{ (isset($user)) ? $user->email : null }}">
                            </div>
                            <!-- <div class="form-group col-md-4 input-group">
                                <input id="password" name="password" type="password" class="form-control" placeholder="Password">
                                <div class="input-group-append">
                                    <span class="input-group-text" id="olho">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-eye" viewBox="0 0 16 16">
                                            <path d="M16 8s-3-5.5-8-5.5S0 8 0 8s3 5.5 8 5.5S16 8 16 8zM1.173 8a13.133 13.133 0 0 1 1.66-2.043C4.12 4.668 5.88 3.5 8 3.5c2.12 0 3.879 1.168 5.168 2.457A13.133 13.133 0 0 1 14.828 8c-.058.087-.122.183-.195.288-.335.48-.83 1.12-1.465 1.755C11.879 11.332 10.119 12.5 8 12.5c-2.12 0-3.879-1.168-5.168-2.457A13.134 13.134 0 0 1 1.172 8z"/>
                                            <path d="M8 5.5a2.5 2.5 0 1 0 0 5 2.5 2.5 0 0 0 0-5zM4.5 8a3.5 3.5 0 1 1 7 0 3.5 3.5 0 0 1-7 0z"/>
                                        </svg>
                                    </span>
                                </div>
                            </div>
                            <div class="form-group col-md-4 input-group-append">
                                <input id="confirm-password" name="confirm-password" type="password" class="form-control" placeholder="Confirme Password">
                                <div class="input-group-append">
                                    <span class="input-group-text" id="olho-confirm">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-eye" viewBox="0 0 16 16">
                                            <path d="M16 8s-3-5.5-8-5.5S0 8 0 8s3 5.5 8 5.5S16 8 16 8zM1.173 8a13.133 13.133 0 0 1 1.66-2.043C4.12 4.668 5.88 3.5 8 3.5c2.12 0 3.879 1.168 5.168 2.457A13.133 13.133 0 0 1 14.828 8c-.058.087-.122.183-.195.288-.335.48-.83 1.12-1.465 1.755C11.879 11.332 10.119 12.5 8 12.5c-2.12 0-3.879-1.168-5.168-2.457A13.134 13.134 0 0 1 1.172 8z"/>
                                            <path d="M8 5.5a2.5 2.5 0 1 0 0 5 2.5 2.5 0 0 0 0-5zM4.5 8a3.5 3.5 0 1 1 7 0 3.5 3.5 0 0 1-7 0z"/>
                                        </svg>
                                    </span>
                                </div>
                            </div>
                        </div> -->
                        <!-- <div class="form-row">
                            @if(auth()->user()->administrator == true || auth()->user()->master == true)
                            <div class="form-group col-md-3">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text">
                                            <input {{ isset($user->administrator) && ($user->administrator) ? 'checked' : null }} type="checkbox" aria-label=".administrator" name="administrator" value="1">
                                        </div>
                                        <input type="text" class="form-control administrator" value="Administrador do sistema" disabled>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group col-md-3">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text">
                                            <input {{ isset($user->financeiro) && ($user->financeiro) ? 'checked' : null }} type="checkbox" aria-label=".financeiro" name="financeiro" value="1">
                                        </div>
                                        <input type="text" class="form-control financeiro" value="Faz pagamentos" disabled>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group col-md-3">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text">
                                            <input {{ isset($user->receber_contas) && ($user->receber_contas) ? 'checked' : null }} type="checkbox" aria-label=".receber" name="receber" value="1">
                                        </div>
                                        <input type="text" class="form-control receber" value="Movimenta recebiveis" disabled>
                                    </div>
                                </div>
                            </div>
                            @endif
                            @if(isset(auth()->user()->master) && auth()->user()->master == true)
                            <div class="form-group col-md-4">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text">
                                            <input {{ isset($user->master) && ($user->master == true) ? 'checked' : null }} type="checkbox" aria-label=".master" name="master" value="1" >
                                        </div>
                                        <input type="text" class="form-control master" value="Master do sistema" disabled>
                                    </div>
                                </div>
                            </div>
                            @endif
                        </div> -->
                        <!-- <div class="form-row">
                            <div class="form-group col-md-12">
                                <div id="grupos_hidden"></div>
                                <label for="grupos" class="form-label">Grupos do usuario</label>
                                <input type="text" id="grupo-selecionado" class="form-control" data-role="tagsinput" >
                                <input type="text" id="grupos-select" data-provide="typeahead" class="form-control" value="">
                            </div>
                        </div> -->
                        <div class="form-row">
                            <div class="form-group col-md-12">
                                <label for="id_empresa" class="form-label">Empresas:</label>
                                @if(isset($user->id_empresa) and !(auth()->user()->master))
                                <input hidden value="{{$user->id_empresa}}" name="id_empresa">
                                <input disabled type="text" class="form-control" value="{{ $user->empresa_name }}">
                                @elseif(!auth()->user()->master)
                                <input hidden value="{{$empresas->id}}" name="id_empresa">
                                <input type="text" value="{{$empresas->nome}}" class="form-control" disabled>
                                @elseif(auth()->user()->master == true)
                                <select name="id_empresa" class="form-control form-select" aria-label=".form-select">
                                @if(isset($empresas))
                                    <option>Selecione uma empresa</option>
                                @endif
                                @forelse($empresas as $empresa)
                                    <option value="{{ $empresa->id }}">{{ $empresa->razao_social }}</option>
                                @empty
                                    <option>Nada aqui ainda</option>
                                @endforelse
                                </select>
                                @endif
                            </div>
                        </div>
                        <div class="form-row">
                            @forelse($filiais as $filial)
                            <div class="form-group col-md-3">
                                <label for="id_empresa" class="form-label">Filial:</label>
                                <div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text">
                                            <input type="checkbox" aria-label=".filialCheked" name="filiais[]" value="{{ $filial->id }}" {{ (isset($user) && App\Helpers\Utils::findFilialIdInArraySession(session()->get('filiais'), $filial->id)) ? 'checked' : null }}>
                                        </div>
                                    </div>
                                    <input type="text" class="form-control .filialChecked" value="{{ $filial->razao_social }}" disabled>
                                </div>
                            </div>
                            @empty
                            @endforelse
                        </div>
                        <div class="form-row">
                        @forelse($ccsChecked as $cc)
                            <div class="form-group col-md-3">
                                <div class="input-group  mb-3">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text">
                                            <input checked type="checkbox" aria-label=".ccsChecked" name="{{ $cc->nome }}" value="{{ $cc->id }}">
                                        </div>
                                    </div>
                                    <input type="text" class="form-control .ccsChecked" value="{{ $cc->nome }}" disabled>
                                </div>
                            </div>
                        @empty

                        @endforelse
                        @forelse($ccs as $cc)
                            <div class="form-group col-md-3">
                                <div class="input-group  mb-3">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text">
                                            <input type="checkbox" aria-label=".ccs" name="{{ $cc->nome }}" value="{{ $cc->id }}">
                                        </div>
                                    </div>
                                    <input type="text" class="form-control .ccs" value="{{ $cc->nome }}" disabled>
                                </div>
                            </div>
                        @empty

                        @endforelse
                        </div>
                        <div class="form-row">
                            <div class="mb-3">
                            @if(isset($user))
                                <button id="inserirUsuario" class="btn btn-warning btn-submit">Alterar</button>
                                <a href="{{ url()->previous() }}" class="btn btn-secondary">Voltar</a>
                            @else
                                <button id="inserirUsuario" class="btn btn-success btn-submit">Inserir</button>
                                <a href="{{ url()->previous() }}" class="btn btn-secondary">Voltar</a>
                            @endif
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <!-- fim do body do card -->
        </div>
    </div>
</div>
@endsection
