<div class="row">
    <div class="col-12">
        <div class="row">
            <div class="sidebar-heading">
                <div class="font-regular-wt font-heading-bar">{{ isset($fornecedor) ? 'Edição' : 'Cadastro' }}</div>
            </div>
        </div>
        <div class="row mt-2">
            <div class="col-12">
                <div class="font-regular-wt text-processo">Cadastro de fornecedor</div>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-12">
        @if(isset($fornecedor))
        <form method="POST" action="{{ route('fornecedor.update', ['fornecedor' => $fornecedor->id]) }}">
            {{ method_field('PUT') }}
        @else
        <form method="POST" action="{{ route('fornecedor.store') }}">
        @endif
            @csrf
            <div class="row cor-cinza-I">
                <div class="card h-100 shadow-number w-100">
                    <div class="card-body">
                        <div class="row cor-cinza-I">
                            <div class="font-regular-wt text-processo">Informações Principais</div>
                        </div>
                        <div class="row">
                            <div class="font-regular-wt font-heading-bar mt-3">
                                Os dados abaixo são muito importantes para o cadastro do seu processo de faturamento. Preencha-os com atenção.
                            </div>
                        </div>
                        <div class="row mt-3">
                            <div class="form-group col-3">
                                <label class="label-number" for="cpf_cnpj">CPF/CNPJ</label>
                                <input id="cnpj" name="cpf_cnpj" type="text" class="input-login form-control" placeholder="Cpf/CNPJ" value="{{ isset($fornecedor) ? $fornecedor->cpf_cnpj : null }}">
                            </div>
                            <div class="form-group col-3">
                                <label class="label-number" for="inscricao_estadual">Inscrição estadual</label>
                                <input id="inscricao_estadual" name="inscrica_estadual" type="text" class="input-login form-control" placeholder="I.E." value="{{ isset($fornecedor) ? $fornecedor->inscricao_estadual : null }}">
                            </div>
                            <div class="form-group col-3">
                                <label class="label-number" for="nome">Nome fantasia</label>
                                <input id="nome" name="nome" type="text" class="input-login form-control" placeholder="Nome fantasia" value="{{ isset($fornecedor) ? $fornecedor->nome : null }}">
                            </div>
                            <div class="form-group col-3">
                                <label class="label-number" for="razao_social">Razão social</label>
                                <input id="razao_social" name="razao_social" type="text" class="input-login form-control" placeholder="Razão social" value="{{ isset($fornecedor) ? $fornecedor->razao_social : null }}">
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-2">
                                <label class="label-number" for="cep">CEP</label>
                                <input id="cep" name="cep" type="text" class="input-login form-control" placeholder="CEP" value="{{ isset($fornecedor) ? $fornecedor->cep : null }}">
                            </div>
                            <div class="form-group col-3">
                                <label class="label-number" for="endereco">Endereço</label>
                                <input id="endereco" name="endereco" type="text" class="input-login form-control" placeholder="Endereço" value="{{ isset($fornecedor) ? $fornecedor->endereco : null }}">
                            </div>
                            <div class="form-group col-3">
                                <label class="label-number" for="cidade">Cidade</label>
                                <input id="cidade" name="cidade" type="text" class="input-login form-control" placeholder="Cidade" value="{{ isset($fornecedor) ? $fornecedor->cidade : null }}">
                            </div>
                            <div class="form-group col-1">
                                <label class="label-number" for="numero">Numero</label>
                                <input id="numero" name="numero" type="text" class="input-login form-control" placeholder="Numero" value="{{ isset($fornecedor) ? $fornecedor->numero : null }}">
                            </div>
                            <div class="form-group col-3">
                                <label class="label-number" for="complemento">Complemento</label>
                                <input id="complemento" name="complemento" type="text" class="input-login form-control" placeholder="Complemento" value="{{ isset($fornecedor) ? $fornecedor->complemento : null }}">
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-3">
                                <label class="label-number" for="bairro">Bairro</label>
                                <input id="bairro" name="bairro" type="text" class="input-login form-control" placeholder="Bairro" value="{{ isset($fornecedor) ? $fornecedor->bairro : null }}">
                            </div>
                            <div class="form-group col-3">
                                <label class="label-number" for="forma_pagamento">Dados pagamento</label>
                                <textarea id="forma_pagamento" name="forma_pagamento" class="input-login form-control">{{ isset($fornecedor) ? $fornecedor->forma_pagamento : null }}</textarea>
                            </div>
                            <div class="form-group col-3">
                                <label class="label-number" for="observacao">Observação</label>
                                <textarea id="observacao" name="observacao" class="input-login form-control">{{ isset($fornecedor) ? $fornecedor->observacao : null }}</textarea>
                            </div>
                        </div>
                        <div class="row">
                            @if(isset($fornecedor))
                                <button id="submissao" class="btn btn-success btn-submit mr-3">Alterar</button>
                                <a href="{{ url()->previous() }}" class="btn btn-secondary">Voltar</a>
                            @else
                                <button id="submissao" class="btn btn-success btn-submit mr-3">Inserir</button>
                                <a href="{{ url()->previous() }}" class="btn btn-secondary">Voltar</a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
