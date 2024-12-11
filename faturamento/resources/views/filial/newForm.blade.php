<div class="row w-100">
    <div class="col-12">
        <div class="row">
            <div class="sidebar-heading"#>
                <div class="font-regular-wt font-heading-bar">
                {{ isset($filial) ? 'Edição:' : 'Cadastro:'}}
                </div>
            </div>
        </div>
        <div class="row mt-2 mb-3">
            <div class="col-12">
                <div class="font-regular-wt text-processo">{{ $filial->nome }}</div>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-12">
        @if(isset($filial))
            <form method="POST" action="{{ route('filial.update', ['filial' => $filial->id]) }}">
            {{ method_field('PUT') }}
        @else
            <form method="POST" action="{{ route('filial.store') }}">
        @endif
            @csrf
            <div class="row cor-cinza-I">
                <div class="card h-100 shadow-number w-100">
                    <div class="card-body" style="background: rgba(141, 148, 145, 0.10);">
                        <div class="row cor-cinza-I">
                            <div class="font-regular-wt text-processo">Dados das Filiais</div>
                        </div>
                        <div class="row">
                            <div class="font-regular-wt font-heading-bar mt-3">
                                Os dados abaixo são muito importantes. Preencha-os com atenção.
                            </div>
                        </div>
                        <div class="row mt-3">
                            <input type="hidden" name="id_empresa" value="{{ isset($filial) ? $filial->id_empresa : $id }}">
                            <div class="form-group col-3">
                                <label class="label-number" for="cnpj">CNPJ:</label>
                                <input id="cnpj" name="cnpj" type="text" class="form-control input-login" placeholder="Cpf/Cnpj" value="{{ (isset($filial)) ? $filial->cnpj : null}}">
                            </div>
                            <div class="form-group col-3">
                                <label class="label-number" for="inscricao_estadual">I.E.:</label>
                                <input id="inscricao_estadual" type="text" class="form-control input-login" placeholder="I.E." value="{{ (isset($filial)) ? $filial->inscricao_estadual : null }}">
                            </div>
                            <div class="form-group col-6">
                                <label class="label-number" for="nome">Nome:</label>
                                <input name="nome" type="text" class="form-control input-login" placeholder="Nome Fantasia" value="{{ (isset($filial)) ? $filial->nome : null }}">
                            </div>
                        </div>
                        <div class="row mt-3">
                            <div class="form-group col-5">
                                <label class="label-number" for="razao_social">Razão Social:</label>
                                <input name="razao_social" type="text" class="form-control input-login" placeholder="Razão Social" value="{{ (isset($filial)) ? $filial->razao_social : null }}">
                            </div>
                            <div class="form-group col-3">
                                <label class="label-number" for="cep">CEP:</label>
                                <input name="cep" type="text" class="form-control input-login" placeholder="CEP" value="{{ (isset($filial)) ? $filial->cep : null }}">
                            </div>
                            <div class="form-group col-4">
                                <label class="label-number" for="endereco">Endereco:</label>
                                <input name="endereco" type="text" class="form-control input-login" placeholder="Endereco" value="{{ (isset($filial)) ? $filial->endereco : null }}">
                            </div>
                        </div>
                        <div class="row mt-3">
                            <div class="form-group col-3">
                                <label class="label-number" for="cidade">Cidade:</label>
                                <input name="cidade" type="text" class="form-control input-login" placeholder="Cidade" value="{{ (isset($filial)) ? $filial->cidade : null }}">
                            </div>
                            <div class="form-group col-md-1">
                                <label class="label-number" for="numero">Numero:</label>
                                <input name="numero" type="text" class="form-control input-login" placeholder="Num" value="{{ (isset($filial)) ? $filial->numero : null }}">
                            </div>
                            <div class="form-group col-md-4">
                                <label class="label-number" for="complemento">Complemento:</label>
                                <input name="complemento" type="text" class="form-control input-login" placeholder="Complemento" value="{{ (isset($filial)) ? $filial->complemento : null }}">
                            </div>
                            <div class="form-group col-md-4">
                                <label class="label-number" for="Bairro">Bairro:</label>
                                <input name="bairro" type="text" class="form-control input-login" placeholder="Bairro" value="{{ (isset($filial)) ? $filial->bairro : null }}">
                            </div>
                        </div>
                        <div class="row mt-3">
                            <div class="form-group col-6">
                                <label class="label-number" for="observacao">Observação:</label>
                                <textarea name="observacao" class="input-login form-control" rows="4">{{ isset($filial) ? $filial->observacao : null }}</textarea>
                            </div>
                        </div>
                        <div class="row mt-3">
                            <div class="col-2">
                                <button class="btn btn-md btn-success-number btn-success">{{ isset($filial) ? 'Editar' : 'Salvar' }} Filial</button>
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
