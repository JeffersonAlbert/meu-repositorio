<div class=row>
    <!-- Area Chart -->
    <div class="col-xl-12 col-lg-7">
        <div class="card shadow mb-4" style="overflow-y: scroll;">
            <!-- Card Header - Dropdown -->
            <div
                class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                <h6 class="m-0 font-weight-bold text-primary">Dados matriz</h6>
                <div class="dropdown no-arrow">
                    <a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink"
                        data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="fas fa-ellipsis-v fa-sm fa-fw text-gray-400"></i>
                    </a>
                    <div class="dropdown-menu dropdown-menu-right shadow animated--fade-in"
                        aria-labelledby="dropdownMenuLink">
                        <div class="dropdown-header">Dropdown Header:</div>
                        <a class="dropdown-item" href="{{ route('empresa.create') }}">Add empresa</a>
                        <a class="dropdown-item" href="{{ route('filial-create', ['filial' => $empresa->id]) }}">Add filial</a>
                        <a class="dropdown-item" href="{{ route('departamento-create', ['departamento' => $empresa->id ])}}">Add departamento</a>
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item" href="#">Something else here</a>
                    </div>
                </div>
            </div>
            <!-- Card Body -->
            <div class="card-body">
                <div class="chart-area">
                    <form>
                        <div class="form-row">
                            <div class="form-group col-md-3">
                                <input id="cnpj" disabled name="cpf_cnpj" type="text" class="form-control" placeholder="Cpf/Cnpj" value="{{ (isset($empresa)) ? $empresa->cpf_cnpj : null}}">
                            </div>
                            <div class="form-group col-md-3">
                                <input disabled name="inscricao_estadual" id="inscricao_estadual" type="text" class="form-control" placeholder="I.E." value="{{ (isset($empresa)) ? $empresa->inscricao_estadual : null }}">
                            </div>
                            <div class="form-group col-md-6">
                                <input disabled name="nome" type="text" class="form-control" placeholder="Nome Fantasia" value="{{ (isset($empresa)) ? $empresa->nome : null }}">
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-5">
                                <input disabled name="razao_social" type="text" class="form-control" placeholder="Razão Social" value="{{ (isset($empresa)) ? $empresa->razao_social : null }}">
                            </div>
                            <div class="form-group col-md-3">
                                <input disabled name="cep" type="text" class="form-control" placeholder="CEP" value="{{ (isset($empresa)) ? $empresa->cep : null }}">
                            </div>
                            <div class="form-group col-md-4">
                                <input disabled name="endereco" type="text" class="form-control" placeholder="Endereco" value="{{ (isset($empresa)) ? $empresa->endereco : null }}">
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-3">
                                <input disabled name="cidade" type="text" class="form-control" placeholder="Cidade" value="{{ (isset($empresa)) ? $empresa->cidade : null }}">
                            </div>
                            <div class="form-group col-md-1">
                                <input disabled name="numero" type="text" class="form-control" placeholder="Num" value="{{ (isset($empresa)) ? $empresa->numero : null }}">
                            </div>
                            <div class="form-group col-md-4">
                                <input disabled name="complemento" type="text" class="form-control" placeholder="Complemento" value="{{ (isset($empresa)) ? $empresa->complemento : null }}">
                            </div>
                            <div class="form-group col-md-4">
                                <input disabled name="bairro" type="text" class="form-control" placeholder="Bairro" value="{{ (isset($empresa)) ? $empresa->bairro : null }}">
                            </div>
                        </div>
                    </form>
                    <div class="row">
                        <div class="col-sm-12">
                            <button class="btn btn-md btn-success"  data-toggle="modal" data-target="#modalFiliais">Filiais</button>
                            <button class="btn btn-md btn-success"  data-toggle="modal" data-target="#modalWorkflow">Workflow</button>
                            <button class="btn btn-md btn-success"  data-toggle="modal" data-target="#modalGrupos">Grupos</button>
                            <button class="btn btn-md btn-success"  data-toggle="modal" data-target="#modalUsuarios">Usuarios</button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-footer">
                <a href="{{ url()->previous() }}" class="btn btn-secondary">Voltar</a>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-xl-12 col-lg-7">
    </div>
</div>
<div class="row">
    <div class="col-xl-12 col-lg-7">
        <div class="card shadow mb-4" style="overflow-y: scroll;">
            <!-- Card Header - Dropdown -->
            <div
                class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                <h6 class="m-0 font-weight-bold text-primary">Lista Departamento</h6>
                <div class="dropdown no-arrow">
                    <a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink"
                        data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="fas fa-ellipsis-v fa-sm fa-fw text-gray-400"></i>
                    </a>
                    <div class="dropdown-menu dropdown-menu-right shadow animated--fade-in"
                        aria-labelledby="dropdownMenuLink">
                        <div class="dropdown-header">Dropdown Header:</div>
                        <a class="dropdown-item" href="#">Alguma coisa</a>
                        <a class="dropdown-item" href="">Alguma outra coisa</a>
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item" href="#">Something else here</a>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="chart-area">
                    <table class="table table-responsive-xl">
                        <thead>
                            <th scope="col">#</th>
                            <th scope="col">Nome</th>
                            <th scope="col">Filiais</th>
                            <th scope="col">Ações</th>
                        </thead>
                        <tbody>
                        @foreach($departamentos as $departamento)
                            <tr>
                                <th scope="row">{{$departamento->id}}</th>
                                <td>{{$departamento->nome}}</td>
                                <td>{{$departamento->id_filiais}}</td>
                                <td>
                                    <a href="{{ route('departamento.edit', ['departamento' => $departamento->id]) }}" class="btn btn-warning btn-sm" role='button'>Editar</a>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
