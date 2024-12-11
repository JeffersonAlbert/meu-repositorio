@extends('layout.newLayout')

@section('content')
<div class="row">
    <div class="col-4">
        <div class="card w-100 bck-card" style="padding-top: 20px; padding-bottom: 20px;">
            <div class="card-body">
                <img class="" src="{{ asset('/img/static/icons/compras.svg') }}" style="width: 30%; display: block;">
                    <span class="mt-3 ml-2 mb-0 font-weight-bold text-processo-lateral tx-card-1" style="display: block; text-align: left;">Contas a pagar</span>
                    <br><br>
                    <span class="mt-3 ml-2 mb-0 font-regular-wt text-processo-lateral tx-card-2" style="display: block; font-size: 14px; text-align: left;">Cadastre suas contas para solicitação de pagamento</span>
                       <a class="mt-3 nav-link text-right" href="{{ route('financeiro.controle') }}">
                            <span class="acesso-link">Acesse aqui!</span>
                       </a>
            </div>
        </div>
    </div>
     <div class="col-4">
        <div class="card w-100 bck-card" style="padding-top: 20px; padding-bottom: 20px;">
            <div class="card-body">
                <img class="" src="{{ asset('/img/static/icons/vendas.svg') }}" style="width: 30%; display: block;">
                    <span class="mt-3 ml-2 mb-0 font-weight-bold text-processo-lateral tx-card-1" style="display: block; text-align: left;">Contas a receber</span>
                    <br><br>
                    <span class="mt-3 ml-2 mb-0 font-regular-wt text-processo-lateral tx-card-2" style="display: block; font-size: 14px; text-align: left;">Cadastre suas contas para solicitação de pagamento</span>
                       <a class="mt-3 nav-link text-right" href="{{ route('financeiro.receber') }}">
                            <span class="acesso-link">Acesse aqui!</span>
                       </a>
            </div>
        </div>
    </div>
    <div class="col-4">
        <div class="card w-100 bck-card" style="padding-top: 20px; padding-bottom: 20px;">
            <div class="card-body">
                <img class="" src="{{ asset('/img/static/icons/processos.svg') }}" style="width: 30%; display: block;">
                    <span class="mt-3 ml-2 mb-0 font-weight-bold text-processo-lateral tx-card-1" style="display: block; text-align: left;">Contratos</span>
                    <br><br>
                    <span class="mt-3 ml-2 mb-0 font-regular-wt text-processo-lateral tx-card-2" style="display: block; font-size: 14px; text-align: left;">Cadastre suas contas para solicitação de pagamento</span>
                       <a class="mt-3 nav-link text-right" href="{{ route('contrato.index') }}">
                            <span class="acesso-link">Acesse aqui!</span>
                       </a>
            </div>
        </div>
    </div>
</div>
<div class="row mt-3">
    <div class="col-4">
        <div class="card w-100 bck-card" style="padding-top: 20px; padding-bottom: 20px;">
            <div class="card-body">
                <img class="" src="{{ asset('/img/static/icons/person-add.svg') }}" style="width: 30%; display: block;">
                    <span class="mt-3 ml-2 mb-0 font-weight-bold text-processo-lateral tx-card-1" style="display: block; text-align: left;">Usuários</span>
                    <br><br>
                    <span class="mt-3 ml-2 mb-0 font-regular-wt text-processo-lateral tx-card-2" style="display: block; font-size: 14px; text-align: left;">Aqui você controla seus usuários</span>
                       <a class="mt-3 nav-link text-right" href="{{ route('usuarios.index') }}">
                            <span class="acesso-link">Acesse aqui!</span>
                       </a>
            </div>
        </div>
    </div>
     <div class="col-4">
        <div class="card w-100 bck-card" style="padding-top: 20px; padding-bottom: 20px;">
            <div class="card-body">
                <img class="" src="{{ asset('/img/static/icons/person-add.svg') }}" style="width: 30%; display: block;">
                    <span class="mt-3 ml-2 mb-0 font-weight-bold text-processo-lateral tx-card-1" style="display: block; text-align: left;">Empresa</span>
                    <br><br>
                    <span class="mt-3 ml-2 mb-0 font-regular-wt text-processo-lateral tx-card-2" style="display: block; font-size: 14px; text-align: left;">Controle de empresa e filiais</span>
                       <a class="mt-3 nav-link text-right" href="{{ isset(auth()->user()->id_empresa) && !is_null(auth()->user()->id_empresa) ? route('empresa.show', ['empresa' => auth()->user()->id_empresa]) :  route('empresa.index')}}">
                            <span class="acesso-link">Acesse aqui!</span>
                       </a>

            </div>
        </div>
    </div>
</div>
@endsection
