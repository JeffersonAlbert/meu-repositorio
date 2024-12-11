@extends('financeiro.produtos.jsProdutos')
@extends('financeiro.contratos.jsContratos')
@extends('financeiro.contasreceber.js')
@extends('financeiro.contasreceber.js.formularioUpload-js')
@extends('financeiro.contasreceber.js.select-categoria-js')
@extends('layout.newLayout')

@section('content')
<div class="row">
    <div class="col-xl-12 col-lg-12">
        @include('financeiro.contasreceber.newForm')

    </div>
</div>
@include('financeiro.contasreceber.modalCadCliente')
@include('financeiro.contasreceber.modalCadCentroCusto')
@include('financeiro.contasreceber.modalCadCategoria')
@include('financeiro.contasreceber.modalCadastroRateio')
@include('financeiro.contratos.modalCadContratos')
@include('financeiro.produtos.modalCadProdutos')
@include('financeiro.contasreceber.modalInserirRecebimento')
@include('financeiro.contasreceber.modal.modalRemoverArquivo')
@endsection
