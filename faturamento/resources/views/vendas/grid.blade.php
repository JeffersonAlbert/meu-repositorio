@extends('layout.newLayout')

@section('content')
   <div class="row">
        <div class="message col">
            @if(session('message'))
                <div class="alert alert-success">
                        {{ session('message') }}
                </div>
            @endif
            @if(session('error'))
                <div class="alert alert-danger">
                        {{ session('error') }}
                </div>
            @endif
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            <h2 class="font-regular-wt">Vendas</h2>
        </div>
    </div>
    <div class="row mb-1">
        <div class="col-9"></div>
        <div class="col-3 text-right">
            <a href="{{ route('vendas.create') }}" class="btn btn-sm btn-success btn-success-number">
                <i class="bi bi-plus"></i>
                Novo
            </a>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <table class="table table-striped table-responsive-sm
            table-head-number table-bordered">
                <thead class="head-grid-data">
                    <tr>
                        <th>Cliente</th>
                        <th>Vendedor</th>
                        <th>Valor</th>
                        <th>Vencimento</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody class="rel-tb-claro">
                    @foreach($vendas as $venda)
                        <tr class="td-grid-font align-middle">
                            <td>{{ $venda->nome }}</td>
                            <td>{{ $venda->name }} {{ $venda->last_name }}</td>
                            <td>R$ {{ App\Helpers\FormatUtils::formatMoney($venda->valor_total) }}</td>
                            <td>{{ App\Helpers\FormatUtils::formatDate($venda->updated_at) }}</td>
                            <td>
                                <a href="{{ route('vendas.edit', $venda->id) }}" class="btn btn-sm btn-warning">Editar</a>
                                <a href="{{ route('vendas.show', $venda->id) }}" class="btn btn-sm btn-secondary">Detalhes</a>
                                <form action="{{ route('vendas.destroy', $venda->id) }}" method="post" style="display: inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger">Excluir</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
        </table>
        </div>
    </div>
   <div class="row ml-1">
       <div class="pagination">
           {{ $vendas->links() }}
       </div>
   </div>
@endsection