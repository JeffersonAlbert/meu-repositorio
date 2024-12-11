@extends('layout.layout')

@section('content')
<div class=row>
    <!-- Area Chart -->
    <div class="col-xl-12 col-lg-7">
        <div class="card shadow mb-4" style="overflow-y: scroll;">
            <!-- Card Header - Dropdown -->
            <div
                class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                <h6 class="m-0 font-weight-bold text-primary">Lista centro custo</h6>
                <div class="dropdown no-arrow">
                    <a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink"
                        data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="fas fa-ellipsis-v fa-sm fa-fw text-gray-400"></i>
                    </a>
                    <div class="dropdown-menu dropdown-menu-right shadow animated--fade-in"
                        aria-labelledby="dropdownMenuLink">
                        <div class="dropdown-header">Dropdown Header:</div>
                        <a class="dropdown-item" href="{{ route("centrocusto.create") }}">Add Centro Custo</a>
                        <a class="dropdown-item" href="#">Another action</a>
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item" href="#">Something else here</a>
                    </div>
                </div>
            </div>
            <!-- Card Body -->
            <div class="card-body">
                <div class="chart-area">
                    <table class="table table-responsive-sm">
                        <thead>
                            <th scope="col">#</th>
                            <th scope="col">Nome</th>
                            <th scope="col">Descricao</th>
                            <th scope="col">Ações</th>
                        </thead>
                        <tbody>
                        @forelse($ccs as $cc)
                            <tr>
                                <th scope="row">{{$cc->id}}</th>
                                <td>{{$cc->nome}}</td>
                                <td>{{$cc->descricao}}</td>
                                <td>
                                    <a href="{{ route('centrocusto.edit', ['centrocusto' => $cc->id]) }}" class="btn btn-warning btn-sm" role='button'>Editar</a>
                                </td>
                            </tr>
                        @empty
                        @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
