@extends('grupoprocesso.js')
@extends('layout.newLayout')
@section('content')
<div class=row>
    <!-- Area Chart -->
    <div class="col-xl-12 col-lg-7">
        <div class="card shadow mb-4" >
            <!-- Card Header - Dropdown -->
            <div
                class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                <h6 class="m-0 font-weight-bold text-primary">Lista grupo</h6>
                <div class="dropdown no-arrow">
                    <a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink"
                        data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="fas fa-ellipsis-v fa-sm fa-fw text-gray-400"></i>
                    </a>
                    <div class="dropdown-menu dropdown-menu-right shadow animated--fade-in"
                        aria-labelledby="dropdownMenuLink">
                        <div class="dropdown-header">Dropdown Header:</div>
                        <a class="dropdown-item" href="{{ route("grupoprocesso.create") }}">Add grupo</a>
                        <a class="dropdown-item" href="#">Another action</a>
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item" href="#">Something else here</a>
                    </div>
                </div>
            </div>
            <!-- Card Body -->
            <div class="card-body" style="overflow-y: scroll;">
                <div class="chart-area">
                    <table class="table table-responsive-xl">
                        <thead>
                            <th scope="col">#</th>
                            <th scope="col">Nome</th>
                            <th scope="col">Empresa</th>
                            <th scope="col">Ações</th>
                        </thead>
                        <tbody>
                        @forelse($grupos as $grupo)
                            <tr>
                                <th scope="row">{{ $grupo->id }}</th>
                                <td>{{ $grupo->nome }}</td>
                                <td>{{ !is_null($grupo->empresa) ? $grupo->empresa : $grupo->empresa_rs }}</td>
                                <td>
                                    <a href="{{ route('grupoprocesso.edit', ['grupoprocesso' => $grupo->id]) }}" class="btn btn-warning btn-sm" role='button'>Editar</a>
                                    <button class="btn btn-success btn-sm user-data" data-id="{{ $grupo->id }}">Ver usuários</button>
                                </td>
                            </tr>
                        @empty
                        <tr><td>Nada aqui ainda</td></tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            <!-- final do card-body -->
            <div class="card-footer">
                <a href="{{ url()->previous() }}" class="btn btn-success btn-back-number">Voltar</a>
            </div>
        </div>
    </div>
</div>
@include('grupoprocesso.modalDataUsuariosGrupos')
@endsection
