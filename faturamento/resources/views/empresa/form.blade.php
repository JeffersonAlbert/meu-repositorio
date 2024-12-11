@extends('empresa.js')
@extends('layout.newLayout')

@section('content')
@include('empresa.newForm')
<div class="row">
    <!-- Area chart -->
    <div class="col-xl-12 col-lg-7">
        <div class="card shadow mb-4" >
        <!-- Card Header - Dropdown -->
            <div
                class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                <h6 class="m-0 font-weight-bold text-primary">Cadastro empresa</h6>
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
                @if(isset($empresa))
                    <form method="POST" action="{{ route('empresa.update', ['empresa' => $empresa->id]) }}">
                    {{ method_field('PUT') }}
                @else
                    <form method="POST" action="{{ route('empresa.store') }}">
                @endif
                    @csrf
                        <div class="form-row">
                            <div class="form-group col-md-3">
                                <input id="cnpj" name="cpf_cnpj" type="text" class="form-control" placeholder="Cpf/Cnpj" value="{{ (isset($empresa)) ? $empresa->cpf_cnpj : null}}">
                            </div>
                            <div class="form-group col-md-3">
                                <input id="inscricao_estadual" type="text" class="form-control" placeholder="I.E." value="{{ (isset($empresa)) ? $empresa->inscricao_estadual : null }}">
                            </div>
                            <div class="form-group col-md-6">
                                <input name="nome" type="text" class="form-control" placeholder="Nome Fantasia" value="{{ (isset($empresa)) ? $empresa->nome : null }}">
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-5">
                                <input name="razao_social" type="text" class="form-control" placeholder="Razão Social" value="{{ (isset($empresa)) ? $empresa->razao_social : null }}">
                            </div>
                            <div class="form-group col-md-3">
                                <input name="cep" type="text" class="form-control" placeholder="CEP" value="{{ (isset($empresa)) ? $empresa->cep : null }}">
                            </div>
                            <div class="form-group col-md-4">
                                <input name="endereco" type="text" class="form-control" placeholder="Endereco" value="{{ (isset($empresa)) ? $empresa->endereco : null }}">
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-3">
                                <input name="cidade" type="text" class="form-control" placeholder="Cidade" value="{{ (isset($empresa)) ? $empresa->cidade : null }}">
                            </div>
                            <div class="form-group col-md-1">
                                <input name="numero" type="text" class="form-control" placeholder="Num" value="{{ (isset($empresa)) ? $empresa->numero : null }}">
                            </div>
                            <div class="form-group col-md-4">
                                <input name="complemento" type="text" class="form-control" placeholder="Complemento" value="{{ (isset($empresa)) ? $empresa->complemento : null }}">
                            </div>
                            <div class="form-group col-md-4">
                                <input name="bairro" type="text" class="form-control" placeholder="Bairro" value="{{ (isset($empresa)) ? $empresa->bairro : null }}">
                            </div>

                        </div>
                        <div class="mb-4">
                        @if(isset($empresa))
                        <!-- Criar uma div com class row -->
                        <div class="row">
                            <button class="btn btn-warning btn-submit">Alterar</button>
                            <a href="{{ url()->previous() }}" class="btn btn-secondary">Voltar</a>
                        </div>
                        @else
                            <button class="btn btn-success btn-submit">Inserir</button>
                            <a href="{{ url()->previous() }}" class="btn btn-secondary">Voltar</a>
                        @endif
                        </div>
                    </form>
                </div>
            </div>
            <!-- fim do body do card -->
            <div class="card-footer">

            </div>
        </div>
    </div>
</div>
@endsection
