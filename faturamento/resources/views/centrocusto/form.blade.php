@extends('layout.layout')

@section('content')
<div class="row">
    <!-- Area chart -->
    <div class="col-xl-12 col-lg-7">
        <div class="card shadow mb-4">
        <!-- Card Header - Dropdown -->
            <div
                class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                <h6 class="m-0 font-weight-bold text-primary">Cadastro Centro Custo</h6>
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
                @if(isset($cc))
                     <form method="POST" action="{{ route('centrocusto.update', ['centrocusto' => $cc->id]) }}">
                        <input name="_method" type='hidden' value="PUT">
                @else
                    <form method="POST" action="{{ route('centrocusto.store') }}">
                @endif
                    @csrf
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <input name="nome" type="text" class="form-control" placeholder="Nome" value="{{ (isset($cc)) ? $cc->nome : null }}">
                            </div>
                            <div class="form-group col-md-6">
                                <input name="descricao" type="text" class="form-control" placeholder="Breve descricao" value="{{ (isset($cc)) ? $cc->descricao : null }}">
                            </div>
                        </div>
                        <div class="mb-3">
                            <button class="btn btn-success btn-submit">Submit</button>
                        </div>
                    </form>
                </div>
            </div>
            <!-- fim do body do card -->
        </div>
    </div>
</div>
@endsection
