<div class="row w-100">
    <div class="col-12">
        <div class="row">
            <div class="sidebar-heading"#>
                <div class="font-regular-wt font-heading-bar">
                {{ isset($empresa) ? 'Edição:' : 'Cadastro:'}}
                </div>
            </div>
        </div>
        <div class="row mt-2">
            <div class="col-12">
                <div class="font-regular-wt text-processo">{{ $empresa->nome }}</div>
            </div>
        </div>
        <div class="row mt-2 mb-2">
            <div class="col-sm-12">
                <button class="btn btn-md btn-success btn-success-number"  data-toggle="modal" data-target="#modalFiliais">Filiais</button>
                <button class="btn btn-md btn-success btn-success-number"  data-toggle="modal" data-target="#modalWorkflow">Workflow</button>
                <button class="btn btn-md btn-success btn-success-number"  data-toggle="modal" data-target="#modalGrupos">Grupos</button>
                <button class="btn btn-md btn-success btn-success-number"  data-toggle="modal" data-target="#modalUsuarios">Usuarios</button>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-12">
        @if(isset($empresa))
            <form method="POST" action="{{ route('empresa.update', ['empresa' => $empresa->id]) }}">
            {{ method_field('PUT') }}
        @else
            <form method="POST" action="{{ route('empresa.store') }}">
        @endif
            @csrf
            <div class="row cor-cinza-I">
                <div class="card h-100 shadow-number w-100">
                    <div class="card-body" style="background: rgba(141, 148, 145, 0.10);">
                        <div class="row cor-cinza-I">
                            <div class="font-regular-wt text-processo">Dados da Matriz</div>
                        </div>
                        <div class="row">
                            <div class="font-regular-wt font-heading-bar mt-3">
                                Os dados abaixo são muito importantes. Preencha-os com atenção.
                            </div>
                        </div>
                        <div class="row mt-3">
                            <div class="form-group col-3">
                                <label class="label-number" for="cnpj">CNPJ:</label>
                                <input id="cnpj" name="cnpj" type="text" class="form-control input-login" placeholder="Cpf/Cnpj" value="{{ (isset($empresa)) ? $empresa->cpf_cnpj : null}}" disabled>
                            </div>
                            <div class="form-group col-3">
                                <label class="label-number" for="inscricao_estadual">I.E.:</label>
                                <input id="inscricao_estadual" type="text" class="form-control input-login" placeholder="I.E." value="{{ (isset($empresa)) ? $empresa->inscricao_estadual : null }}" disabled>
                            </div>
                            <div class="form-group col-6">
                                <label class="label-number" for="nome">Nome:</label>
                                <input name="nome" type="text" class="form-control input-login" placeholder="Nome Fantasia" value="{{ (isset($empresa)) ? $empresa->nome : null }}" disabled>
                            </div>
                        </div>
                        <div class="row mt-3">
                            <div class="form-group col-5">
                                <label class="label-number" for="razao_social">Razão Social:</label>
                                <input name="razao_social" type="text" class="form-control input-login" placeholder="Razão Social" value="{{ (isset($empresa)) ? $empresa->razao_social : null }}" disabled>
                            </div>
                            <div class="form-group col-3">
                                <label class="label-number" for="cep">CEP:</label>
                                <input name="cep" type="text" class="form-control input-login" placeholder="CEP" value="{{ (isset($empresa)) ? $empresa->cep : null }}" disabled>
                            </div>
                            <div class="form-group col-4">
                                <label class="label-number" for="endereco">Endereco:</label>
                                <input name="endereco" type="text" class="form-control input-login" placeholder="Endereco" value="{{ (isset($empresa)) ? $empresa->endereco : null }}" disabled>
                            </div>
                        </div>
                        <div class="row mt-3">
                            <div class="form-group col-3">
                                <label class="label-number" for="cidade">Cidade:</label>
                                <input name="cidade" type="text" class="form-control input-login" placeholder="Cidade" value="{{ (isset($empresa)) ? $empresa->cidade : null }}" disabled>
                            </div>
                            <div class="form-group col-md-1">
                                <label class="label-number" for="numero">Numero:</label>
                                <input name="numero" type="text" class="form-control input-login" placeholder="Num" value="{{ (isset($empresa)) ? $empresa->numero : null }}" disabled>
                            </div>
                            <div class="form-group col-md-4">
                                <label class="label-number" for="complemento">Complemento:</label>
                                <input name="complemento" type="text" class="form-control input-login" placeholder="Complemento" value="{{ (isset($empresa)) ? $empresa->complemento : null }}" disabled>
                            </div>
                            <div class="form-group col-md-4">
                                <label class="label-number" for="Bairro">Bairro:</label>
                                <input name="bairro" type="text" class="form-control input-login" placeholder="Bairro" value="{{ (isset($empresa)) ? $empresa->bairro : null }}" disabled>
                            </div>
                        </div>
                        <div class="row mt-3">
                            <div class="form-group col-6">
                                <label class="label-number" for="observacao">Observação:</label>
                                <textarea name="observacao" class="input-login form-control" rows="4">{{ isset($empresa) ? $empresa->observacao : null }}</textarea>
                            </div>
                        </div>
                        <div class="row mt-3">
                            <div class="col-2">
                                <button class="btn btn-md btn-success-number btn-success">{{ isset($filial) ? 'Editar' : 'Salvar' }} Matriz</button>
                            </div>
                            <div class="col-2">
                                <a href="#" class="btn btn-md btn-back-number btn-success">Voltar</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
