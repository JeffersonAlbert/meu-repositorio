<div class="row">
    <!-- Area Chart -->
        <div class="col-xl-12 col-lg-7">
            <div class="card shadow mb-4" >
                <!-- Card header - dropdow -->
                <div
                    class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">Lista processos</h6>
                    <div class="align-items-center">
                        <form class="form-inline" action="{{ route('fornecedor.index') }}" method="GET">
                            <div class="input-group">
                                <input type="text" name="termo" class="col-sm-13 form-control mr-1" placeholder="Pesquisar...">
                                <button class="btn btn-success btn-sm"><i class="bi bi-search"></i></button>
                            </div>
                        </form>
                    </div>
                    <div class="dropdown no-arrow">
                        <a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="fas fa-ellipsis-v fa-sm fa-fw text-gray-400"></i>
                        </a>
                        <div class="dropdown-menu dropdown-menu-right shadow animated--fade-in"
                            aria-labelledby="dropdownMenuLink">
                            <div class="dropdown-header">Dropdown Header:</div>
                            <a class="dropdown-item" href="{{ route("fornecedor.create") }}">Add Fornecedor</a>
                            <a class="dropdown-item" href="#">Another action</a>
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item" href="#">Something else here</a>
                        </div>
                    </div>
                </div>
                <!-- Card Body -->
                <div class="card-body" style="overflow-y: scroll;">
                    <div class="chart-area">
                        <table class="table table-responsive-sm">
                            <thead>
                                <th scope="col">#</th>
                                <th scope="col">Nome</th>
                                <th scope="col">Cpf/Cnpj</th>
                                <th scope="col">Tel</th>
                                <th scope="col">Ações</th>
                            </thead>
                            <tbody>
                            @forelse($fornecedores as $fornecedor)
                                <tr>
                                    <th scope="row">{{ $fornecedor->id }}</th>
                                    <td>{{ $fornecedor->nome }}</td>
                                    <td>{{ $fornecedor->cpf_cnpj }}</td>
                                    <td>{{ $fornecedor->telefone }}</td>
                                    <td>
                                        <a href="{{ route('fornecedor.edit', ['fornecedor' => $fornecedor->id]) }}" class="btn btn-warning btn-sm" role='button'>Editar</a>
                                    </td>
                                </tr>
                            @empty
                            @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="card-footer">
                    <a href="{{ url()->previous() }}" class="btn btn-secondary">Voltar</a>
                </div>
            </div>
        </div>
    </div>
