@extends('dre.js.js')
@extends('layout.newLayout')

@section('content')
<div class='row'>
    <button href='#' class="btn btn-sm btn-success btn-success-number" data-toggle="modal" data-target="#addDRE">
        <i class="bi bi-plus"></i>
        Adicionar receita
    </button>
</div>
<div class="row mt-3">
    <div class="col-12">
        <div class="row">
            <div class="titulo-grid-number font-regular-wt mb-3">
                Categoria de Receita
            </div>
        </div>
    </div>
    <div class="col-12">
        <div class="card h-100 shadown-number w-100">
            @forelse($receita as $categoria)
                <div class="row ml-2 mt-2">
                    <div class="col-9">
                        <div class="titulo-grid-number font-regular-wt">
                            {{ $categoria->nome }}<br>
                        </div>
                    </div>
                    @if($categoria->editable == true)
                    <div class="col-3">
                        <button class="btn btn-sm btn-danger">
                            <i class="bi bi-trash"></i>
                        </button>
                        <button class="btn btn-sm btn-success">
                            <i class="bi bi-pencil"></i>
                        </button>
                    </div>
                    @endif
                </div>
                <div class="divider-xl"></div>
                @forelse($subReceitas as $subReceita)
                @if($categoria->id == $subReceita->dre_id)
                <div class="row ml-3">
                    <div class="col-9">
                        <div class="sub_categoria row font-regular-wt">
                             - {{ $subReceita->sub_categoria_descricao }}
                        </div>
                        <div class="row font-regular-wt ml-3">
                            DRE: <b>{{ $subReceita->vinculo_descricao  }}</b>
                        </div>
                    </div>
                    @if($subReceita->editable == true)
                    <div class="col-3">
                        <button class="btn btn-sm btn-danger">
                            <i class="bi bi-trash"></i>
                        </button>
                        <button class="btn btn-sm btn-success editarReceita" data-id="{{ $subReceita->sub_receita_id }}">
                            <i class="bi bi-pencil"></i>
                        </button>
                    </div>
                    @endif
                </div>
                <div class="divider-xl"></div>
                @endif
                @empty
                NADA AINDA
                @endforelse
            @empty
                <div class="m-2 titulo-grid-number font-regular-wt">
                    Nada aqui ainda
                </div>
                <div class="divider-xl"></div>
            @endforelse
        </div>
    </div>
</div>
<div class='row mt-3'>
    <button href='#' class="btn btn-sm btn-success btn-success-number" data-toggle="modal" data-target="#addDREDespesa">
        <i class="bi bi-plus"></i>
        Adicionar Despesa
    </button>
</div>
<div class="row mt-3">
    <div class="col-12">
        <div class="row">
            <div class="titulo-grid-number font-regular-wt mb-3">
                Categoria de Despesa
            </div>
        </div>
    </div>
    <div class="col-12">
        <div class="card h-100 shadown-number w-100">
            @forelse($despesa as $categoria)
            <div class="row">
                <div class="col-9">
                    <div class="m-2 titulo-grid-number font-regular-wt">
                        {{ $categoria->nome }}
                    </div>
                </div>
                @if($categoria->editable == true)
                <div class="col-3">
                    <button class="btn btn-sm btn-danger">
                        <i class="bi bi-trash"></i>
                    </button>
                    <button class="btn btn-sm btn-success">
                        <i class="bi bi-pencil"></i>
                    </button>
                </div>
                @endif
            </div>
                <div class="divider-xl"></div>
                @forelse($subDespesas as $subDespesa)
                @if($categoria->id == $subDespesa->dre_id)
                <div class="row ml-3">
                    <div class="col-9">
                        <div class="sub_categoria font-regular-wt row">
                            - {{ $subDespesa->sub_categoria_descricao }}
                        </div>
                        <div class="font-regular-wt row">
                            DRE: <b>{{ $subDespesa->vinculo_descricao  }}</b>
                        </div>
                    </div>
                    @if($subDespesa->editable == true)
                    <div class="col-3">
                        <button class="btn btn-sm btn-danger">
                            <i class="bi bi-trash"></i>
                        </button>
                        <button class="btn btn-sm btn-success editarDespesa" data-id="{{ $subDespesa->sub_despesa_id }}">
                            <i class="bi bi-pencil"></i>
                        </button>
                    </div>
                    @endif
                </div>
                <div class="divider-xl"></div>
                @endif
            @empty
            Nada ainda
            @endforelse
            @empty
            <div class="m-2 titulo-grid-number font-regular-wt">
                Nada aqui ainda
            </div>
            <div class="divider-xl"></div>

            @endforelse
        </div>
    </div>
</div>
@endsection
@include('dre.modal.modalCreateDRE')
@include('dre.modal.modalCreateDreDespesa')
@include('dre.modal.modalEdit')

