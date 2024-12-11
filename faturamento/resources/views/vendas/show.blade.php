@extends('layout.newLayout')

@push('scripts')
    @include('vendas.js.dados-orcamento')
@endpush

@section('content')
    <div class="row">
        <div class="col-2"></div>
        <div class="col-8">
            <div class="card w-100">
                <div class="card-body">
                    <div class="row mt-3">
                        <div class="col-4">
                            <h3>ORÇAMENTO</h3>
                        </div>
                        <div class="col-3">

                        </div>
                        <div class="col-1 text-end">
                            <h3>R$</h3>
                        </div>
                        <div class="col-4 text-end">
                            <h2><b>{{ \App\Helpers\FormatUtils::formatMoney($venda->valor_total) }}</b></h2>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <hr class="sidebar-divider">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <div class="row ml-3">
                                <span>Cliente</span>
                            </div>
                            <div class="row ml-3">
                                <a class="nome-cliente" href="#">{{ $dadosVenda->totalizador_itens }}</a>
                            </div>
                        </div>
                    </div>
                    <div class="row mt-6">
                        <div class="col-3">
                            <div class="row ml-3">
                                <span>Data orçamento</span>
                            </div>
                            <div class="row ml-3">
                                <span>{{ \App\Helpers\FormatUtils::formatDate($dadosVenda->data) }}</span>
                            </div>
                        </div>
                        <div class="col-3">
                            <div class="row">
                                <span>Validade do orçamento</span>
                            </div>
                            <div class="row">
                                <span>{{ \App\Helpers\FormatUtils::formatDate($dadosVenda->data) }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="row mt-6">
                        <div class="col-12">
                            <div class="row ml-1">
                                <div class="col-3">
                                    <span>Produto/Serviço</span>
                                </div>
                                <div class="col-3">
                                    <span>Detalhes do item</span>
                                </div>
                                <div class="col-1">
                                    <span>Qtde.</span>
                                </div>
                                <div class="col-2">
                                    <span>Valor</span>
                                </div>
                                <div class="col-3 text-end">
                                    <span>Subtotal</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <hr class="sidebar-divider"></hr>
                        </div>
                    </div>
                    <div class="addRows"></div>
                    <div class="row mt-6">
                        <div class="col-4">

                        </div>
                        <div class="col-4">
                            <span>Valor total</span>
                        </div>
                        <div class="col-4 text-end">
                            <span>{{ $dadosVenda->totalizador_itens }}</span>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-4"></div>
                        <div class="col-8">
                            <hr class="line-number"></hr>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-4"></div>
                        <div class="col-4">
                            <span>Desconto</span>
                        </div>
                        <div class="col-4 text-end">
                            <span>{{ $dadosVenda->totalizador_desconto }}</span>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-4"></div>
                        <div class="col-4">
                            <span>Frete a cobrar do cliente</span>
                        </div>
                        <div class="col-4 text-end">
                            <span>{{ $dadosVenda->totalizador_valor_adicional }}</span>
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-4"></div>
                        <div class="col-8">
                            <hr class="line-number"></hr>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-4"></div>
                        <div class="col-4">
                            <span><h3><b>Total líquido</b></h3></span>
                        </div>
                        <div class="col-4 text-end">
                            <span><h3><b>{{ $dadosVenda->totalizador_valor }}</b></h3></span>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-4"></div>
                        <div class="col-8">
                            <hr class="line-number"></hr>
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-9">
                            <hr class="line-number"></hr>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-9 text-center">
                            <span>Vendedor Responsável</span>
                        </div>
                    </div>
                    <div class="row text-center">
                        <div class="col-9 vendedor">
                            <span>{{ $venda->name }}</span>
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-9">
                            <hr class="line-number"></hr>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-2"></div>
    </div>

    <div class="row mt-3">
        <div class="col-2"></div>
        <div class="col-8">
            <div class="card">
                <div class="card-body w-100">
                    <div class="row">
                        <div class="col-1">
                            <a href="{{ route('vendas.imprimir', ['venda' => $venda->id]) }}" class="btn btn-sm btn-success">Imprimir</a>
                        </div>
                        <div class="col-1">
                            <a href="{{ route('vendas.edit', ['venda' => $venda->id]) }}" class="btn btn-sm btn-success">Editar</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-2"></div>
    </div>

@endsection
