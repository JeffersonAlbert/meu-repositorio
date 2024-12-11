<div class="row">
    <!-- Area chart -->
    <div class="col-xl-12 col-lg-7">
        <div class="card shadow mb-4" style="overflow-y: scroll;">
        <!-- Card Header - Dropdown -->
            <div
                class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                <h6 class="m-0 font-weight-bold text-primary">Cadastro filial</h6>
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
                @if(isset($filial))
                    <form method="POST" action="{{ route('filial.update', ['filial' => $filial->id]) }}">
                    {{ method_field('PUT') }}
                @else
                    <form method="POST" action="{{ route('filial.store') }}">
                @endif
                    @csrf
                        <input type="hidden" name="id_empresa" value="{{ isset($filial) ? $filial->id_empresa : $id }}">
                        <div class="form-row">
                            <div class="form-group col-md-3">
                                <input id="cnpj" name="cnpj" type="text" class="form-control" placeholder="Cpf/Cnpj" value="{{ (isset($filial)) ? $filial->cnpj : null}}">
                            </div>
                            <div class="form-group col-md-3">
                                <input id="inscricao_estadual" type="text" class="form-control" placeholder="I.E." value="{{ (isset($filial)) ? $filial->inscricao_estadual : null }}">
                            </div>
                            <div class="form-group col-md-6">
                                <input name="nome" type="text" class="form-control" placeholder="Nome Fantasia" value="{{ (isset($filial)) ? $filial->nome : null }}">
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-5">
                                <input name="razao_social" type="text" class="form-control" placeholder="Razão Social" value="{{ (isset($filial)) ? $filial->razao_social : null }}">
                            </div>
                            <div class="form-group col-md-3">
                                <input name="cep" type="text" class="form-control" placeholder="CEP" value="{{ (isset($filial)) ? $filial->cep : null }}">
                            </div>
                            <div class="form-group col-md-4">
                                <input name="endereco" type="text" class="form-control" placeholder="Endereco" value="{{ (isset($filial)) ? $filial->endereco : null }}">
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-3">
                                <input name="cidade" type="text" class="form-control" placeholder="Cidade" value="{{ (isset($filial)) ? $filial->cidade : null }}">
                            </div>
                            <div class="form-group col-md-1">
                                <input name="numero" type="text" class="form-control" placeholder="Num" value="{{ (isset($filial)) ? $filial->numero : null }}">
                            </div>
                            <div class="form-group col-md-4">
                                <input name="complemento" type="text" class="form-control" placeholder="Complemento" value="{{ (isset($filial)) ? $filial->complemento : null }}">
                            </div>
                            <div class="form-group col-md-4">
                                <input name="bairro" type="text" class="form-control" placeholder="Bairro" value="{{ (isset($filial)) ? $filial->bairro : null }}">
                            </div>

                        </div>
                        <div class="mb-4">
                        @if(isset($filial))
                            <button disabled id="btn-submit" class="btn btn-warning btn-submit">Alterar</button>
                            <a href="{{ url()->previous() }}" class="btn btn-secondary">Voltar</a>
                        @else
                            <button disabled id="btn-submit" class="btn btn-success btn-submit">Inserir</button>
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
