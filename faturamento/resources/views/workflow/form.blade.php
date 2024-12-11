@extends('workflow.js')
@extends('workflow.components.grupos-js')
@extends('layout.newLayout')

@section('content')
    <div class="row">
        <!-- Area chart -->
        <div class="col-xl-12 col-lg-7">
            <div class="form-workflow-error"></div>
            @if (count($grupos) == 0)
                <div class="alert alert-danger">
                    <p>Antes de criar o workflow precisa criar os grupos que irão participar do Workflow. <a
                            href="{{ route('grupoprocesso.create') }}">Clique aqui!</a></p>
                </div>
            @endif
            <div class="card shadow mb-4">
                <!-- Card Header - Dropdown -->
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">Criar Workflow</h6>
                    <div class="dropdown no-arrow">
                        <a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown"
                            aria-haspopup="true" aria-expanded="false">
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
                        @if (isset($workflow))
                            <form id="workflowForm" method="POST"
                                action="{{ route('workflow.update', ['workflow' => $workflow->id]) }}">
                                {{ method_field('PUT') }}
                            @else
                                <form id="workflowForm" method="POST" action="{{ route('workflow.store') }}">
                        @endif
                        @csrf
                        <div class="form-row">
                            <label for="nome" class="label-number required">Nome do grupo</label>
                            <div class="form-group col-md-12">
                                <input type="text" name="nome" class="input-login form-control"
                                    value="{{ isset($workflow) ? $workflow->nome : null }}" placeholder="Setar o nome aqui">
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-12">
                                @include('workflow.components.grupos')
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Fim card body -->
                <!-- card footer -->
                <div class="card-footer" style="overflow-y: scroll;">
                    <div class="mb-3">
                        @if (isset($workflow))
                            <button id="inserirWorkflow" class="btn btn-warning btn-submit">Alterar</button>
                            <a href="{{ url()->previous() }}" class="btn btn-secondary">Voltar</a>
                        @else
                            <button id="inserirWorkflow" class="btn btn-success btn-submit">Inserir</button>
                            <a href="{{ url()->previous() }}" class="btn btn-secondary">Voltar</a>
                        @endif
                    </div>
                    </form>
                </div>
                <!-- fim card footer-->
            </div>
        </div>
    </div>
@endsection
