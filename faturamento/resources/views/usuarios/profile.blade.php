@extends('usuarios.js')
@extends('layout.newLayout')

@section('content')
<div class="row">
    <div class="col-xl-12 col-lg-7">
        <div class="card h-100 shadow-number mb-4">
            <div
                class="card-header-wt py-3 d-flex flex-row align-items-center justify-content-between">
                <h6 class="m-0 font-weight-bold text-number-wt">Profile</h6>
                <div class="dropdown no-arrow">
                </div>
            </div>
            <div class="card-body-wt" style="overflow-y: scroll;">
                <div class="row">
                    <div class="col-lg-2">
                        <!-- Botão de upload de imagem -->
                        <div class="card mb-4">
                            <div class="card-body text-center text-number-wt foto-container">
                                <img id="user_photo" src="{{ isset($user->perfil_img) &&
                                $user->perfil_img !== null && file_exists(public_path('img/static/perfil/'.auth()->user()->perfil_img)) ?
                                asset('img/static/perfil/'.$user->perfil_img) :
                                "https://mdbcdn.b-cdn.net/img/Photos/new-templates/bootstrap-chat/ava3.webp" }}" alt="avatar"
                                    class="rounded-circle img-fluid" style="width: 150px;">
                                <i class="fa fa-camera" data-toggle="modal" data-target="#uploadImagemPerfil"></i> <!-- Ícone da câmera (use um ícone real aqui) -->
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-10">
                        <div class="card mb-4">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-sm-3">
                                        <p class="mb-0"><h6 class="font-weight-bold">Nome</h6></p>
                                    </div>
                                    <div class="col-sm-9">
                                        <p class="text-muted m-0"><h6 class="font-weight-bold text-number-wt">{{ auth()->user()->name}} {{ auth()->user()->last_name }}</h6></p>
                                    </div>
                                </div>
                                <div class="dropdown-divider m-0"></div>
                                <div class="row mt-3">
                                    <div class="col-sm-3">
                                        <p class="mb-0"><h6 class="font-weight-bold">Email</h6></p>
                                    </div>
                                    <div class="col-sm-9">
                                        <p class="text-muted m-0"><h6 class="font-weight-bold text-number-wt">{{ auth()->user()->email}}</h6></p>
                                    </div>
                                </div>
                                <div class="dropdown-divider m-0"></div>
                                <div class="row mt-3">
                                    <div class="col-sm-3">
                                        <p class="mb-0"><h6 class="font-weight-bold">Empresa</h6></p>
                                    </div>
                                    <div class="col-sm-9">
                                        <p class="text-muted m-0"><h6 class="font-weight-bold text-number-wt">{{ $user->empresa }}</h6></p>
                                    </div>
                                </div>
                                <div class="dropdown-divider m-0"></div>
                                <div class="row mt-3">
                                    <div class="col-sm-3">
                                        <p class="mb-0"><h6 class="font-weight-bold">Filiais</h6></p>
                                    </div>
                                    <div class="col-sm-9">
                                        <p class="text-muted m-0"><h6 class="font-weight-bold text-number-wt">{{ auth()->user()->ids_filiais}}</h6></p>
                                    </div>
                                </div>
                                <div class="dropdown-divider m-0"></div>
                                <div class="row mt-3">
                                    <div class="col-sm-3">
                                        <p class="mb-0"><h6 class="font-weight-bold">Perfil</h6></p>
                                    </div>
                                    <div class="col-sm-9">
                                        <p class="text-muted m-0">
                                            <h6 class="font-weight-bold text-number-wt">
                                            @if(auth()->user()->administrator)
                                                Administrador,
                                            @endif
                                            @if(auth()->user()->master)
                                                Master,
                                            @endif
                                            @if(auth()->user()->financeiro)
                                                Financeiro,
                                            @endif
                                            </h6>
                                        </p>
                                    </div>
                                </div>
                                <div class="dropdown-divider m-0"></div>
                                <div class="row mt-3">
                                    <div class="col-sm-3">
                                        <p class="mb-0"><h6 class="font-weight-bold">Grupos</h6></p>
                                    </div>
                                    <div class="col-sm-9">
                                        <p class="text-muted m-0">
                                            <h6 class="font-weight-bold text-number-wt">
                                            @foreach(session('permissions') as $permission)
                                                {{ $permission->grupo_nome }},
                                            @endforeach
                                            </h6>
                                        </p>
                                    </div>
                                </div>
                                <div class="dropdown-divider m-0"></div>
                                <div class="row mt-3">
                                    <div class="col-sm-3">
                                        <p class="mb-0"><h6 class="font-weight-bold">Senha</h6></p>
                                    </div>
                                    <div class="col-sm-3">
                                        <p class="text-muted m-0">
                                            <h6 id="textSenha" class="font-weight-bold text-number-wt">******</h6>
                                            <input id="inputSenha" class="form-control" style="display: none;">
                                        </p>

                                    </div>
                                    <div class="col-sm-3">
                                        <p class="mb-0"><h6 class="font-weight-bold">Confirme</h6></p>
                                    </div>
                                    <div class="col-sm-3">
                                        <p class="text-muted m-0">
                                            <h6 id="textConfirm" class="font-weight-bold text-number-wt">******</h6>
                                            <input id="inputConfirm" class="form-control" style="display: none;">
                                        </p>
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer">
                                <div class="d-flex justify-content-end">
                                    <button id="editarProfile" class="btn-warning btn btn-sm mr-2">Editar</button>
                                    <button id="enviarAlteracoesUsuario" class="btn-success btn btn-sm">Enviar</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@include('usuarios.modal')
@endsection
