<div class="row">
    <div class="form-user-error" style="display: none"></div>
    <div class="col-12">
        <div class="row">
            <div class="sidebar-heading">
                <div class="font-regular-wt font-heading-bar">
                    {{ isset($processo) ? 'Edição:' : 'Cadastro:' }}
                </div>
            </div>
        </div>
        <div class="row mt-2">
            <div class="col-12">
                <div class="font-regular-wt text-processo">Usuário</div>
            </div>
        </div>
        @if (isset($user))
            <div class="row mt-2 mb-3">
                <div class="col-12">
                    <div class="font-regular-wt">{{ $user->name }} {{ $user->last_name }}</div>
                </div>
            </div>
        @endif
    </div>
</div>
<div class="row">
    <div class="col-12">
        @if (isset($user))
            <form id="usuarioForm" method="POST" action="{{ route('usuarios.update', ['usuario' => $user->id]) }}">
                {{ method_field('PUT') }}
            @else
                <form id="usuarioForm" method="POST" action="{{ route('usuarios.store') }}">
        @endif
        @csrf
        <div class="row cor-cinza-I">
            <div class="card h-100 shadow-number w-100">
                <div class="card-body" style="background: rgba(141, 148, 145, 0.10);">
                    <div class="row cor-cinza-I">
                        <div class="font-regular-wt text-processo">Informações Principais</div>
                    </div>
                    <div class="row">
                        <div class="font-regular-wt font-heading-bar mt-3">
                            Os dados abaixo são muito importantes para o cadastro do seu processo de faturamento.
                            Preencha-os com atenção.
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-6">
                            <label class="label-number" for="name"
                                style="font-size: 14px; white-space: nowrap;">Nome:</label>
                            <input name="name" type="text" class="input-login form-control" placeholder="Nome"
                                value="{{ isset($user) ? $user->name : null }}">
                        </div>
                        <div class="col-6">
                            <label class="label-number" for="last_name"
                                style="font-size: 14px; white-space: nowrap;">Sobrenome:</label>
                            <input name="last_name" type="text" class="input-login form-control"
                                placeholder="Sobrenome" value="{{ isset($user) ? $user->last_name : null }}">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-4">
                            <label class="label-number" for="email"
                                style="font-size: 14px; white-space: nowrap;">Email:</label>
                            <input name="email" type="email" class="input-login form-control" placeholder="Email"
                                value="{{ isset($user) ? $user->email : null }}">
                        </div>
                        <div class="col-4">
                            <label class="label-number" for="password">Password:</label>
                            <div class="input-group">
                                <input id="password" name="password" type="password" class="form-control input-login"
                                    placeholder="Senha">
                                <div class="input-group-append">
                                    <span class="input-group-text" id="olho">
                                        <i class="bi bi-eye"></i>
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="col-4">
                            <label class="label-number" for="confirm-password">Confirme password:</label>
                            <div class="input-group">
                                <input id="confirm-password" name="confirm-password" type="password"
                                    class="form-control input-login" placeholder="Senha">
                                <div class="input-group-append">
                                    <span class="input-group-text" id="olho-confirm">
                                        <i class="bi bi-eye"></i>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row mt-3">
                        @if (auth()->user()->administrator == true || auth()->user()->master == true)
                            <div class="form-group col-3">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text">
                                            <input
                                                {{ isset($user->administrator) && $user->administrator ? 'checked' : null }}
                                                type="checkbox" aria-label=".administrator" name="administrator"
                                                value="1">
                                        </div>
                                        <input type="text" class="form-control administrator input-login"
                                            value="Administrador do sistema" disabled>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group col-3">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text">
                                            <input
                                                {{ isset($user->financeiro) && $user->financeiro ? 'checked' : null }}
                                                type="checkbox" aria-label=".financeiro" name="financeiro"
                                                value="1">
                                        </div>
                                        <input type="text" class="form-control financeiro input-login"
                                            value="Faz pagamentos" disabled>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group col-3">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text">
                                            <input
                                                {{ isset($user->receber_contas) && $user->receber_contas ? 'checked' : null }}
                                                type="checkbox" aria-label=".receber" name="receber" value="1">
                                        </div>
                                        <input type="text" class="form-control receber input-login"
                                            value="Movimenta recebiveis" disabled>
                                    </div>
                                </div>
                            </div>
                        @endif
                        @if (isset(auth()->user()->master) && auth()->user()->master == true)
                            <div class="form-group col-md-4">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text">
                                            <input
                                                {{ isset($user->master) && $user->master == true ? 'checked' : null }}
                                                type="checkbox" aria-label=".master" name="master" value="1">
                                        </div>
                                        <input type="text" class="form-control master" value="Master do sistema"
                                            disabled>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                    <div class="row mt-3">
                        <div class="form-group col-md-12">
                            @include('usuarios.components.grupos')
                        </div>
                        {{-- <div class="form-group col-md-12">
                            <div id="grupos_hidden"></div>
                            <div for="" class="grupo-input-number">
                                <label for="grupos-select" class="form-label label-number">Grupos do usuario</label>
                                <div class="row ml-3">
                                    <input disabled type="text" id="grupo-selecionado" class="form-control"
                                        data-role="tagsinput">
                                </div>
                                <div class="row mt-1 ml-3 mr-3">
                                    <input type="text" id="grupos-select" data-provide="typeahead"
                                        class="form-control input-login" value="">
                                </div>
                            </div>
                        </div> --}}
                    </div>
                    <div class="row mt-3">
                        <div class="form-group col-md-12">
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="form-group col-md-12">
                            <label for="id_empresa" class="form-label">Empresas:</label>
                            @if (isset($user->id_empresa) and !auth()->user()->master)
                                <input hidden value="{{ $user->id_empresa }}" name="id_empresa">
                                <input disabled type="text" class="form-control"
                                    value="{{ $user->empresa_name }}">
                            @elseif(!auth()->user()->master)
                                <input hidden value="{{ $empresas->id }}" name="id_empresa">
                                <input type="text" value="{{ $empresas->nome }}" class="form-control" disabled>
                            @elseif(auth()->user()->master == true)
                                <select name="id_empresa" class="form-control form-select" aria-label=".form-select">
                                    @if (isset($empresas))
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
                    <div class="row mt-3">
                        @forelse($filiais as $filial)
                            <div class="form-group col-md-3">
                                <label for="id_empresa" class="form-label">Filial:</label>
                                <div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text">
                                            <input type="checkbox" aria-label=".filialCheked" name="filiais[]"
                                                value="{{ $filial->id }}"
                                                {{ isset($user) && App\Helpers\Utils::findFilialIdInArraySession(session()->get('filiais'), $filial->id) ? 'checked' : null }}>
                                        </div>
                                    </div>
                                    <input type="text" class="form-control .filialChecked"
                                        value="{{ $filial->razao_social }}" disabled>
                                </div>
                            </div>
                        @empty
                        @endforelse
                    </div>
                    <div class="row mt-3">
                        @forelse($ccsChecked as $cc)
                            <div class="form-group col-md-3">
                                <div class="input-group  mb-3">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text">
                                            <input checked type="checkbox" aria-label=".ccsChecked"
                                                name="{{ $cc->nome }}" value="{{ $cc->id }}">
                                        </div>
                                    </div>
                                    <input type="text" class="form-control .ccsChecked"
                                        value="{{ $cc->nome }}" disabled>
                                </div>
                            </div>
                        @empty
                        @endforelse
                        @forelse($ccs as $cc)
                            <div class="form-group col-md-3">
                                <div class="input-group  mb-3">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text">
                                            <input type="checkbox" aria-label=".ccs" name="{{ $cc->nome }}"
                                                value="{{ $cc->id }}">
                                        </div>
                                    </div>
                                    <input type="text" class="form-control .ccs" value="{{ $cc->nome }}"
                                        disabled>
                                </div>
                            </div>
                        @empty
                        @endforelse
                    </div>
                    <div class="row mt-3">
                        <div class="col-1">
                            <button id="inserirUsuario"
                                class="btn btn-success btn-submit">{{ isset($user) ? 'Alterar' : 'Inserir' }}</button>
                        </div>
                        <div class="col-1">
                            <a href="{{ url()->previous() }}" class="btn btn-success">Volttar</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        </form>
    </div>
</div>
