<div class="row">
    <!-- Area chart -->
    <div class="col-xl-12 col-lg-7">
        <div class="card shadow mb-4" style="overflow-y: scroll;">
        <!-- Card Header - Dropdown -->
            <div
                class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                <h6 class="m-0 font-weight-bold text-primary">Cadastro processo faturamento</h6>
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
            <div class="card-body">
                <div class="chart-area">
                @if(isset($fornecedor))
                    <form method="POST" action="{{ route('fornecedor.update', ['fornecedor' => $fornecedor->id]) }}">
                    {{ method_field('PUT') }}
                @else
                    <form method="POST" action="{{ route('fornecedor.store') }}">
                @endif
                    @csrf
                        <input type="hidden" value="{{ auth()->user()->id_empresa }}" name="id_empresa">
                        <!-- <div class="form-row">
                            <div class="form-group col-md-6">
                                <input name="nome" type="text" class="form-control" placeholder="Nome" value="{{ (isset($fornecedor)) ? $fornecedor->nome : null}}">
                            </div>
                            <div class="form-group col-md-6">
                                <input name="cpf_cnpj" type="text" class="form-control" placeholder="Cnpj" value="{{ (isset($fornecedor)) ? $fornecedor->cpf_cnpj : null }}">
                            </div>
                        </div> -->
                        <!-- formulario novo aqui -->
                        <div class="form-row">
                            <div class="form-group col-md-3">
                                <input id="cnpj" name="cpf_cnpj" type="text" class="input-login form-control" placeholder="Cpf/Cnpj" value="{{ (isset($fornecedor)) ? $fornecedor->cpf_cnpj : null}}">
                            </div>
                            <div class="form-group col-md-3">
                                <input id="inscricao_estadual" type="text" class="input-login form-control" placeholder="I.E." value="{{ (isset($fornecedor)) ? $fornecedor->inscricao_estadual : null }}">
                            </div>
                            <div class="form-group col-md-6">
                                <input name="nome" type="text" class="input-login form-control" placeholder="Nome Fantasia" value="{{ (isset($fornecedor)) ? $fornecedor->nome : null }}">
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-5">
                                <input name="razao_social" type="text" class="input-login form-control" placeholder="Razão Social" value="{{ (isset($fornecedor)) ? $fornecedor->razao_social : null }}">
                            </div>
                            <div class="form-group col-md-3">
                                <input name="cep" type="text" class="input-login form-control" placeholder="CEP" value="{{ (isset($fornecedor)) ? $fornecedor->cep : null }}">
                            </div>
                            <div class="form-group col-md-4">
                                <input name="endereco" type="text" class="input-login form-control" placeholder="Endereco" value="{{ (isset($fornecedor)) ? $fornecedor->endereco : null }}">
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-3">
                                <input name="cidade" type="text" class="input-login form-control" placeholder="Cidade" value="{{ (isset($fornecedor)) ? $fornecedor->cidade : null }}">
                            </div>
                            <div class="form-group col-md-1">
                                <input name="numero" type="text" class="input-login form-control" placeholder="Num" value="{{ (isset($fornecedor)) ? $fornecedor->numero : null }}">
                            </div>
                            <div class="form-group col-md-4">
                                <input name="complemento" type="text" class="input-login form-control" placeholder="Complemento" value="{{ (isset($fornecedor)) ? $fornecedor->complemento : null }}">
                            </div>
                            <div class="form-group col-md-4">
                                <input name="bairro" type="text" class="input-login form-control" placeholder="Bairro" value="{{ (isset($fornecedor)) ? $fornecedor->bairro : null }}">
                            </div>

                        </div>

                        <div class="form-row">
                        </div>
                        <div class="mb-3">
                        @if(isset($fornecedor))
                            <button id="submissao" class="btn btn-warning btn-submit mr-3">Alterar</button>
                            <a href="{{ url()->previous() }}" class="btn btn-secondary">Voltar</a>
                        @else
                            <button id="submissao" class="btn btn-success btn-submit mr-3">Inserir</button>
                            <a href="{{ url()->previous() }}" class="btn btn-secondary">Voltar</a>
                        @endif
                        </div>
                    </form>
                </div>
            </div>
            <!-- fim do body do card -->
        </div>
    </div>
</div>
