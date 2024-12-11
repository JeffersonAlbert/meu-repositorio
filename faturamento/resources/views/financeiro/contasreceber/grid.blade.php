@extends('financeiro.contasreceber.js.grid-js')
@extends('financeiro.contasreceber.js')
@extends('layout.newLayout')

@section('content')

@include('financeiro.contasreceber.cardsContas')
@include('financeiro.contasreceber.table')
@include('financeiro.contasreceber.graficos')

@endsection
